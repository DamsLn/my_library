<?php

namespace App\Controller\Admin;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book', name: 'app_book_')]
class BookController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        $tableHeaderCells = [
            'id' => '#',
            'title' => 'Titre',
            'description' => 'Description',
            'author' =>'Auteur',
            'createdAt' => 'Date de création',
            'updatedAt' => 'Date de dernière mise à jour',
        ];

        return $this->render('book/admin/list.html.twig', [
            'books' => $books,
            'tableHeaderCells' => $tableHeaderCells,
        ]);
    }
}
