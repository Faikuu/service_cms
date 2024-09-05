<?php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleListener
{
    private $defaultLocale;
    private $requestStack;

    public function __construct(RequestStack $requestStack, string $defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
        $this->requestStack = $requestStack;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
            return;
        }

        // Get the current session from the request stack
        $session = $this->requestStack->getSession();

        // Try to see if the locale has been set as a session variable
        $locale = $session->get('_locale', $this->defaultLocale);
        $request->setLocale($locale);
    }
}
