<?php

namespace App\Controller\admin;

use App\Entity\Options;
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

    private function upsertOption($entityManager, $key, $value)
    {
        $option = $entityManager->getRepository(Options::class)->findOneBy(['key' => $key]);
        if (!$option) {
            $option = new Options();
            $option->setOptionName($key);
        }
        $option->setOptionValue($value);
        $entityManager->persist($option);
        $entityManager->flush();
    }
}
