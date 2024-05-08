<?php
// src/Controller/UserController.php

namespace App\Controller;

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Login extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/login', name: 'app_login', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('login/login.html.twig');
    }

    #[Route('/loginn', name: 'app_loginn', methods: ['POST'])]
   public function login(Request $request): Response
  {

    $email = $request->request->get('email');
    $password = $request->request->get('password');
    
    // Log the email and password
    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    
        // Check if a user was found with the provided email
        if ($user) {
            // Verify the password using Symfony's password encoder

            if ($password ==$user->getPassword()) {
                $roles = $user->getRoles();
                // Authentication successful
                // You can proceed with whatever action you need for authenticated users
                // For example, setting up a session, generating a token, etc.
                // Do something here after successful authentication
                if  (in_array('ROLE_PRO', $roles)) {
                    $responseData = [

                        'sucess' => 'connect as profession ',
                        
                    ];  
                }elseif (in_array('ROLE_USER', $roles)) {
                    $responseData = [

                        'sucess' => 'connect as user ',
                        
                    ];  
                } else {
                    // Handle other roles or scenarios
                }
               
            } else {
                // Password doesn't match
                // Handle incorrect password scenario
                $responseData = [

                    'erreur' => ' Password doesn t match',
                    
                ];  
            }
        } else {
            // User not found with the provided email
            $responseData = [

                'erreur' => 'User not found with the provided email',
                
            ];   // Handle user not found scenario
        }

        // Redirect the user to '/'
        
    

    // Your login logic goes here

    // Construct a JSON response with the email and password
    

        return $this->json($responseData);
}

}
