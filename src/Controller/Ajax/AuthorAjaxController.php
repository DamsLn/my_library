<?php

namespace App\Controller\Ajax;

use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ajax', name: 'ajax_')]
class AuthorAjaxController extends AbstractController 
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route('/author/{id}/show', name: 'author_show', methods: ['GET'])]
    public function showAuthor(int $id): Response
    {
        $author = $this->em->getRepository(Author::class)->find($id);

        $htmlAuthorInfo = $this->render('author/admin/show.html.twig', [
            'author' => $author
        ])->getContent();

        return $this->json($htmlAuthorInfo, 200, [], ['Content-Type' => 'application/json']);
    }

    #[Route('/admin/author/{id}/edit', name: 'author_edit', methods: ['GET', 'POST'])]
    public function editAuthor(int $id, Request $request): Response
    {
        $author = $this->em->getRepository(Author::class)->find($id);

        $authorForm = $this->createForm(AuthorType::class, $author, [
            'action' => $this->generateUrl('ajax_author_edit', ['id' => $id]),
        ]);

        $authorForm->handleRequest($request);

        if ($authorForm->isSubmitted() && $authorForm->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Les informations de l\'auteur/autrice ont bien été mises à jour');

            return $this->redirectToRoute('app_author_admin_list');
        }

        $htmlForm = $this->render('author/admin/update.html.twig', [
            'author' => $author,
            'authorForm' => $authorForm->createView(),
        ])->getContent();

        return $this->json($htmlForm, 200, [], ['Content-Type' => 'text/html']);
    }
}