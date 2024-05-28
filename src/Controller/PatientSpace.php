<?php
// src/Controller/PatientSpace.php

namespace App\Controller;

use App\Entity\RDV;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

    #[Route('/rdv/new', name: 'rdv_new', methods: ['GET', 'POST'])]
    public function createRdv(Request $request, UserInterface $user): Response
    {
        $patient = $user->getPatient();
        if (!$patient) {
            throw $this->createNotFoundException('No patient found for the current user');
        }

        if ($request->isMethod('POST')) {
            $dateRdv = $request->request->get('dateRdv');
            

            if ($dateRdv) {
                $rdv = new RDV();
                $rdv->setDateRdv(new \DateTime($dateRdv));
                
                $rdv->setPatient($patient);

                $this->entityManager->persist($rdv);
                $this->entityManager->flush();

                return $this->redirectToRoute('PatientSpace'); // Assuming you have a route to list RDVs
            }
        }

        return $this->render('admin/rdv.html.twig');
    }
    #[Route('/rdv/list', name: 'rdv_list', methods: ['GET'])]
    public function listRdvs(UserInterface $user): Response
    {
        $patient = $user->getPatient();
        if (!$patient) {
            throw $this->createNotFoundException('No patient found for the current user');
        }

        $rdvs = $this->entityManager->getRepository(RDV::class)->findBy(['patient' => $patient]);

        return $this->render('admin/rdvlist.html.twig', [
            'rdvs' => $rdvs,
        ]);
    }
    #[Route('/myrdv/edit/{id}', name: 'rdv_edit', methods: ['GET'])]
    public function edit(int $id): Response
    {
        $rdv = $this->entityManager->getRepository(RDV::class)->find($id);

        if (!$rdv) {
            return $this->json(['message' => 'RDV not found'], 404);
        }

        return $this->render('admin/editRdv.html.twig', [
            'rdv' => $rdv,
        ]);
    }

    #[Route('/myrdv/update/{id}', name: 'myrdv_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, int $id): Response
    {
        $rdv = $this->entityManager->getRepository(RDV::class)->find($id);

        if (!$rdv) {
            return $this->json(['message' => 'RDV not found'], 404);
        }

        $rdv->setDateRdv(new \DateTime($request->request->get('date_rdv')));
       
        // Update other properties similarly, depending on your form

        $this->entityManager->flush();

        return $this->redirectToRoute('PatientSpace');
    }
    #[Route('/rdv/delete/{id}', name: 'myrdv_delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $rdv = $this->entityManager->getRepository(RDV::class)->find($id);

        if (!$rdv) {
            return $this->json(['message' => 'RDV not found'], 404);
        }

        $this->entityManager->remove($rdv);
        $this->entityManager->flush();

        return $this->redirectToRoute('PatientSpace');
    }

   
}
