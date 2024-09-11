<?php
namespace App\Controller\admin;

use App\Entity\CatalogItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogManagementController extends AbstractController
{
    #[Route('admin/catalog', name: 'app_admin_catalogmanagement_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $items = $entityManager->getRepository(CatalogItem::class)->findAll();

        return $this->render('admin/catalogmanagement/index.html.twig', [
            'items' => $items,
        ]);
    }
}
