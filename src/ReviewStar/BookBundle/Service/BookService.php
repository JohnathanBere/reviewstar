<?php
namespace ReviewStar\BookBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use ReviewStar\BookBundle\Entity\Book;
use Symfony\Component\HttpFoundation\Request;

class BookService {
    private $emi;

    /**
     * BookService constructor.
     * @param $emi
     */
    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
    }

    public function getBooksForUser($userId) {
        $books = $this
            ->emi
            ->getRepository("ReviewStarBookBundle:Book")
            ->getBooksByUser($userId);

        return $books;
    }

    public function getAllBooks() {
        return $this->emi->getRepository("ReviewStarBookBundle:Book")->findAll();
    }


    public function getBook($id) {
        $book = $this->emi->getRepository("ReviewStarBookBundle:Book")->find($id);

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

    public function updateBook($id, Request $request) {
        $book = $this->getBook($id);
    }

    public function getPage($booksPerPage, $currentPage = 1) {
        $offSet = ($currentPage - 1) * $booksPerPage;
        return $this->emi->getRepository("ReviewStarBookBundle:Book")->getLatest($booksPerPage, $offSet);
    }


}