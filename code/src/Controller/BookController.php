<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
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

class BookController extends AbstractController
{
    #[Route('/book', name: 'book_index')]
    public function index(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/book/create', name: 'book_create')]
    public function create(Request $request, ManagerRegistry $doctrine, FileUploader$fileUploader): Response
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $book = $form->getData();
            $coverFile = $form->get('cover_file_name')->getData();
            if ($coverFile) {
                $coverFileName = $fileUploader->upload($coverFile);
                $book->setCoverFileName($coverFileName);
            }

             $entityManager = $doctrine->getManager();
             $entityManager->persist($book);
             $entityManager->flush();

            $this->addFlash('success', 'Книга добавлена');

            return $this->redirect('/book/' . $book->getId());
        }

        return $this->render('book/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/book/edit/{id}', name: 'book_update')]
    public function edit(int $id, Request $request, BookRepository $bookRepository, ManagerRegistry $doctrine, FileUploader$fileUploader): Response
    {
        $book = $bookRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $coverFile = $form->get('cover_file_name')->getData();
            if ($coverFile) {
                $coverFileName = $fileUploader->upload($coverFile);
                $book->setCoverFileName($coverFileName);
            }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Книга обновлена');

            return $this->redirect('/book/' . $book->getId());
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form,
            'book' => $book,
        ]);
    }


    #[Route('/book/{id}', name: 'book_show')]
    public function show(int $id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'Нет книги с таким id: '.$id
            );
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/book/delete/{id}', name: 'book_delete')]
    public function delete(int $id, BookRepository $bookRepository, ManagerRegistry $doctrine): Response
    {
        $book = $bookRepository->find($id);
        if ($book) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
            $this->addFlash('success', 'Книга удалена');

        }
        else {
            $this->addFlash('success', 'Ошибка. Нет такой книги');
        }
        return $this->redirect('/book');
    }
}
