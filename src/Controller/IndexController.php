<?php

namespace App\Controller;

use App\Entity\CatalogItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $items = $entityManager->getRepository(CatalogItem::class)->findAll();

        return $this->render('index/index.html.twig', [
            'items' => $items,
            'controller_name' => 'IndexController',
        ]);
    }
}
