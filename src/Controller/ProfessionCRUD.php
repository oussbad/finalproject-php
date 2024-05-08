<?php

namespace App\Controller;

use App\Entity\Profession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfessionCRUD extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/professions', name: 'profession_index', methods: ['GET'])]
    public function index(): Response
    {
        $professions = $this->entityManager->getRepository(Profession::class)->findAll();

        return $this->render('profession/index.html.twig', [
            'professions' => $professions,
        ]);
    }

    #[Route('/profession/create', name: 'profession_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('profession/create.html.twig');
    }

    #[Route('/profession/store', name: 'profession_store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $profession = new Profession();
        $profession->setNPE($request->request->get('npe'));
        $profession->setNom($request->request->get('nom'));
        $profession->setPrenom($request->request->get('prenom'));

        // Persist the entity
        $this->entityManager->persist($profession);
        $this->entityManager->flush();

        return $this->redirectToRoute('profession_index');
    }

    #[Route('/profession/edit/{id}', name: 'profession_edit', methods: ['GET'])]
    public function edit(int $id): Response
    {
        $profession = $this->entityManager->getRepository(Profession::class)->find($id);

        if (!$profession) {
            return $this->json(['message' => 'Profession not found'], 404);
        }

        return $this->render('profession/edit.html.twig', [
            'profession' => $profession,
        ]);
    }

    #[Route('/profession/update/{id}', name: 'profession_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, int $id): Response
    {
        $profession = $this->entityManager->getRepository(Profession::class)->find($id);

        if (!$profession) {
            return $this->json(['message' => 'Profession not found'], 404);
        }

        $profession->setNPE($request->request->get('npe'));
        $profession->setNom($request->request->get('nom'));
        $profession->setPrenom($request->request->get('prenom'));

        $this->entityManager->flush();

        return $this->redirectToRoute('profession_index');
    }

    #[Route('/profession/delete/{id}', name: 'profession_delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $profession = $this->entityManager->getRepository(Profession::class)->find($id);

        if (!$profession) {
            return $this->json(['message' => 'Profession not found'], 404);
        }

        $this->entityManager->remove($profession);
        $this->entityManager->flush();

        return $this->redirectToRoute('profession_index');
    }
}
