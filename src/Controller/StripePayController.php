<?php

namespace App\Controller;

use App\Entity\OrderPurchase;
use App\Entity\Purchase;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// install stripe composer sur le terminal avec la cmnd: composer require stripe/stripe-php
class StripePayController extends AbstractController
{

    // après création d'un compte stripe
    // doc: https://stripe.com/docs/checkout/quickstart


    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/stripe/pay', name: 'app_stripe_pay')]
    public function index(CartService $cs): Response
    {

        $fullCart = $cs->getCartWithData();

        // tableau recevant les informations des produits
        $line_items = [];


        foreach ($fullCart as $item) {
            $line_items[] = [
                'price_data' => [
                    'unit_amount' => $item['perfume']->getPrice() * 100, 'currency' => 'EUR',
                    'product_data' => ['name' => $item['perfume']->getPerfumeTitle()
                    ]
                ],
                'quantity' => $item['quantity']


            ];
        }

    // Authentification avec la clé API Stripe
    $stripeKey = $this->getParameter('stripe_key');
    Stripe::setApiKey($stripeKey);
        // création de la session de paiement à transmettre au template de stripe avec définition des url de success ou d'echec
        $session = Session::create([
//                 'success_url'=>'https://www.lock.cezdigit.com/commande/success',
//                 'cancel_url'=>'https://www.lock.cezdigit.com/wishList',
            'success_url' => 'http://127.0.0.1:8000/commande/success',
            'cancel_url' => 'http://127.0.0.1:8000/cart',
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment'
        ]);

        return $this->redirect($session->url, 303);


    }

    // url de succes, ici on peu valider la commande
    #[Route('/commande/{success}', name: 'commande')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function commande(CartService $cartService, EntityManagerInterface $manager, $success = null): Response
    {
        if ($success) {
            // création d'une nouvelle commande dont on rempli les informations
            $order = new OrderPurchase();
            $order->setUser($this->getUser());
            $order->setOrderDate(new \DateTime());
            $order->setOrderStatus(0);
            $manager->persist($order);

         // on bucle sur le panier, pour chaque tour une nouvelle
            //purchase (ligne d'achat et on rempli l'objet)
            foreach ($cartService->getCartWithData() as $item) {
                $purchase = new Purchase();
                $purchase->setOrderPurchase($order);
                $purchase->setQuantity($item['quantity']);
                $purchase->setPerfume($item['perfume']);
                $manager->persist($purchase);

            }

            $manager->flush();

            $this->addFlash('success', 'Merci pour votre confiance');
            // on détruit le panier
            $cartService->destroy();
            return $this->redirectToRoute('app_catalogue');

        } else {
            $this->addFlash('danger', 'Un problème est survenu merci de réitérer votre paiement');
            return $this->redirectToRoute('cart');

        }


    }


}
