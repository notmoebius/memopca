<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\ResetPassType;
use App\Repository\LoginRepository;
use Respect\Validation\Validator as v;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Login;


class SecurityController extends AbstractController
{
    /**
     * @Route("/se-connecter", name="app_login")
     */
    public function Login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function Logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

        /**
 * @Route("/mot-de-passe-oublie", name="app_forgotten_password")
 */
public function ForgottenPass(Request $request, LoginRepository $logins, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator
): Response
{
    // On initialise le formulaire
    $form = $this->createForm(ResetPassType::class);

    // On traite le formulaire
    $form->handleRequest($request);

    // Si le formulaire est valide
    if ($form->isSubmitted() && $form->isValid()) {
        // On récupère les données
        $donnees = $form->getData();

        // On cherche un utilisateur ayant cet e-mail
        $login = $logins->findOneByEmail($donnees['email']);

        // Si l'utilisateur n'existe pas
        if ($login === null) {
            // On envoie une alerte disant que l'adresse e-mail est inconnue
            $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
            
            // On retourne sur la page de connexion
            return $this->redirectToRoute('app_forgotten_password');
        }

        // On génère un token
        $token = $tokenGenerator->generateToken();

        // On essaie d'écrire le token en base de données
        try{
            $login->setResetToken($token);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($login);
            $entityManager->flush();
        } catch (\Exception $e) {
            $this->addFlash('warning', 'Une erreur est survenue'. $e->getMessage());
            return $this->redirectToRoute('app_login');
        }

        // On génère l'URL de réinitialisation de mot de passe
        $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

        // On génère l'e-mail
        $message = (new \Swift_Message('Mot de passe oublié'))
            ->setFrom('votre@adresse.fr')
            ->setTo($login->getEmail())
            ->setBody(
                "Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour le site MemoPCA. Veuillez cliquer sur le lien suivant : " .$url,
                'text/html'
            )
        ;

        // On envoie l'e-mail
        $mailer->send($message);

        // On crée le message flash de confirmation
        $this->addFlash('success', 'E-mail de réinitialisation du mot de passe envoyé !');

        // On redirige vers la page de login
        return $this->redirectToRoute('app_login');
    }

    // On envoie le formulaire à la vue
    return $this->render('security/forgottenPassword.html.twig',['emailForm' => $form->createView()]);
}

/**
 * @Route("/reset-pass/{token}", name="app_reset_password")
 */
    public function ResetPassword(Request $request, LoginRepository $login, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
    $errors = [];
    // on vérifie les erreurs
    if(!empty($_POST)){
        $safe = array_map('trim', array_map('strip_tags', $_POST));

        if(!v::length(6,20)->validate($safe['password'])){ // validation mot de passe
            $errors[] = 'Votre mot de passe doit comporter entre 6 et 20 caractères';
        }
        if(!v::equals($safe['password'])->validate($safe['confirmPassword'])){
            $errors[] = 'Vos mots de passe ne correspondent pas';
        }
            // Si l'utilisateur n'existe pas
        if ($login === null) {
            $errors [] = 'Utilisateur n\'éxiste pas';
            }

    if(count($errors) === 0){

        // On cherche un utilisateur avec le token donné
        $login = $this->getDoctrine()->getRepository(Login::class)->findOneBy(['reset_token' => $token]);

        
       // Si le formulaire est envoyé en méthode post
    if ($request->isMethod('POST')) {

        // On supprime le token
        $login->setResetToken(null);

        // On chiffre le mot de passe
        $login->setPassword($passwordEncoder->encodePassword($login, $request->request->get('password')));

        // On stocke
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($login);
        $entityManager->flush();

        // On crée le message flash
        $this->addFlash('success', 'Mot de passe mis à jour');

        // On redirige vers la page de connexion
        return $this->redirectToRoute('app_login');

        
    } // Fermeture du request
    
    }else {
        // Retourne les erreurs listés
        $this->addFlash('danger', implode('<br>', $errors));    
        }
    }
    // Si on n'a pas reçu les données, on affiche le formulaire
    return $this->render('security/resetPassword.html.twig', ['token' => $token]);
    }

}
