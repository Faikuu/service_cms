<?php
namespace App\Controller\admin;

use App\Entity\User;
use App\Form\UserEditFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/users')]
class UserManagementController extends AbstractController
{
    #[Route('/', name: 'app_admin_usermanagement_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        // $this->addFlash('success', 'Test TestTestTest TestTest Test');

        return $this->render('admin/usermanagement/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/create', name: 'app_admin_usermanagement_create', methods: ['GET', 'POST'])]
    public function create(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();

            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_usermanagement_index');
        }

        return $this->render('admin/usermanagement/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_usermanagement_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
    
        $form = $this->createForm(UserEditFormType::class, $user);
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

    #[Route('/{id}/delete', name: 'app_admin_usermanagement_delete', methods: ['GET'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
    
        $entityManager->remove($user);
        $entityManager->flush();
    
        $this->addFlash('success', 'User has been deleted successfully');
    
        return $this->redirectToRoute('app_admin_usermanagement_index');
    }
}
