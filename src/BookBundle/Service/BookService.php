<?php
namespace BookBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use BookBundle\Controller\BookController;
use BookBundle\Entity\Book;
use Symfony\Component\HttpFoundation\Request;

class BookService {
    private $emi;
    private $bookRepo;
    //private $bookController;

    /**
     * BookService constructor.
     * @param $emi
     */
    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
        $this->bookRepo = $this->emi->getRepository("BookBundle:Book");
    }

    public function getBooksForUser($userId) {
        $books = $this
            ->emi
            ->getRepository("BookBundle:Book")
            ->getBooksByUser($userId);

        return $books;
    }

    public function getAllBooks() {
        return $this->bookRepo->findAll();
    }

    public function getBook($id) {
        $book = $this->bookRepo->find($id);

        return $book;
    }

    public function insertBook(Book $book, $currentUser)
    {
        if (!$book->getId())
        {
            $book->setUser($currentUser);
            $book->setCreated(new \DateTime());

            $this->emi->persist($book);
            $this->emi->flush();
        }
    }

    public function updateBook(Book $book, $id) {
        if (!empty($book) && $book->getId() == $id) {
            $this->emi->flush();
        }
    }

    public function getPage($booksPerPage, $currentPage = 1) {
        $offSet = ($currentPage - 1) * $booksPerPage;
        return $this->bookRepo->getLatest($booksPerPage, $offSet);
    }


}