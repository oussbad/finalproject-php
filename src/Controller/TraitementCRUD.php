<?php 

namespace App\Controller;

use App\Entity\Traitements;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TraitementCRUD extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/traitements', name: 'traitements_index', methods: ['GET'])]
    public function index(): Response
    {
        $traitements = $this->entityManager->getRepository(Traitements::class)->findAll();

        return $this->render('traitements/index.html.twig', [
            'traitements' => $traitements,
        ]);
    }

    #[Route('/traitement/create', name: 'traitements_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('traitements/create.html.twig');
    }

    #[Route('/traitement/store', name: 'traitements_store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $traitement = new Traitements();
        $traitement->setDescription($request->request->get('description'));

        // Persist the entity
        $this->entityManager->persist($traitement);
        $this->entityManager->flush();

        return $this->redirectToRoute('traitements_index');
    }
    

    #[Route('/traitement/edit/{id}', name: 'traitements_edit', methods: ['GET'])]
    public function edit(int $id): Response
    {
        $traitement = $this->entityManager->getRepository(Traitements::class)->find($id);

        if (!$traitement) {
            return $this->json(['message' => 'Traitement not found'], 404);
        }

        return $this->render('traitements/edit.html.twig', [
            'traitement' => $traitement,
        ]);
    }

    #[Route('/traitement/update/{id}', name: 'traitements_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, int $id): Response
    {
        $traitement = $this->entityManager->getRepository(Traitements::class)->find($id);

        if (!$traitement) {
            return $this->json(['message' => 'Traitement not found'], 404);
        }

        $traitement->setDescription($request->request->get('description'));

        $this->entityManager->flush();

        return $this->redirectToRoute('traitements_index');
    }

    #[Route('/traitement/delete/{id}', name: 'traitements_delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        $traitement = $this->entityManager->getRepository(Traitements::class)->find($id);

        if (!$traitement) {
            return $this->json(['message' => 'Traitement not found'], 404);
        }

        $this->entityManager->remove($traitement);
        $this->entityManager->flush();

        return $this->redirectToRoute('traitements_index');
    }
}
