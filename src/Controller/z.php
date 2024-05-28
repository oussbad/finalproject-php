<?php
// src/Controller/UserController.php

namespace App\Controller;

namespace App\Controller;

use App\Entity\User;
use App\Entity\Patient;
use App\Entity\Profession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class z extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/loginnnnn', name: 'app_loginnnnn', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('login/login.html.twig',[
            'erreur' => '',
        ]);
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
                    return $this->redirectToRoute('admin');
                }elseif (in_array('ROLE_USER', $roles)) {
                    return $this->redirectToRoute('app_login');
                } else {
                    // Handle other roles or scenarios
                }
               
            } else {
                // Password doesn't match
                // Handle incorrect password scenario
                return $this->render('login/login.html.twig',[
                    'erreur' => 'Password doesn t match',
                ]);
                
            }
        } else {
            // User not found with the provided email
            return $this->render('login/login.html.twig',[
                'erreur' => 'User not found with the provided email',
            ]);
              // Handle user not found scenario
        }

        // Redirect the user to '/'
        
    

    // Your login logic goes here

    // Construct a JSON response with the email and password
    

        
}
#[Route('/registerrrrrrr', name: 'app_registerrrr', methods: ['GET'])]
public function createRegisterForm(): Response
{
    return $this->render('login/ins.html.twig');
}

#[Route('/registerrrr', name: 'app_register_submit', methods: ['POST'])]
public function register(Request $request): Response
{
    $user = new User();
    
    $email = $request->request->get('email');
    $password = $request->request->get('password');
    $userType = $request->request->get('userType');
    $INPE = $request->request->get('INPE');
    $CIN = $request->request->get('CIN');

    $user->setEmail($email);
    $user->setPassword($password); // Use password encoder

    if ($userType === 'patient') {
        $user->setRoles(['ROLE_USER']);
        $patient = new Patient();
        $patient->setNom($request->request->get('nom'));
        $patient->setPrenom($request->request->get('prenom'));
        $patient->setCin($CIN);
        $user->setPatient($patient);
    } elseif ($userType === 'professional') {
        $user->setRoles(['ROLE_PRO']);
        $profession = new Profession();
        $profession->setNPE($INPE);
        $profession->setNom($request->request->get('nom'));
        $profession->setPrenom($request->request->get('prenom'));
        $user->setProfession($profession);
    }

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    return $this->redirectToRoute('app_login');
}

}
