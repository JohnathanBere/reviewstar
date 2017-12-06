<?php

namespace ReviewStar\BookBundle\Controller;

use ReviewStar\BookBundle\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller {
    private $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }


    public function indexAction() {
        $books = $this->bookService->getAllBooks();

        return $this->render('ReviewStarBookBundle:Page:index.html.twig', [
            'books' => $books
        ]);
    }

    public function listAction($pageNo = 1) {
        $bookPP = 1;

        $books = $this->bookService->getAllBooks();
        $bookCount = count($books);
        $pageDiv = ceil($bookCount / $bookPP);
        $pagedBooks = $this->bookService->getPage($bookPP, $pageNo);

        return $this->render('ReviewStarBookBundle:Page:index.html.twig', [
            'books' => $pagedBooks, 'pageCount' => $pageDiv
        ]);
    }
}