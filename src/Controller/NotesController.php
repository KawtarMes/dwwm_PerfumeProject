<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Notes;
use App\Form\MediaType;
use App\Form\NotesType;
use App\Repository\NotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Contrôleur pour gérer les notes
#[Route('admin/notes',)]
class NotesController extends AbstractController
{
    // Action pour afficher toutes les notes
    #[Route('/', name: 'admin_notes')]
    public function index(NotesRepository $repo): Response
    {
        // Récupération de toutes les notes depuis le repository
        $notes = $repo->findAll();

        // Rendu de la vue avec les notes récupérées
        return $this->render('notes/index.html.twig', [
            'notes' => $notes,
        ]);
    }

    // Action pour ajouter une note
    #[Route('/new', name: 'admin_notes_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        // Création d'une nouvelle instance de Notes
        $note = new Notes();

        // Création du formulaire pour la note
        $form = $this->createForm(NotesType::class, $note);

        // Traitement de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification de la soumission du formulaire et de sa validité
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $note = $form->getData();

            // Persiste les données de la note
            $manager->persist($note);
            $manager->flush();

            // Redirection vers l'ajout de média pour la note
            return $this->redirectToRoute('admin_notes_add_media', ['id' => $note->getId()]);
        }

        // Rendu du formulaire pour ajouter une note
        return $this->render('notes/new.html.twig', ['form' => $form->createView()]);
    }

    // Action pour mettre à jour une note
    #[Route('/update/{id}', name: 'admin_notes_update')]
    public function update(Request $request, EntityManagerInterface $manager, Notes $note): Response
    {
        // Création du formulaire pour la note
        $form = $this->createForm(NotesType::class, $note);

        // Traitement de la soumission du formulaire
        $form->handleRequest($request);

        // Vérification de la soumission du formulaire et de sa validité
        if ($form->isSubmitted() && $form->isValid()) {
            // Persiste les données de la note
            $manager->persist($note);
            $manager->flush();

            // Redirection vers la gestion des notes
            return $this->redirectToRoute('admin_notes');
        }

        // Rendu du formulaire pour modifier une note
        return $this->render('notes/update_note.html.twig', ['form' => $form->createView()]);
    }

    // Action pour supprimer une note
    #[Route('/delete/{id}', name: 'admin_notes_delete')]
    public function delete(NotesRepository $repo, EntityManagerInterface $manager, $id): Response
    {
        // Récupération de la note à supprimer
        $note = $repo->find($id);

        if ($note) {
            // Suppression de la note
            $manager->remove($note);
            $manager->flush();
        }

        // Redirection vers la gestion des notes
        return $this->redirectToRoute('admin_notes');
    }

    // Action pour ajouter un média à une note
    #[Route('/add-media/{id}', name: 'admin_notes_add_media')]
    public function addMedia(Request $request, EntityManagerInterface $manager, Notes $note): Response
    {
        // Création d'un nouveau média
        $media = new Media();

        // Création du formulaire pour le média
        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération du fichier média
            $file = $form->get('src')->getData();
            // Génération d'un nom de fichier unique
            $fileName = uniqid().'.'.$file->guessExtension();
            // Déplacement du fichier vers le dossier d'upload
            $file->move($this->getParameter('upload_dir'), $fileName);

            // Attribution du fichier média à la note
            $media->setSrc($fileName);
            $note->setMedia($media);

            // Persiste le média
            $manager->persist($media);
            $manager->flush();

            // Redirection vers la page de détail de la note
            return $this->redirectToRoute('note_detail', ['id' => $note->getId()]);
        }

        // Rendu du formulaire pour ajouter un média
        return $this->render('notes/add_media.html.twig', [
            'form' => $form->createView(),
            'note' => $note,
        ]);
    }

    // Action pour supprimer un média d'une note
    #[Route('/delete-media/{id}', name: 'admin_notes_delete_media')]
    public function deleteMedia(EntityManagerInterface $manager, Notes $note): Response
    {
        // Récupération du média associé à la note
        $media = $note->getMedia();

        if ($media !== null) {
            // Suppression du fichier physique du système de fichiers
            $fileName = $media->getSrc();
            $filePath = $this->getParameter('upload_dir') . '/' . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Suppression du média de la base de données
            $manager->remove($media);
            $manager->flush();
        }

        // Redirection vers la gestion des notes
        return $this->redirectToRoute('admin_notes');
    }

    // Action pour afficher les détails d'une note
    #[Route('/note_detail/{id}', name: 'note_detail')]
    public function noteDetail(Notes $note): Response
    {
        // Rendu de la vue des détails de la note
        return $this->render('notes/detail_note.html.twig', ['note' => $note]);
    }
}
