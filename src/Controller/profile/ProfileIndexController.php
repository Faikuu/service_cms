<?php

namespace App\Controller\profile;

use App\Entity\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProfileIndexController extends AbstractController
{
    #[Route('/profile')]
    public function index(EntityManagerInterface $em): Response
    {
        $sessionRepo = $em->getRepository(Session::class);
        $dbSessions = $sessionRepo->findAll();

        $sessions = [];

        foreach ($dbSessions as $session) {
            $typeLabel = 'Personal Training'; // Default or derived from logic
            $typeClass = 'bg-green-100 text-green-800';

            // Example logic to determine status class
            $statusLabel = $session->getStatusLabel();
            $statusClass = match (strtolower($statusLabel)) {
                'confirmed' => 'bg-blue-100 text-blue-800',
                'pending' => 'bg-yellow-100 text-yellow-800',
                'cancelled' => 'bg-red-100 text-red-800',
                'no show' => 'bg-gray-100 text-gray-800',
                default => 'bg-gray-200 text-gray-900',
            };

            $sessions[] = [
                'initials' => $session->getInitials(),
                'name' => $session->getName(),
                'email' => $session->getEmail(),
                'date' => $session->getDate()->format('Y-m-d'),
                'typeLabel' => $typeLabel,
                'typeClass' => $typeClass,
                'duration' => $session->getDuration(),
                'statusLabel' => $statusLabel,
                'statusClass' => $statusClass,
            ];
        }

        return $this->render('profile/index.html.twig', [
            'sessions' => $sessions,
            'controller_name' => 'ProfileIndexController',
        ]);
    }
}
