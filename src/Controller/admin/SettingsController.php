<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/settings')]
class SettingsController extends AbstractController
{
    #[Route('/', name: 'app_adminsettings')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
