<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserInterfaceController extends AbstractController
{
    public function renderNavbar(): Response
    {
        $navUrls = [];
        // get app_login route
        $navUrls['login'] = $this->generateUrl('app_login');
        $navUrls['logout'] = $this->generateUrl('app_logout');
        if ($this->isGranted('ROLE_ADMIN')) {
            $navUrls['admin'] = $this->generateUrl('app_adminindex_index');
        }
        $isLoggedIn = $this->getUser();
        return $this->render('ui/navbar.html.twig', [
            'nav_urls' => $navUrls,
            'is_logged_in' => $isLoggedIn
        ]);
    }

    public function renderFooter(): Response
    {
        return $this->render('ui/footer.html.twig', [
            'controller_name' => 'NavbarController',
        ]);
    }

    public function renderSidebar(): Response
    {
        return $this->render('ui/sidebar.html.twig', [
            'controller_name' => 'NavbarController',
        ]);
    }
}
