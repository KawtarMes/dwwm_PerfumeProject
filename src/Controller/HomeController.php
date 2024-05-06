<?php

namespace App\Controller;

use App\Repository\PerfumeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    // Catalogue
    #[Route('/', name: 'app_home')]
    public function index(PerfumeRepository $repo): Response
    {
        //je recupere tout les parfums dans la table perfumes
        $perfumes = $repo->findAll(); 

        return $this->render('home/index.html.twig', [
            'perfumes'=>$perfumes,
        ]);
    }
}
