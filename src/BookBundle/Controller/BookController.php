<?php
namespace BookBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use BookBundle\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use BookBundle\Form\BookType;
use BookBundle\Entity\Book;

class BookController extends Controller {
    private $bookService;
    private $emi;
    private $bookAccessPriv;
    private $reviewAccessPriv;

    public function __construct(BookService $bookService, EntityManagerInterface $emi)
    {
        $this->bookService = $bookService;
        $this->emi = $emi;
        $this->bookAccessPriv = ['ROLE_ADMIN', 'ROLE_SITE_ADMIN', 'ROLE_BOOK_ADMIN'];
        $this->reviewAccessPriv = ['ROLE_ADMIN', 'ROLE_SITE_ADMIN', 'ROLE_BOOK_ADMIN', 'ROLE_MOD'];
    }

    public function fileHelper(Book $book) {
        $file = $book->getBookCover();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move(
            $this->getParameter('image_directory'), $fileName
        );

        $book->setBookCover($fileName);
    }

    // View one individual book redirect
    public function viewAction($id) {
        $book = $this->bookService->getBook($id);
        if(empty($book))
        {
            return $this->render("BookBundle:Page:index.html.twig");
        }

        return $this->render("BookBundle:Book:view.html.twig", [
            'book' => $book,
            'currentUser' => $this->getUser(),
            'privileges_book' => $this->bookAccessPriv,
            'privileges_review' => $this->reviewAccessPriv,
        ]);
    }

    public function createAction(Request $request) {
        if ($this->getUser()) {
            $book = new Book();
            $form = $this->createForm(BookType::class, $book, [
                'action' => $request->getUri()
            ]);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->fileHelper($book);
                $this->bookService->insertBook($book, $this->getUser());
                return $this->redirect($this->generateUrl('rs_book_view', [
                    'id' => $book->getId()
                ]));
            }

            return $this->render("BookBundle:Book:create.html.twig", [
                'form' => $form->createView()
            ]);
        }
        return $this->redirect($this->generateUrl('index'));
    }

    public function editAction($id, Request $request) {
        $book = $this->bookService->getBook($id);

        if (!empty($book)) {
            foreach($this->bookAccessPriv as $privilege) {
                if ($book->getUser() == $this->getUser() || $this->getUser()->hasRole($privilege))
                {
                    $form = $this->createForm(BookType::class, $book, [
                        'action' => $request->getUri()
                    ]);
                    $form->handleRequest($request);

                    if ($form->isValid()) {
                        $this->fileHelper($book);
                        $this->emi->flush();
                        return $this->redirect($this->generateUrl('rs_book_view',
                            ['id' => $book->getId()]));
                    }
                    return $this->render('BookBundle:Book:edit.html.twig', [
                        'form' => $form->createView(),
                        'book' => $book
                    ]);
                }
                return $this->redirect($this->generateUrl('rs_book_view', [
                    'id' => $book->getId(),
                    'privileges_review' => $this->reviewAccessPriv,
                    'privileges_book' => $this->bookAccessPriv
                ]));
            }
        }
    }

    public function deleteAction($id) {
        $book = $this->bookService->getBook($id);
        if (!empty($book)) {
            foreach($this->bookAccessPriv as $privilege) {
                if ($book->getUser()->hasRole($privilege) || $this->getUser() == $book->getUser()) {
                    $this->emi->remove($book);
                    $this->emi->flush();
                    return $this->redirect($this->generateUrl('index'));
                }
            }
        }
        return $this->redirect($this->generateUrl('rs_book_view', [ 'id' => $book->getId()]));
    }
}