<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/author', name: 'app_author_admin_')]
#[IsGranted('ROLE_ADMIN')]
class AuthorController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route('/list', name: 'list')]
    public function list(Request $request): Response
    {
        $authors = $this->em->getRepository(Author::class)->findAll();
        $authorForm = $this->createForm(AuthorType::class);

        $authorForm->handleRequest($request);
        if ($authorForm->isSubmitted() && $authorForm->isValid()) {
            $author = $authorForm->getData();

            $this->em->persist($author);
            $this->em->flush();

            $this->addFlash('success', 'L\'auteur/autrice a bien été créé·e');

            return $this->redirectToRoute('app_author_admin_list');
        }

        $tableHeaderCells = [
            'id' => 'ID',
            'fullName' => 'Nom complet',
            'actions' => 'Actions',
        ];

        return $this->render('author/admin/list.html.twig', [
            'authors' => $authors,
            'tableHeaderCells' => $tableHeaderCells,
            'authorForm' => $authorForm->createView(),
        ]);
    }

    #[Route('/{id}/remove', name: 'remove')]
    public function remove(int $id): Response
    {
        $author = $this->em->getRepository(Author::class)->find($id);

        $this->em->remove($author);
        $this->em->flush();

        $this->addFlash('success', 'L\'auteur/autrice a bien été supprimé·e');

        return $this->redirectToRoute('app_author_admin_list');
    }
}
