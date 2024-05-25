<?php

namespace App\Controller;

use App\Entity\RDV;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RDVCRUD extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/rdvs', name: 'rdv_index', methods: ['GET'])]
    public function index(): Response
    {
        $rdvs = $this->entityManager->getRepository(RDV::class)->findAll();

        return $this->render('rdv/index.html.twig', [
            'rdvs' => $rdvs,
        ]);
    }

    #[Route('/rdv/create', name: 'rdv_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('rdv/create.html.twig');
    }

    #[Route('/rdv/store', name: 'rdv_store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $rdv = new RDV();
        $rdv->setDateRdv(new \DateTime($request->request->get('date_rdv')));

        // You would set other properties similarly, depending on your form

        // Persist the entity
        $this->entityManager->persist($rdv);
        $this->entityManager->flush();

        return $this->redirectToRoute('rdv_index');
    }

    #[Route('/rdv/edit/{id}', name: 'rdv_edit', methods: ['GET'])]
    public function edit(int $id): Response
    {
        $rdv = $this->entityManager->getRepository(RDV::class)->find($id);

        if (!$rdv) {
            return $this->json(['message' => 'RDV not found'], 404);
        }

        return $this->render('rdv/edit.html.twig', [
            'rdv' => $rdv,
        ]);
    }

    #[Route('/rdv/update/{id}', name: 'rdv_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, int $id): Response
    {
        $rdv = $this->entityManager->getRepository(RDV::class)->find($id);

        if (!$rdv) {
            return $this->json(['message' => 'RDV not found'], 404);
        }

        $rdv->setDateRdv(new \DateTime($request->request->get('date_rdv')));
        $rdv->setPrescription($request->request->get('prescription_rdv'));

        // Update other properties similarly, depending on your form

        $this->entityManager->flush();

        return $this->redirectToRoute('rdv_index');
    }

    #[Route('/rdv/delete/{id}', name: 'rdv_delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $rdv = $this->entityManager->getRepository(RDV::class)->find($id);

        if (!$rdv) {
            return $this->json(['message' => 'RDV not found'], 404);
        }

        $this->entityManager->remove($rdv);
        $this->entityManager->flush();

        return $this->redirectToRoute('rdv_index');
    }
}
