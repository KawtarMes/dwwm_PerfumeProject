<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Notes;
use App\Form\MediaType;
use App\Form\NotesType;
use App\Repository\NotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Nop;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('admin/notes',)]
class NotesController extends AbstractController
{
    #[Route('/', name: 'admin_notes')]
    public function index(NotesRepository $repo): Response
    {
        // je récupere la totalité notes
        $notes= $repo->findAll();

        return $this->render('notes/index.html.twig', [
            'notes' => $notes,
        ]);
    }

    // Ajout et modification des notes
    #[Route('/new', name: 'admin_notes_new')]
    #[Route('/update/{id}', name: 'admin_notes_update')]
    public function new(Request $request,EntityManagerInterface $manager,$id=null):Response
    {
    if(!$id){
        // si pas de notes selectionnée, ou inexistante j'en crée
        $note = new Notes(); 
    }else{
        // on a un id donc on recupere l'objet , la note dans le repository via son id
        $note = $manager->getRepository(Notes::class)->find($id);

    }
    // formulaire NotesType 
    $form =$this->createForm(NotesType::class, $note);
    
    //gestion requete
    $form->handleRequest($request);

    // verification si formulaire soumis et si il est valide
            if($form->isSubmitted() && $form->isValid()){


                //on recupere les valeurs du form
                //on crée une instance de la classe Notes , qui recupere les valeurs
                $note = $form->getData();

                // on persiste les valeurs
                $manager->persist($note); 
                // on execute la requete
                $manager->flush();
                // //redirection à la gestion de notes
                // return $this->redirectToRoute('admin_notes');
                return $this->redirectToRoute('admin_notes_add_media', ['id' => $note->getId()]); //renvoyer l'id de la note dans l'url de media create);
            }
    
    return $this->render('notes/new.html.twig',['form'=>$form->createView()]);
    
    }


    //Suppression des notes 
    #[Route('/delete/{id}', name: 'admin_notes_delete')]
    public function delete(NotesRepository $repo,EntityManagerInterface $manager, $id):Response
    {
        $note = $repo->find($id); //je recupère la note selectionnée

        if($note){
            //si on a bien retrouver la note on passe à sa suppression
            $manager->remove($note);
            $manager->flush();
           
        }
         //renvoie à la page de gestion note
         return $this->redirectToRoute('admin_notes');
    
    }
//Rajouter un media à une notes olfactive
    #[Route('/admin/notes/{id}', name: 'admin_notes_add_media')]
    public function addMedia(Request $request, EntityManagerInterface $manager, Notes $note): Response
    {
        // Crée un nouveau média
        $media = new Media();
        // Crée le formulaire pour le média
        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère le fichier média
            $file = $form->get('src')->getData();
            // Génère un nom de fichier unique
            $fileName = uniqid().'.'.$file->guessExtension();
            // Déplace le fichier vers le dossier d'upload
            $file->move(
                $this->getParameter('upload_dir'),
                $fileName
            );
    
            // Associe le fichier média à la note
            $media->setSrc($fileName);
            $note->setMedia($media);
    
            // Persiste le média
            $manager->persist($media);
            $manager->flush();
    
            // // Redirige vers la page de gestion des notes
            // return $this->redirectToRoute('admin_notes');
            return $this->redirectToRoute('note_detail', ['id' => $note->getId()]);
        }
    
        // Rend le formulaire pour ajouter un média
        return $this->render('notes/add_media.html.twig', [
            'form' => $form->createView(),
            'note' => $note,
        ]);
    }  
//Supprimer le medias d'une note
    #[Route('/admin/notes/{id}', name: 'admin_notes_delete_media')]
public function deleteMedia(EntityManagerInterface $manager, Notes $note): Response
{
    // Récupère le média associé à la note
    $media = $note->getMedia();

    if ($media !== null) {
        // Supprime le fichier physique du système de fichiers
        $fileName = $media->getSrc();
        $filePath = $this->getParameter('upload_dir') . '/' . $fileName;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Supprime le média de la base de données
        $manager->remove($media);
        $manager->flush();
    }

    // Redirige vers la page de gestion des notes
    return $this->redirectToRoute('admin_notes');
}
//Note Detail affichage
#[Route('/note_detail/{id}', name: 'note_detail')]
public function perfume_detail(Notes $note ):Response
{


return $this->render('notes/detail_note.html.twig',['note'=>$note]);

}
}
