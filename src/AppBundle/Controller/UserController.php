<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function indexAction(Request $request)
    {
        // on crée un utilisateur vide
        $user = new User();
        
        // on recupere une instance de notre formulaire
        // ce form est associé à l'utilisateur vide
        $registrationForm = $this->createForm(new RegistrationType(), $user);
        
        // traite le formulaire
        $registrationForm->handleRequest( $request );
        dump($user);
        
        // si les données sont valides ...
        if ($registrationForm->isValid()) {
            // hydrate les autres proprietés de notre User
            
                // hacher le mot de passe
                
                //generer un salt
                $salt = md5(uniqid());
                $user->setSalt( $salt );
                
                // generer un token
                $token = md5(uniqid());
                $user->setToken( $token );
                
                // les dates actuelles
                
            // sauvegarder le User en bdd
            dump($user);
            
        }
        // on shoot le formulaire à Twig (on oublie pas le create view)
        $params = array(
            "registrationForm" => $registrationForm->createView()
            
        );
        
        return $this->render('user/register.html.twig', $params);
    }
}
