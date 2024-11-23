<?php

namespace App\Controller\Ajax;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ajax', name: 'ajax_')]
class BookAjaxController extends AbstractController 
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route('/book/{id}/show', name: 'book_show', methods: ['GET'])]
    public function showBook(int $id): Response
    {
        $book = $this->em->getRepository(Book::class)->find($id);

        $htmlBookInfo = $this->render('book/admin/show.html.twig', [
            'book' => $book
        ])->getContent();

        return $this->json($htmlBookInfo, 200, [], ['Content-Type' => 'application/json']);
    }

    #[Route('/book/{id}/edit', name: 'book_edit', methods: ['GET', 'POST'])]
    public function editBook(int $id, Request $request): Response
    {
        $book = $this->em->getRepository(Book::class)->find($id);

        $bookForm = $this->createForm(BookType::class, $book, [
            'action' => $this->generateUrl('ajax_book_edit', ['id' => $id]),
        ]);

        $bookForm->handleRequest($request);

        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Le livre a bien été mis à jour');

            return $this->redirectToRoute('app_book_admin_list');
        }

        $htmlForm = $this->render('book/admin/update.html.twig', [
            'book' => $book,
            'bookForm' => $bookForm->createView(),
        ])->getContent();

        return $this->json($htmlForm, 200, [], ['Content-Type' => 'text/html']);
    }
}