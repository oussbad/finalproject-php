<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profession;
use App\Entity\Patient;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $nom=$form->get('nom')->getData();
        $prenom=$form->get('prenom')->getData();
        $cin=$form->get('cin')->getData();
        $INPE=$form->get('inpe')->getData();
        $userType=$form->get('userType')->getData();




        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            if ($userType === 'patient') {
                $user->setRoles(['ROLE_USER']);
                $patient = new Patient();
                $patient->setNom($nom);
                $patient->setPrenom($prenom);
                $patient->setCin($cin);
                $user->setPatient($patient);
            } elseif ($userType === 'professional') {
                $user->setRoles(['ROLE_PRO']);
                $profession = new Profession();
                $profession->setNPE($INPE);
                $profession->setNom($nom);
                $profession->setPrenom($prenom);
                $user->setProfession($profession);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            
            if ($userType === 'patient') {

                return $this->redirectToRoute('PatientSpace');

            } elseif ($userType === 'professional') {

                return $this->redirectToRoute('admin');

            }

            // do anything else you need here, like send an email

            
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
