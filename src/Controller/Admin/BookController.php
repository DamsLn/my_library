<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book', name: 'app_book_')]
class BookController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route('/list', name: 'admin_list', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $bookForm = $this->createForm('App\Form\BookType', new Book());

        $bookForm->handleRequest($request);
        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            $newBook = $bookForm->getData();

            $this->em->persist($newBook);
            $this->em->flush();
        }

        $books = $this->em->getRepository(Book::class)->findAll();
        $tableHeaderCells = [
            'id' => '#',
            'title' => 'Titre',
            'description' => 'Description',
            'author' =>'Auteur',
            'createdAt' => 'Date de création',
            'updatedAt' => 'Date de dernière mise à jour',
            'actions' => 'Actions',
        ];

        return $this->render('book/admin/list.html.twig', [
            'books' => $books,
            'tableHeaderCells' => $tableHeaderCells,
            'bookForm' => $bookForm->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'admin_update', methods: ['GET', 'PUT'])]
    public function update(int $id, Request $request): Response
    {
        $book = $this->em->getRepository(Book::class)->find($id);

        dd($book);
    }

    #[Route('/remove/{id}', name: 'admin_remove', methods: ['DELETE'])]
    public function remove(int $id): Response
    {
        $book = $this->em->getRepository(Book::class)->find($id);

        $this->em->remove($book);
        $this->em->flush();

        return $this->redirectToRoute('app_book_admin_list');
    }
}
