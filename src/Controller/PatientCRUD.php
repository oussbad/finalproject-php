<?php
// src/Controller/PatientCRUDController.php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class PatientCRUD extends AbstractController
{
    private $entityManager;
    private $userPasswordHasher;

    public function __construct(EntityManagerInterface $entityManager , UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('/patients', name: 'patient_index', methods: ['GET'])]
    public function index(): Response
    {
        $patients = $this->entityManager->getRepository(Patient::class)->findAll();

        return $this->render('patient/index.html.twig', [
            'patients' => $patients,
        ]);
    }

    #[Route('/patient/create', name: 'patient_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('patient/create.html.twig');
    }

    #[Route('/patient/store', name: 'patient_store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $patient = new Patient();
        $patient->setNom($request->request->get('nom'));
        $patient->setPrenom($request->request->get('prenom'));
        $patient->setCin($request->request->get('cin'));

        $user = new User();
        $user->setEmail($request->request->get('email'));
        $user->setRoles(['ROLE_USER']);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $request->request->get('password')
            )
        );

        $user->setPatient($patient);

        // Persist the entities
        $this->entityManager->persist($patient);
        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return $this->redirectToRoute('admin');
    }
    #[Route('/patient/edit/{id}', name: 'patient_edit', methods: ['GET'])]
    public function edit(int $id): Response
    {
        $patient = $this->entityManager->getRepository(Patient::class)->find($id);

        if (!$patient) {
            return $this->json(['message' => 'Patient not found'], 404);
        }

        return $this->render('patient/edit.html.twig', [
            'patient' => $patient,
        ]);
    }

    #[Route('/patient/update/{id}', name: 'patient_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, int $id): Response
    {
        $patient = $this->entityManager->getRepository(Patient::class)->find($id);

        if (!$patient) {
            return $this->json(['message' => 'Patient not found'], 404);
        }

        $patient->setNom($request->request->get('nom'));
        $patient->setPrenom($request->request->get('prenom'));
        $patient->setCin($request->request->get('cin'));

        $this->entityManager->flush();

        return $this->redirectToRoute('admin');
    }

     #[Route('/patient/GenerateRapport/{id}', name: 'patient_rapport', methods: ['POST', 'PUT','GET'])]
    public function GenerateRapport(int $id): Response
    {
        $patient = $this->entityManager->getRepository(Patient::class)->find($id);

        if (!$patient) {
            return $this->json(['message' => 'Patient not found'], 404);
        }

        
    }

    #[Route('/patient/delete/{id}', name: 'patient_delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $patient = $this->entityManager->getRepository(Patient::class)->find($id);

        if (!$patient) {
            return $this->json(['message' => 'Patient not found'], 404);
        }

        $this->entityManager->remove($patient);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin');
    }

    #[Route('/patient/{id}', name: 'patient_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $patient = $this->entityManager->getRepository(Patient::class)->find($id);

        if (!$patient) {
            throw $this->createNotFoundException('The patient does not exist');
        }

        return $this->render('patient/show.html.twig', [
            'patient' => $patient,
        ]);
    }

    #[Route('/rapports', name: 'patient_rapport', methods: ['GET'])]
    public function rapport(): Response
    {
        $patients = $this->entityManager->getRepository(Patient::class)->findAll();

        return $this->render('patient/rapport.html.twig', [
            'patients' => $patients,
        ]);
    }
}
