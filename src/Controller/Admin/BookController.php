<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/book/admin', name: 'app_book_admin_')]
#[IsGranted('ROLE_ADMIN')]
class BookController extends AbstractController
{
    private const int LIMIT = 2;

    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route('/list', name: 'list', methods: ['GET', 'POST'])]
    public function adminList(
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/covers')] string $coversDirectory
    ): Response
    {
        $bookRepository = $this->em->getRepository(Book::class);
        
        $page = $request->query->getInt('page', 1);
        
        $books = $bookRepository->findAllAvailablePaginate(
            $page,
            self::LIMIT
        );
        $maxPages = ceil($books->count() / self::LIMIT);

        $bookForm = $this->createForm(BookType::class, new Book());

        $bookForm->handleRequest($request);
        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            $newBook = $bookForm->getData();

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

                $newBook->setCoverFilename($newFilename);
            }

            $this->em->persist($newBook);
            $this->em->flush();

            $this->addFlash('success', 'Le livre a bien été ajouté');

            return $this->redirectToRoute('app_book_admin_list');
        }

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
            'pageNumber' => $page,
            'maxPages' => $maxPages,
        ]);
    }

    #[Route('/{id}/remove', name: 'remove', methods: ['DELETE'])]
    public function remove(
        int $id,
        #[Autowire('%kernel.project_dir%/public/uploads/covers')] string $coversDirectory, 
        Filesystem $filesystem
    ): Response
    {
        $book = $this->em->getRepository(Book::class)->find($id);

        if (!is_null($book->getCoverFilename())) {
            $filesystem->remove($coversDirectory.'/'.$book->getCoverFilename());
        }

        $this->em->remove($book);
        $this->em->flush();

        $this->addFlash('success', 'Le livre a bien été supprimé');

        return $this->redirectToRoute('app_book_admin_list');
    }
}
