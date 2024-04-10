<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/profil')]
class UserProfilController extends AbstractController
{
    //acceder à la page profil
    #[Route('/', name: 'app_user_profil')]
    public function index(): Response
    {
        //page avec info et possibilité de modifier  
        return $this->render('user_profil/profil.html.twig', []);
    }

    //modifer mon profil
    #[Route('/editprofil/{id}', name: 'edit_profil')]
    public function edit_profil($id, EditProfilType $editProfil, UserRepository $repo, EntityManagerInterface $manager, Request $request, MailerInterface $mailer): Response
    {
        $user = $repo->find($id); // je recuper l'user en session variable twig
        $form = $this->createForm(EditProfilType::class, $user); //creation form 
        $form->handleRequest($request); //traitement donnés à la submission form

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData(); //je re rempli avec les changement saisis

            // si le mail est nouveau donc != du mail en BDD faut le set a active= 0 et lui envoyer un mail de validation pour l'activer

            if ($user->getEmail() != $form->get('email')) {
                $user->setActive(0); // on le set à inactive tant qu'la pas valider

                //generer le token
                $token = $this->generateToken(); //on appelle la methode 
                $user->setToken($token); // on affecte le token à l'user

                //envoi d'email      
                $email = (new TemplatedEmail())
                    ->from('kawtarthebest@gmail.com')
                    ->to($user->getEmail())
                    ->subject('activé votre compte | nouveau email')
                    ->htmlTemplate('email/NewEmailValidation.html.twig')
                    // pass variables (name => value) to the template
                    ->context([
                        'user' => $user
                    ]);
                $mailer->send($email);

                $this->addFlash('success', 'Votre profil à bien été modifié, allez activer votre compte par mail');
                return $this->redirectToRoute('app_login'); //redirection pour la connexion

            }
            //message de confirmation de modif
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre profil à bien été modifié');
        }

        return $this->render('user_profil/edit_profil.html.twig', ['form' => $form]);
    }

    #[Route('/validate-email-change/{token}', name: 'validate_email_change')]
    public function validate_account_change($token,UserRepository $repository, EntityManagerInterface $manager):Response
    {
       // on va requeter un user sur son token , methode findOneby()
       $user=$repository->findOneBy(['token'=>$token]);

       //si on a un resultat on passe sa propriété active à 1 , son token à null et on persist, execute et redirige sur la page de connexion avec un message de succes
       if ($user){
        // dd($user);
    

        $user->setToken(NULL);
        $user->setActive(1);
        $manager->persist($user);
        $manager->flush();
        $this->addFlash('success',"Félicitation votre compte est activé, connectez vous");

       }else{
        $this->addFlash('danger',"Une erreur s'est produite");
       }
       return $this->redirectToRoute('app_login');
    }

    //fonction pour gener des tokens
    private function generateToken()
    {
        // rtrim supprime les espaces en fin de chaine de caractère
        // strtr remplace des occurences dans une chaine ici +/ et -_ (caractères récurent dans l'encodage en base64) par des = pour générer des url valides
        // ce token sera utilisé dans les envoie de mail pour l'activation du compte ou la récupération de mot de passe
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }


}
