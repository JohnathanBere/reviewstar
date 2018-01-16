<?php

namespace ReviewStar\BookBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ReviewStar\BookBundle\Entity\Book;
use ReviewStar\BookBundle\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller {
    private $bookService;
    private $emi;
    private $bookRepo;

    public function __construct(BookService $bookService, EntityManagerInterface $emi)
    {
        $this->bookService = $bookService;
        $this->emi = $emi;
        $this->bookRepo = $this->emi->getRepository("ReviewStarBookBundle:Book");
    }

    public function indexAction() {
        $books = $this->bookService->getAllBooks();

        return $this->render('ReviewStarBookBundle:Page:index.html.twig', [
            'books' => $books
        ]);
    }

    public function listAction($page = 1) {
        $bookPP = 8;

        $bookCount = $this->bookRepo->countBooks();
        $pageCount = ceil($bookCount / $bookPP);

        $pagedBooks = $this->bookService->getPage($bookPP, $page);

        return $this->render('ReviewStarBookBundle:Page:index.html.twig', [
            'books' => $pagedBooks, 'pageCount' => $pageCount
        ]);
    }
}