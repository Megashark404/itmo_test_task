<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'author_index')]
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/create', name: 'author_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $author = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'Автор добавлен');

            return $this->redirect('/author/' . $author->getId());
        }

        return $this->render('author/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/author/edit/{id}', name: 'author_update')]
    public function edit(int $id, Request $request, AuthorRepository $authorRepository, ManagerRegistry $doctrine, FileUploader$fileUploader): Response
    {
        $author = $authorRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'Автор обновлен');

            return $this->redirect('/author/' . $author->getId());
        }

        return $this->render('author/edit.html.twig', [
            'form' => $form,
            'author' => $author,
        ]);
    }


    #[Route('/author/{id}', name: 'author_show')]
    public function show(int $id, authorRepository $authorRepository): Response
    {
        $author = $authorRepository->find($id);

        if (!$author) {
            throw $this->createNotFoundException(
                'Нет автора с таким id: '.$id
            );
        }

        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/author/delete/{id}', name: 'author_delete')]
    public function delete(int $id, authorRepository $authorRepository, ManagerRegistry $doctrine): Response
    {
        $author = $authorRepository->find($id);
        if ($author) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($author);
            $entityManager->flush();
            $this->addFlash('success', 'Автор удален');

        }
        else {
            $this->addFlash('success', 'Ошибка. Нет такого автора');
        }
        return $this->redirect('/author');
    }
}
