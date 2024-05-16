<?php

namespace App\Controller;

use App\Entity\RDV;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class admin extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    

    #[Route('/admin', name: 'admin', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('admin/admin.html.twig');
    }

    

   
}
