<?php

namespace App\Controller\Ajax;

use App\Entity\Book;
use App\Form\BookType;
use App\Security\Voter\BookVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/ajax', name: 'ajax_')]
#[IsGranted('ROLE_ADMIN')]
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

    #[Route('/admin/book/{id}/edit', name: 'book_edit', methods: ['GET', 'POST'])]
    #[IsGranted(BookVoter::EDIT, 'book')]
    public function editBook(
        Book $book, 
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/covers')] string $coversDirectory
    ): Response
    {
        $bookForm = $this->createForm(BookType::class, $book, [
            'action' => $this->generateUrl('ajax_book_edit', ['id' => $book->getId()]),
        ]);

        $bookForm->handleRequest($request);
        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            $coverFile = $bookForm->get('cover')->getData();

            if ($coverFile) {
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$coverFile->guessExtension();

                try {
                    $coverFile->move($coversDirectory, $newFilename);
                } catch (FileException $e) {
                    dd($e->getMessage());
                }

                $book->setCoverFilename($newFilename);
            }

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