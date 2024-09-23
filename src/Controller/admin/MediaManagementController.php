<?php
namespace App\Controller\admin;

use App\Entity\CatalogItem;
use App\Entity\Media;
use App\Form\CatalogItemEditFormType;
use App\Form\MediaUploadFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/media')]
class MediaManagementController extends AbstractController
{
    #[Route('/', name: 'app_admin_mediamanagement_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $items = $entityManager->getRepository(Media::class)->findAll();

        return $this->render('admin/mediamanagement/index.html.twig', [
            'items' => $items,
        ]);
    }

    #[Route('/create', name: 'app_admin_mediamanagement_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/img')] string $mediaDirectory): Response
    {
        $item = new Media();
        $form = $this->createForm(MediaUploadFormType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('filename')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move($mediaDirectory, $newFilename);
                } catch (FileException $e) {
                }
                $item->setFilename('/uploads/img/'.$newFilename);
            }

            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_mediamanagement_index');
        }

        return $this->render('admin/mediamanagement/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_mediamanagement_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/img')] string $mediaDirectory): Response
    {
        $item = $entityManager->getRepository(Media::class)->find($id);
        $item->setFilename("");
        $form = $this->createForm(MediaUploadFormType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('filename')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move($mediaDirectory, $newFilename);
                } catch (FileException $e) {
                }
                $item->setFilename('/uploads/img/'.$newFilename);
            }

            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_mediamanagement_index');
        }

        return $this->render('admin/mediamanagement/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_mediamanagement_delete', methods: ['GET'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $item = $entityManager->getRepository(Media::class)->find($id);
    
        if (!$item) {
            throw $this->createNotFoundException('Media not found.');
        }
    
        $entityManager->remove($item);
        $entityManager->flush();
    
        $this->addFlash('success', 'Media has been deleted successfully');
    
        return $this->redirectToRoute('app_admin_mediamanagement_index');
    }
}

