<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Media;
use App\Form\ArticleType;
use App\Form\MediaType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'admin_blog')]
    public function index(ArticleRepository $repo): Response
    {
        //recuperer les articles presents en base de donner
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    // Ajout et modification des articles
    #[Route('/new', name: 'admin_article_new')]
    #[Route('/update/{id}', name: 'admin_article_update')]
    public function new(Request $request, EntityManagerInterface $manager, $id = null): Response
    {
        if (!$id) {
            // si pas de articles selectionnée, ou inexistante j'en crée
            $article = new Article();
        } else {
            // on a un id donc on recupere l'objet , la article dans le repository via son id
            $article = $manager->getRepository(Article::class)->find($id);
        }
        // formulaire articlesType 
        $form = $this->createForm(ArticleType::class, $article);

        //gestion requete
        $form->handleRequest($request);

        // verification si formulaire soumis et si il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            //on recupere les valeurs du form
            //on crée une instance de la classe articles , qui recupere les valeurs
            $article = $form->getData();

            // on persiste les valeurs et on execute la requete
            $manager->persist($article);
            $manager->flush();

            // //redirection à l'ajout de media
            return $this->redirectToRoute('admin_article_add_media', ['id' => $article->getId()]); //renvoyer l'id de la article dans l'url de media create);
        }

        return $this->render('blog/new_article.html.twig', ['form' => $form->createView()]);
    }




    //Rajouter  media à un article
    #[Route('/admin/article/{id}', name: 'admin_article_add_media')]
    public function addMedia(Request $request, EntityManagerInterface $manager, Article $article): Response
    {
        // Crée un nouveau média
        $media = new Media();
        // Crée le formulaire pour le média
        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère le fichier média
            $file = $form->get('src')->getData();
            // Génère un nom de fichier à partir du titre de l'article
            $fileName = $article->getId() . '.' . $file->guessExtension();
            // Déplace le fichier vers le dossier d'upload
            $file->move(
                $this->getParameter('upload_dir'),
                $fileName
            );

            // Associe le fichier média à la article
            $media->setSrc($fileName);
            $article->addMedia($media); // rajouter le media à l'article

            // Persiste le média
            $manager->persist($media);
            $manager->flush();

            // // Redirige vers la page de gestion des articles
            return $this->redirectToRoute('admin_blog');
            // return $this->redirectToRoute('article_detail', ['id' => $article->getId()]);
        }

        // Rend le formulaire pour ajouter un média
        return $this->render('blog/add_media.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/detail/{id}', name: 'admin_detail_article')]
    public function detail(Article $article, EntityManagerInterface $manager): Response
    {
        // dd($article);
        return $this->render('blog/detail_article.html.twig', ['article' => $article]);
    }
    //Supprimer un article 
    #[Route('/delete/{id}', name: 'admin_article_delete')]
    public function delete(ArticleRepository $repo, EntityManagerInterface $manager, $id): Response
    {
        $article = $repo->find($id);
        $medias = $article->getMedias(); // avec les medias du parfum
        //faire un for each pour les media les unlink et supprimer

        foreach ($medias as $media) {

            unlink($this->getParameter('upload_dir') . '/' . $media->getSrc());
            $manager->remove($media); //supprime les medias
        }
        $manager->remove($article); // supprimer l'article
        $manager->flush();

        return $this->redirectToRoute('admin_blog');
    }

    //Supprimer le medias d'une article
    #[Route('/admin/article/{id}', name: 'admin_article_delete_media')]
    public function deleteMedia(EntityManagerInterface $manager, ArticleRepository $repo, $id): Response
    {
        $article = $repo->find($id);
        // Récupère le média associé à la article
        $media = $article->getMedias();
      

        // if ($media !== null) {
        //     // Supprime le fichier physique du système de fichiers
        //     $fileName = $media->getSrc();
        //     $filePath = $this->getParameter('upload_dir') . '/' . $fileName;
        //     if (file_exists($filePath)) {
        //         unlink($filePath);
        //     }

        //     // Supprime le média de la base de données
        //     $manager->remove($media);
        //     $manager->flush();
        // }

        // Redirige vers la page de gestion des article
        return $this->redirectToRoute('admin_blog');
    }
}
