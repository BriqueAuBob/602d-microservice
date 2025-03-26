<?php

namespace App\Controller;

use App\Service\MailerService;
use App\Utils\RequestChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MailerController extends AbstractController
{

    public function __construct(
        private readonly MailerService $mailerService,
        private readonly RequestChecker $requestChecker
    ) {
    }

    #[Route('/send-mail', name: 'send_mail', methods: [Request::METHOD_POST])]
    public function index(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['to'];
        $checkEmailResponse = $this->requestChecker->checkEmail($email);
        if ($checkEmailResponse) {
            return $checkEmailResponse;
        }

        $subject = $data['subject'];
        $message = $data['message'];

        $this->mailerService->sendEmail($email, $subject, $message);

        return new Response('Email envoyé avec succès !');
    }
}
