<?php

namespace App\Controller;

use App\Entity\Perfume;
use App\Repository\PerfumeRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/catalogue')]
class CatalogueController extends AbstractController
{
    #[Route('/', name: 'app_catalogue')]
    public function index(PerfumeRepository $repo): Response
    {
        //recuperer les parfums pour l'affichage en catalogue
        $perfumes= $repo->findAll();
        return $this->render('catalogue/index.html.twig', [
            'perfumes' => $perfumes,
        ]);
    }

    //aller à la page qui affiche le detail du produit
    #[Route('/show-perfume/{id}', name: 'app_show_perfume')]
    public function show(PerfumeRepository $repo, $id):Response
    {
    //je recupere l'id du parfum clické et je vais à son detail
    $perfume = $repo->find($id);
    
    return $this->render('catalogue/show_perfume.html.twig',['perfume'=>$perfume]);
    
    }
}
