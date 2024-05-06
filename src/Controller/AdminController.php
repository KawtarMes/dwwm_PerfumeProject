<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted(('ROLE_ADMIN'))]//Protection sur la route admin. 403 Si on est pas admin

class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [

        ]);
    }

    // Gestion utilisateur

    //Pour afficher les users
    #[Route('/users', name: 'admin_user_show')]
    public function show(UserRepository $repo): Response
    {
        $users = $repo->findAll(); //recuperer tout les user en BDD

        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }

    //////////////////////Desactiver /Activer un user/////////////////

    // /////Generer un token/////////////////////
    private function generateToken()
    {
        // rtrim supprime les espaces en fin de chaine de caractère
        // strtr remplace des occurences dans une chaine ici +/ et -_ (caractères récurent dans l'encodage en base64) par des = pour générer des url valides
        // ce token sera utilisé dans les envoie de mail pour l'activation du compte ou la récupération de mot de passe
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }


    #[Route('/desactivate/{id}/{active}', name: 'admin_user_desactivate')]
    public function delete(UserRepository $repo, EntityManagerInterface $manager, $id, $active, MailerInterface $mailer): Response
    {
        $user = $repo->find($id); //recuperer le user par son id
        $user->setActive($active); //variable je recupere dans le twig avec le if user.active (recuperé en BDD) et que je definie soit à 1 soit à 0 inversement de la valeur recuperée
        $manager->persist($user); //"prepare and execute" ma requete
        $manager->flush();

        if ($user->getActive() == 1) {
            $this->addFlash('success', "Votre compte a bien été Activé");


            //Envoyer un mail pour reactiver avec le token ///////////////
            //generer le token
            $token = $this->generateToken(); //on appelle la methode generateToken pour generer une chaine de caratères aléatoire et unique
            $user->setToken($token); // on affecte le token à l'user

            //mail pour la validation  à nouveau     
            $email = (new TemplatedEmail())
                ->from('kawtarthebest@gmail.com')
                ->to($user->getEmail())
                ->subject('✨Bienvenue chez Perfumes, Activez votre compte✨')

                // chemin vers le template de l'email de validation
                ->htmlTemplate('email/validateAccount.html.twig')
                
                // pass variables (name => value) to the template
                ->context([
                    'user' => $user
                ]);

            $mailer->send($email);
        } else {
            $this->addFlash('success', "Votre compte a bien été Désactiver");
            //mail pour notifier la desactivation
            $email = (new TemplatedEmail())
                ->from('kawtarthebest@gmail.com')
                ->to($user->getEmail())
                ->subject('votre compte a été desactivé')
                // path of the Twig template to render
                ->htmlTemplate('email/DesactivatedAccount.html.twig')
        
                ->context([
                    'user' => $user
                ]);
            $mailer->send($email);
        }
        // rediriger vers la route de Gestion users
        return $this->redirectToRoute('admin_user_show');
    }



    //////////////////////////Modifier le roles user////////////////////////////
    #[Route('/update/{id}/{role}', name: 'update_role_user')]
    public function update($id, $role, UserRepository $repo, EntityManagerInterface $manager): Response
    {
        $user = $repo->find($id); // je cherche user par son id
        $user->setRoles([$role]); // je reaffecte la valeur de role grace à ma variable $role en twig 

        $manager->persist($user);
        $manager->flush();


        return $this->redirectToRoute('admin_user_show');
    }
}
