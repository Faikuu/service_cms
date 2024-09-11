<?php
namespace App\Controller\admin;

use App\Entity\CatalogItem;
use App\Form\CatalogItemEditFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogItemManagementController extends AbstractController
{
    #[Route('admin/catalog', name: 'app_admin_catalogitemmanagement_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $items = $entityManager->getRepository(CatalogItem::class)->findAll();

        return $this->render('admin/catalogitemmanagement/index.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route('admin/catalog/create', name: 'app_admin_catalogitemmanagement_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $item = new CatalogItem();
        $form = $this->createForm(CatalogItemEditFormType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_catalogitemmanagement_index');
        }

        return $this->render('admin/catalogitemmanagement/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('admin/catalog/{id}/edit', name: 'app_admin_catalogitemmanagement_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $item = $entityManager->getRepository(CatalogItem::class)->find($id);
    
        if (!$item) {
            throw $this->createNotFoundException('Catalog item not found.');
        }
    
        $form = $this->createForm(CatalogItemEditFormType::class, $item);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            $this->addFlash('success', 'Catalog item has been updated successfully');
    
            return $this->redirectToRoute('app_admin_catalogitemmanagement_index');
        }
    
        return $this->render('admin/catalogitemmanagement/edit.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'CatalogItemManagementController',
        ]);
    }

    #[Route('admin/catalog/{id}/delete', name: 'app_admin_catalogitemmanagement_delete', methods: ['GET'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $item = $entityManager->getRepository(CatalogItem::class)->find($id);
    
        if (!$item) {
            throw $this->createNotFoundException('Catalog item not found.');
        }
    
        $entityManager->remove($item);
        $entityManager->flush();
    
        $this->addFlash('success', 'Catalog item has been deleted successfully');
    
        return $this->redirectToRoute('app_admin_catalogitemmanagement_index');
    }
}

