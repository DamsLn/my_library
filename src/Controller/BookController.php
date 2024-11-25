<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book', name: 'app_book_')]
class BookController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $books = $this->em->getRepository(Book::class)->findAllAvailable();
        
        return $this->render('book/list.html.twig', [
            'books' => $books,
        ]);
    }
}
