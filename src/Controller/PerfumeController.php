<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Perfume;
use App\Form\MediaType;
use App\Form\PerfumeType;
use App\Repository\MediaRepository;
use App\Repository\PerfumeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/perfume')]
#[IsGranted(('ROLE_ADMIN'))]//Protection sur la route admin. 403 Si on est pas admin
class PerfumeController extends AbstractController
{
    // Gestion Produits: Parfums
    #[Route('/', name: 'admin_perfume')]
    public function index(PerfumeRepository $repo): Response
    {
        //je recupère la totalité des parfums
        $perfumes = $repo->findAll();

        return $this->render('perfume/index.html.twig', [
            'perfumes' => $perfumes,
        ]);
    }

    // Ajouter un parfum 
    #[Route('/new', name: 'admin_perfume_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $perfume = new Perfume();
        //formulaire avec PerfumeType 
        $form = $this->createForm(PerfumeType::class);

        //recuper les champs rempli du formulaire
        $form->handleRequest($request);

        // verification des deux condition : formulaire soumis et si il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            //on recupere les valeurs du form
            //on hydrate l'instance de la classe Perfume , qui recupere les valeurs à partir du formulaire
            $perfume = $form->getData();

            //preparer and executer la requete
            $manager->persist($perfume);
            $manager->flush();
            //redirection à media create pour rajouter image
            return $this->redirectToRoute('media_create', ['id' => $perfume->getId()]); //renvoyer l'id dans l'url de media create
        }
        //view du formulaire de creation de parfum
        return $this->render('perfume/newPerfume.html.twig', ['form' => $form->createView()]);
    }

    //supprimer un parfum
    #[Route('/delete/{id}', name: 'admin_perfume_delete')]
    public function delete(Perfume $perfume, EntityManagerInterface $manager): Response
    {

        $medias = $perfume->getMediasId(); // avec les medias du parfum
        //faire un for each pour les media les unlink et supprimer

        foreach ($medias as $media) {

            unlink($this->getParameter('upload_dir') . '/' . $media->getSrc());// prend le ou les medias et le supprime
            // Supprime le fichier média du système de fichiers en utilisant le chemin et le nom du fichier du média
            $manager->remove($media);//supprime les medias
        }
        $manager->remove($perfume); // supprimer le parfum
        $manager->flush();

        return $this->redirectToRoute('admin_perfume');
    }

    //Modifier un parfum
    #[Route('/update/{id}', name: 'admin_perfume_update')]
    public function update(Perfume $perfume, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(PerfumeType::class, $perfume);// prérempli par $perfume qui est l'objet parfum dont on a recuperé l'id

        //recuper les champs rempli du formulaire
        $form->handleRequest($request);

        // verification des deux condition : formulaire soumis et si il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            //on recupere les valeurs du form
            //on re hydrate l'instance de la classe Perfume récuperée , on change les valeurs des champs en fonction du form
            $perfume = $form->getData();

            //prepare and execute la requete
            $entityManager->persist($perfume);
            $entityManager->flush();
            //redirection 
            return $this->redirectToRoute('media_create', ['id' => $perfume->getId()]); //renvoyer l'id dans l'url de media create);
        }
        return $this->render('perfume/newPerfume.html.twig', ['form' => $form->createView()]);
    }

    //Rajouter un ou des medias àpres le rajout d'un parfum
    // on est rediridé apres la création d'un produit
    //l'id d produit en question pour mettre en lien le media avec le produit

    // #[Route('/media_create/{id}', name: 'media_create')]
    // public function media_create(Perfume $perfume, Request $request, EntityManagerInterface $manager, PerfumeRepository $repo, $id): Response
    // {
    //     $media = new Media(); //instance d'un nouveau media
    //     $media->setPerfume($perfume);

    //     $form = $this->createForm(MediaType::class, $media); // creation de form avec MediaType
    //     $form->handleRequest($request);

    //     //verification formulaire
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         //dd($form->getData());
    //         // Récupération du fichier envoyé dans le formulaire
    //         $files = $form->get('src')->getData();

    //         // nom de fichier unique basé sur la date, le titre du parfum et le nombre de médias associés

    //         $file_name = date('Y-d-d-H-i-s') . '-' . $perfume->getPerfumeTitle() . '.' . $files->getClientOriginalExtension();

    //         // Déplacement du fichier vers le dossier upload
    //         $files->move($this->getParameter('upload_dir'), $file_name);

    //         // Configuration des propriétés du média
    //         $media->setSrc($file_name);
    //         $manager->persist($media);

    //         $manager->flush();

    //         $this->addFlash('success', 'Le produit a bien été modifié'); //

    //         return $this->redirectToRoute('perfume_detail', ['id'=> $perfume->getId()]); // //////////
    //     }

    //     return $this->render('perfume/media_create.html.twig', [
    //         'form' => $form->createView(), "media" => $media, 'id'=> $perfume->getId()
    //     ]);
    // }
     // Page de mise en liens des médias (création de médias) avec le produit
     #[Route('/media/create/{id}', name: 'media_create')]
     public function media_create(Request $request, EntityManagerInterface $manager, PerfumeRepository $repository, $id): Response
     {
 
         $media = new Media();
 
 
         $form = $this->createForm(MediaType::class, $media);
 
 
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
 
             $perfume = $repository->find($id);
             // on recupère le fichier uploader
             $file = $form->get('src')->getData();
            // nombre de médias existant en lien avec le produit que l'on incrémente de 1 pour concaténé au title du média ainsi qu'au renommage du src ci-dessous
             $number = count($perfume->getMediasId()) + 1;
 
             // on renomme le fichier en le concaténant avec date complète, son numéro puis nom d'origine du fichier (le title qui est celui du produit) et enfin son extension
             $file_name = date('Y-m-d-H-i-s') . '-' . $perfume->getPerfumeTitle() . $number . '.' . $file->getClientOriginalExtension();
 
             // on upload en ayant préalablement configuré le parameter 'upload_dir' dans le services.yaml de Config
             //upload_dir: '%kernel.project_dir%/public/upload'
             $file->move($this->getParameter('upload_dir'), $file_name);
 
             // on reaffecte le renommage à l'objet
             $media->setSrc($file_name);
             // on ajoute au produit le media
             $perfume->addMediasId($media);
             // on persist le media
             $manager->persist($media);
             // on persist le produit
             $manager->persist($perfume);
             // on execute
             $manager->flush();
             $this->addFlash('success', 'Média créé, vous pouvez en ajouter un autre et valider ou cliquer sur terminé pour voir le détail');
 
             return $this->redirectToRoute('media_create', ['id' => $perfume->getId()]);
 
         }
 
 
         return $this->render('perfume/media_create.html.twig', [
             'form' => $form->createView(),
           
         ]);
     }
 

