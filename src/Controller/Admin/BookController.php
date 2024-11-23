<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book/admin', name: 'app_book_admin_')]
class BookController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route('/list', name: 'list', methods: ['GET', 'POST'])]
    public function adminList(Request $request): Response
    {
        $bookForm = $this->createForm(BookType::class, new Book());

        $bookForm->handleRequest($request);
        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            $newBook = $bookForm->getData();

            $this->em->persist($newBook);
            $this->em->flush();

            $this->addFlash('success', 'Le livre a bien été ajouté');

            return $this->redirectToRoute('app_book_admin_list');
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

    #[Route('/{id}/remove', name: 'remove', methods: ['DELETE'])]
    public function remove(int $id): Response
    {
        $book = $this->em->getRepository(Book::class)->find($id);

        $this->em->remove($book);
        $this->em->flush();

        $this->addFlash('success', 'Le livre a bien été supprimé');

        return $this->redirectToRoute('app_book_admin_list');
    }
}
