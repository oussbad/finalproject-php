<?php

namespace App\Controller;

use App\Entity\RDV;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;


class PatientSpace extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    

    #[Route('/PatientSpace', name: 'PatientSpace', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('admin/patientadmin.html.twig');
    }

    #[Route('/profile', name: 'profile', methods: ['GET'])]
    public function profile(UserInterface $user): Response
    {
        

        return $this->render('admin/profile.html.twig', [
            'user' => $user,
        ]);
    }

   
}
