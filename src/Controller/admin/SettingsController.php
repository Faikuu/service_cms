<?php

namespace App\Controller\admin;

use App\Entity\Options;
use App\Form\GeneralSettingsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/settings')]
class SettingsController extends AbstractController
{
    #[Route('/', name: 'app_adminsettings')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $options = $entityManager->getRepository(Options::class)->findAll();
    
        $data = [];
        foreach ($options as $option) {
            $data[$option->getOptionName()] = $option->getOptionValue();
        }
    
        $form = $this->createForm(GeneralSettingsFormType::class, $data);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData() as $key => $value) {
                $this->upsertOption($entityManager, $key, $value);
            }
    
            $this->addFlash('success', 'Settings saved');
    
            return $this->redirectToRoute('app_adminsettings');
        }
    
        return $this->render('admin/settings/index.html.twig', [
            'form' => $form->createView(),
            'options' => $options,
        ]);
    }

    private function upsertOption($entityManager, $key, $value)
    {
        $option = $entityManager->getRepository(Options::class)->findOneBy(['option_name' => $key]);
        if (!$option) {
            $option = new Options();
            $option->setOptionName($key);
        }
        $option->setOptionValue($value);
        $entityManager->persist($option);
        $entityManager->flush();
    }
}
