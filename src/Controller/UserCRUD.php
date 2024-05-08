<?php
// src/Controller/UserController.php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserCRUD extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/users', name: 'user_index', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/create', name: 'user_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('user/create.html.twig');
    }

    #[Route('/user/store', name: 'user_store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $user = new User();
        $user->setEmail($request->request->get('email'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($request->request->get('password'));

        // Persist the user entity
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('user_index');
    }

    #[Route('/user/edit/{id}', name: 'user_edit', methods: ['GET'])]
    public function edit(int $id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/update/{id}', name: 'user_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, int $id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        $user->setEmail($request->request->get('email'));
        // Update other fields as needed

        $this->entityManager->flush();

        return $this->redirectToRoute('user_index');
    }

    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('user_index');
    }
}
