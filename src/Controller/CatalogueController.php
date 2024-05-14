<?php

namespace App\Controller;

use App\Entity\Notes;
use App\Entity\OlfactiveFamily;
use App\Entity\Perfume;
use App\Repository\NotesRepository;
use App\Repository\OlfactiveFamilyRepository;
use App\Repository\PerfumeRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/catalogue')]
class CatalogueController extends AbstractController
{
    #[Route('/', name: 'app_catalogue')]
    public function index(PerfumeRepository $repo, OlfactiveFamilyRepository $familyRepo, NotesRepository $notesRepo, Request $request): Response
    {
// //recupere le choix d efam olf du user
//         $olfactiveFamily = $request->request->get('olfactiveFamily');

//         if(!empty($olfactiveFamily)){
//             $filteredPerfumes = $repo->findByOLf($olfactiveFamily);
//         }

        $perfumes = $repo->findAll();
        $families= $familyRepo->findAll();
        $notes= $notesRepo->findAll();
        return $this->render('catalogue/index.html.twig', [
            'perfumes' => $perfumes,
            'families'=>$families,
            'notes'=>$notes
        ]);
    }

    

    // #[Route('/filter', name: 'app_catalogue_filter', methods: ['POST'])]
    // public function filter(Request $request, PerfumeRepository $perfumeRepository): Response
    // {
    //     $olfactiveFamily = $request->request->get('olfactiveFamily');
    //     $brand = $request->request->get('brand');
    //     $priceRange = $request->request->get('priceRange');
    //     $noteId = $request->request->get('note');

    //     if(!empty($olfactiveFamily) || !empty($brand) || !empty($priceRange))
    //     {
    //         $filteredIds = $perfumeRepository->findByFilters((int)$olfactiveFamily, $brand, $priceRange, null);
    //         $filteredPerfumes = [];
            
    //         foreach ($filteredIds as $id) {
                
    //             # code...
    //             $perfume = $perfumeRepository->find($id);
    //             array_push($filteredPerfumes, $perfume);
    //         }
    //         dump($filteredPerfumes);
    
    //     }


    //     // var_dump($perfumeRepository->findByFilters($olfactiveFamily, $brand,$priceRange,null));
    //     // die;
    //     // Appliquer les filtres en fonction des paramètres
    //     // if ($olfactiveFamily && !$brand && !$priceRange && !$noteId) {
    //     //     // Filtrage par famille olfactive uniquement
    //     //     $filteredPerfumes = $perfumeRepository->findByOlfactiveFamily($olfactiveFamily);
    //     // } elseif (!$olfactiveFamily && $brand && !$priceRange && !$noteId) {
    //     //     // Filtrage par marque uniquement
    //     //     $filteredPerfumes = $perfumeRepository->findByBrand($brand);
    //     // } elseif (!$olfactiveFamily && !$brand && $priceRange && !$noteId) {
    //     //     // Filtrage par gamme de prix uniquement
    //     //     $filteredPerfumes = $perfumeRepository->findByPriceRange($priceRange);
    //     // } elseif (!$olfactiveFamily && !$brand && !$priceRange && $noteId) {
    //     //     // Filtrage par note uniquement
    //     //     $filteredPerfumes = $perfumeRepository->findByNoteId($noteId);
    //     // } elseif ($olfactiveFamily && $brand && !$priceRange && !$noteId) {
    //     //     // Filtrage par famille olfactive et marque
    //     //     $filteredPerfumes = $perfumeRepository->findByOlfactiveFamilyAndBrand($olfactiveFamily, $brand);
    //     // } 
    //     //elseif (...) {
    //         // Ajoutez d'autres conditions de filtrage au besoin...
    //     //}
    //      else {
    //         // Aucun filtre appliqué, récupérer tous les parfums
    //         $filteredPerfumes = $perfumeRepository->findAll();
    //     }

        
    //     return $this->redirectToRoute('app_catalogue', [
    //         'perfumes' => $filteredPerfumes,
    //         'selectedOlfactiveFamilyId' => $olfactiveFamily // Optionnel : utile pour garder la sélection dans le menu déroulant
    //     ]);
    // }
    



    //aller à la page qui affiche le detail du produit
    #[Route('/show-perfume/{id}', name: 'app_show_perfume')]
    public function show(PerfumeRepository $repo, $id):Response
    {
    //je recupere l'id du parfum clické et je vais à son detail
    $perfume = $repo->find($id);
    
    return $this->render('catalogue/show_perfume.html.twig',['perfume'=>$perfume]);
    
    }
}
