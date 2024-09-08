<?php
namespace App\Controller\admin;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserManagementController extends AbstractController
{
    #[Route('admin/users', name: 'app_admin_usermanagement_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        // var_dump($users);

        return $this->render('admin/usermanagement/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('admin/users/create', name: 'app_admin_usermanagement_create')]
    public function create(): Response
    {
        return $this->render('admin/usermanagement/create.html.twig', [
            'controller_name' => 'UserManagementController',
        ]);
    }

    #[Route('admin/users/{id}/edit', name: 'app_admin_usermanagement_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
    
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            $this->addFlash('success', 'User has been updated successfully');
    
            return $this->redirectToRoute('app_admin_usermanagement_index');
        }
    
        return $this->render('admin/usermanagement/edit.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'UserManagementController',
        ]);
    }

    #[Route('admin/users/{id}/delete', name: 'app_admin_usermanagement_delete')]
    public function delete(): Response
    {
        return $this->render('admin/usermanagement/delete.html.twig', [
            'controller_name' => 'UserManagementController',
        ]);
    }
}
