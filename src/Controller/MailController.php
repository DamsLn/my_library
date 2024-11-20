<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailController extends AbstractController
{
    private const string EMAIL = 'contact@mylibrary.com';

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contactForm = $this->createForm(ContactType::class, new ContactDTO());

        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contactInfo = $contactForm->getData();

            $email = (new Email())
                ->from(self::EMAIL)
                ->to($contactInfo->getEmail())
                ->subject($contactInfo->getSubject())
                ->text($contactInfo->getMessage());

            $mailer->send($email);

            $contactForm = $this->createForm(ContactType::class, new ContactDTO());
        }

        return $this->render('mail/index.html.twig', [
            'contactForm' => $contactForm->createView(),
        ]);
    }
}
