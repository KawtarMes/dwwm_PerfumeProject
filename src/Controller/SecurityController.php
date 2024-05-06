<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewPasswordType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/security')]
class SecurityController extends AbstractController
{

    //Inscription , creation d'utilisateur///////////////////////////////////////////////////
    #[Route('/signup', name: 'sign_up')]
    public function signup(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher, MailerInterface $mailer): Response
    {
        //  crée une instance de user
        $user = new User();

        //créer le form 
        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //set sasie du form dans user
            $user = $form->getData();

            //hasher le mot de passe
            $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));

            //set user pas active dans un premier
            $user->setActive(0);

            //faire un token
            $token = $this->generateToken();
            //assigner ce token au user
            $user->setToken($token);

            //prepare and execute
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre compte a bien été crée, Allez vite l'activer");


            //mail pour la validation       
            $email = (new TemplatedEmail())
                ->from('kawtarthebest@gmail.com')
                ->to($user->getEmail()) 
                ->subject('✨Bienvenue chez Perfumes, Activez votre compte✨')
                // chemin du template du mail 
                ->htmlTemplate('email/validateAccount.html.twig')


                //var twig
                ->context([
                    'user' => $user
                ]);

            $mailer->send($email);
            // rédirection à la page de connexion
            return $this->redirectToRoute('app_login');

        }

        return $this->render('security/sign_up.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //genère des tokens //////////////////////////////////////////
    private function generateToken()
    {
        // rtrim supprime les espaces en fin de chaine de caractère
        // strtr remplace des occurences dans une chaine ici +/ et -_ (caractères récurent dans l'encodage en base64) par des = pour générer des url valides
        // ce token sera utilisé dans les envoie de mail pour l'activation du compte ou la récupération de mot de passe
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    //valider un account//////////////////////////////////////////////////////////////////////
    #[Route('/validate-account/{token}', name: 'validate_account')]
    public function validate_account($token, UserRepository $repo, EntityManagerInterface $manager)
    {
        // on recherche un user par son token receptionné en paramètre de l'url
        $user = $repo->findOneBy(['token' => $token]);
        if ($user) {
            // si on récupère un utilisateur
            // on passe sa clé active à 1
            // et on reset son token
            $user->setActive(1);
            $user->setToken(null);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', "Votre compte a bien été activé");
        } else {
            // sinon on renvoi un message d'erreur
            $this->addFlash('danger', "Une erreur s'est produite");
        }

        return $this->redirectToRoute('app_login');
    }

    //Connexion , Log In , ///////////////////////////////////////////////////////////////////
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }



    //Se deconnecter Log out/////////////////////////////////////////////////////////////////
    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    //Mode passe oublié/////////////////////////////////////////////////////////////////
    #[Route('/reset/password', name: "reset_password")]
    public function reset(Request $request, UserRepository $repo, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        // récupération de la saisie de l'email provenant du formulaire formulaire
        $email = $request->request->get('email', '');
        if (!empty($email)) {
            // si on a un email de renseigné
            // on requête un user grace à son email
            $user = $repo->findOneBy(['email' => $email]);

            if ($user != null && $user->isActive()) {
                // si il y a un user et que son compte est actif
                // on génère un token que l'on enregistre en BDD
                $user->setToken($this->generateToken());
                //prepare and excecute 
                $entityManager->persist($user);
                $entityManager->flush();

                // on prépare l'email
                $email = (new TemplatedEmail())
                    ->from('kawtarthebest@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Mot de passe perdu?')
                    ->htmlTemplate('email/reset_password.html.twig')
                    ->context([
                        'user' => $user,
                       
                    ]);

                $mailer->send($email);

                $this->addFlash('success', "Un email de réinitialisation vous a été envoyé.");

                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash('error', "Votre compte lié à cet email n'est pas actif. Veuillez l'activer d'abord.");
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/forgotPassword.html.twig');
    }

    //nouvelle pass word , validation/////////////////////////////////////////////////////////////////
    // route d'entrée au click du mail de réinitialisation
    #[Route('/new/password/{token}', name: "new_password")]
    public function newPassword($token, UserRepository $repo, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        // on récupère un user par son token
        $user = $repo->findOneBy(['token' => $token]);
        if ($user) {
            // si il y a on créé le formulaire de reset password
            $form = $this->createForm(NewPasswordType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // on hash le nouveau mdp
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                // on repasse le token à null
                $user->setToken(null);
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', "Votre mot de passe a bien été modifié");
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/newPassword.html.twig', [
                'form' => $form
            ]);
        }
    }
    // Historique commande de l'user connecté

    //Detail commande
    
}
