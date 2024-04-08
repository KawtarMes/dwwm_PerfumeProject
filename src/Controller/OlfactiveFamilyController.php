<?php

namespace App\Controller;

use App\Entity\OlfactiveFamily;
use App\Form\OlfactiveFamilyType;
use App\Repository\OlfactiveFamilyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('admin/family')]
class OlfactiveFamilyController extends AbstractController
{
    #[Route('/', name: 'admin_olfactive_family')]
    public function index(OlfactiveFamilyRepository $repo): Response
    {
        // je recupere toutes les familles familes olfactives , pour raccourcir on les appellera families
        $families = $repo->findAll();
        return $this->render('olfactive_family/index.html.twig', [
            'families' => $families,
        ]);
    }

    // Ajouter et modifier Des Categorie de Parfums/Famille olfactives 

    #[Route('/new', name: 'admin_familyOlf_new')]
    #[Route('/update/{id}', name: 'admin_familyOlf_update')]
    public function new(Request $request, EntityManagerInterface $manager, $id = null): Response
    {
        if (!$id) {
            // si n'existe pas donc pas d'id, je crée une nouvelle famille
            $family = new OlfactiveFamily;
        } else {
            // si la famille existe on va la modifier,
            // on la recupere grace à son id dans l'url
            $family = $manager->getRepository(OlfactiveFamily::class)->find($id);
        }
        // Dans tous les cas on passe par le formulaire OlfactiveFamilyType avec le buildform
        $form = $this->createForm(OlfactiveFamilyType::class, $family);

        //gestion de la requete entrante
        $form->handleRequest($request);

        // verification des deux condition : formulaire soumis et si il est valide
        if ($form->isSubmitted() && $form->isValid()) {


            //on recupere les valeurs du form
            //on crée une instance de la classe OlfactiveFamily , qui recupere les valeurs
            $family = $form->getData();

            // on persiste les valeurs
            $manager->persist($family);
            //on execute 
            $manager->flush();

            //redirection vers la route admin_olfactive_family , qui affiche le tableau de gestion
            return $this->redirectToRoute('admin_olfactive_family');
        }
        return $this->render('olfactive_family/newFamily.html.twig', ['formulaire' => $form]);
    }

    //Supprimer une Famille Olfactive
    #[Route('/delete/{id}', name: 'admin_familyOlf_delete')]
    public function delete(OlfactiveFamilyRepository $repo, EntityManagerInterface $manager, $id): Response
    {
        $family = $repo->find($id);

        if ($family) {
            $manager->remove($family);
            $manager->flush();

            return $this->redirectToRoute('admin_olfactive_family');
        }
    }
}
