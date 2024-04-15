<?php

namespace App\Service;

use App\Repository\PerfumeRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;

    public function __construct(
        private RequestStack $requestStack,
        private PerfumeRepository $perfumeRepository
    ) {
        // Initialisation de la session à partir de la RequestStack
        $this->session = $this->requestStack->getSession();
    }

    // Ajouter un produit au panier
    public function add($perfume): void
    {
        // Get the current cart or initialize an empty array
        $cart = $this->session->get('cart', []);
        // Get the id of the product
        $id = $perfume->getId();
        // If the product is already in the cart, increment the quantity
        if (!empty($cart[$id])) {
            $cart[$id]++;
        // Otherwise, add the product to the cart with quantity 1
        } else {
            $cart[$id] = 1;
        }

        // Save the cart back to the session
        $this->session->set('cart', $cart);
    }

    // Retirer un produit du panier
    public function remove($id): void
    {

        // Récupération du panier depuis la session
        $cart = $this->session->get('cart', []);


        // Vérifier si le produit est dans le panier et sa quantité
        // si $cart[21] existe ou 21 est un index
        // dump($cart["$id"]);
        // die;
        if (isset($cart["$id"]) && $cart["$id"] == 1) {
            // Si la quantité est 1, retirer complètement le produit du panier
            unset($cart["$id"]);
        } elseif (isset($cart["$id"]) && $cart["$id"] > 1) {
            // Si la quantité est supérieure à 1, diminuer la quantité du produit dans le panier
            $cart["$id"]--;
        }

        // Mettre à jour le panier dans la session
        $this->session->set('cart', $cart);
    }

    // Supprimer un produit du panier
    public function delete($perfume): void
    {
        // Get the current cart
        $cart = $this->session->get('cart', []);
        // Get the id of the product
        $id = $perfume->getId();
        // If the product is in the cart, remove it
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        // Save the cart back to the session
        $this->session->set('cart', $cart);
    }


    // Vider complètement le panier
    public function destroy(): void
    {
        // Supprimer le panier de la session
        $this->session->remove('cart');
    }

    // Obtenir le contenu du panier avec les détails des produits
    public function getCartWithData(): array
    {
        // Récupération du panier depuis la session
        $cart = $this->session->get('cart', []);
        $cartWithData = [];

        // Pour chaque produit dans le panier, récupérer ses détails depuis le repository
        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'perfume' => $this->perfumeRepository->find($id),
                'quantity' => $quantity
            ];
        }

        // Retourner le panier avec les détails des produits
        return $cartWithData;
    }

    // Calculer le total du panier
    public function getTotal(): float
    {
        // Initialiser le total à 0
        $total = 0;

        // Pour chaque produit dans le panier avec ses détails, calculer le prix total
        foreach ($this->getCartWithData() as $data) {
            $total += $data['perfume']->getPrice() * $data['quantity'];
        }

        // Retourner le total
        return $total;
    }
}
