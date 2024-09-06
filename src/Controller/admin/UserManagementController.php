<?php
namespace App\Controller\admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('admin/users/{id}/edit', name: 'app_admin_usermanagement_edit')]
    public function edit(): Response
    {
        return $this->render('admin/usermanagement/edit.html.twig', [
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