// Modier les medias d'un parfum
#[Route('/update/medias/{id}', name: 'perfume_update_medias')]
public function product_update_medias(Perfume $perfume):Response //si y a que un object ou un repository il va hydrater le $perfume avec l'$id recuperé dans l'url
{

return $this->render('perfume/perfume_update_medias.html.twig',['perfume'=>$perfume]);

}

/// suppression des médias par la page de gestion des médias
#[Route('/delete/medias/{id}', name: 'perfume_delete_medias')]
public function perfume_delete_medias(Request $request, MediaRepository $repository, EntityManagerInterface $manager, Perfume $perfume): Response
{
    // dd($repository->findBy(["perfume" => $perfume->getId() ]));
   // les checkbox de name choice[] sont récupéré du formulaire
    // car on peut vouloir supprimer plusieurs média.
    // on les récupère via notre formulaire en post avec
    // $request->request

    // $product_medias = $repository->findBy(["perfume" => $perfume->getId() ]);
    $medias_choices = $request->request->all()['choice'];
    // $perfume_id = '';
    foreach ($medias_choices as $id) {
  // on boucle sur tout les id receptionné
        // pour chaque tour on récupère le média grace au repository et la méthode find
        $media = $repository->find($id);
        // die($media->getSrc());
        // $perfume_id = $media->getPerfume()->getId();

          // on supprime du dossier d'upload le fichier
        unlink($this->getParameter('upload_dir') . '/' . $media->getSrc());

       // puis on le supprime de la bdd
        $manager->remove($media);

    }
    // on execute
    $manager->flush();
    $this->addFlash('info', 'Opération réalisée avec succès');


    return $this->redirectToRoute('perfume_detail', ['id' => $perfume->getId()]);

}




//afficher le detail produit 
#[Route('/perfume_detail/{id}', name: 'perfume_detail')]
public function perfume_detail(Perfume $perfume ):Response
{


return $this->render('perfume/perfume_detail.html.twig',['perfume'=>$perfume]);

}
}
