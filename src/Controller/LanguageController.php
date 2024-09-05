<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LanguageController extends AbstractController
{
    #[Route("/switch-language", name: "switch_language", methods: ["POST"])]
    public function switchLanguage(Request $request, SessionInterface $session, CsrfTokenManagerInterface $csrfTokenManager): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('switch_language', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $lang = $request->request->get('lang', 'en');

        $session->set('_locale', $lang);

        return $this->redirect($request->headers->get('referer'));
    }
}