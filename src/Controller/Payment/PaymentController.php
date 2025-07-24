<?php
namespace App\Controller;

use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/create-checkout-session', name: 'create_checkout_session')]
    public function createCheckoutSession(StripeService $stripeService): JsonResponse
    {
        $session = $stripeService->createCheckoutSession(
            [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Example Product',
                    ],
                    'unit_amount' => 5000, // in cents
                ],
                'quantity' => 1,
            ]],
            $this->generateUrl('payment_success', [], 0),
            $this->generateUrl('payment_cancel', [], 0)
        );

        return $this->json(['id' => $session->id]);
    }
}