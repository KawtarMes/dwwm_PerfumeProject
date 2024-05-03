<?php

namespace App\Controller;

use App\Entity\Perfume;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/cart')]
class CartController extends AbstractController
{


    //Route pour ajouter un parfum au panier
    #[Route('/add-to-cart/{id}', name: 'add_to_cart')]
    public function addCart(Perfume $perfume, CartService $cartService):Response
    {
        $cartService->add($perfume);
        $this->addFlash('info', 'Parfum ajouté au panier');
    
    return $this->redirectToRoute('app_catalogue');
    }

    // Route pour remove un parfum
    #[Route('/remove/{id}', name: 'remove_from_cart')]
    public function removeFromCart(CartService $cartService, Perfume $perfume):Response
    {
        $cartService->remove($perfume);
        $this->addFlash('info','Parfum retiré du panier');
    
    return $this->redirectToRoute('cart');
    
    }

    //Route pour retrait complet d'un parfum du panier 
    #[Route('/delete/{id}', name: 'delete_from_cart')]
    public function delete(Perfume $perfume, CartService $cartService):Response
    {
        $cartService->delete($perfume);
        $this->addFlash('info','Parfum retiré du panier');
    return $this->redirectToRoute('cart');
    
    }
    //Vider le panier
    #[Route('/destroy-cart', name: 'destroy_cart')]
    public function destroyCart(CartService $cartService):Response
    {
        $cart = $cartService->destroy();
    
        return $this->redirectToRoute('cart');
    
    }
        // page de panier et finalisation de commande avec stripe implémenté
        #[Route('/', name: 'cart')]
        public function cart(CartService $cartService): Response
        {
            $cart = $cartService->getCartWithData();
    
    
            return $this->render('cart/index.html.twig', [
                
                'cart' => $cart,
                'total' => $cartService->getTotal()
            ]);
        }
}
