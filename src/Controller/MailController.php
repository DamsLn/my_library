<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Event\ContactRequestEvent;
use App\Form\ContactType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailController extends AbstractController
{
    private const string EMAIL = 'contact@mylibrary.com';

    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request, 
        MailerInterface $mailer,
        EventDispatcherInterface $eventDispatcher): Response
    {
        $data = new ContactDTO();
        $contactForm = $this->createForm(ContactType::class, $data);

        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contactInfo = $contactForm->getData();

            $email = (new Email())
                ->from(self::EMAIL)
                ->to($contactInfo->getEmail())
                ->subject($contactInfo->getSubject())
                ->text($contactInfo->getMessage());

            try {
                $mailer->send($email);

                $eventDispatcher->dispatch(new ContactRequestEvent($data));
                $this->addFlash('success', 'Votre mail a bien été envoyé !');
            } catch (Exception $e) {
                $this->addFlash('error', 'Impossible d\'envoyer vote mail...');
            }

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('mail/index.html.twig', [
            'contactForm' => $contactForm->createView(),
        ]);
    }
}
