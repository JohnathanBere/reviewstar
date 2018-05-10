<?php

namespace BookBundle\API;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use BookBundle\Entity\Book;
use BookBundle\Form\BookType;
use Symfony\Component\HttpFoundation\Request;

class BookAPIController extends FOSRestController
{
    private $emi;
    private $bookRepo;

    /**
     * BookAPIController constructor.
     *
     * @param EntityManagerInterface $emi
     */
    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
        $this->bookRepo = $this->emi->getRepository("BookBundle:Book");
    }

    /**
     * Gets all the books.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBooksAction()
    {
        $books = $this->bookRepo->findAll();

        if (empty($books)) {
            return $this->handleView($this->view("There are no books", 204));
        }

        return $this->handleView($this->view($books, 200));
    }

    /**
     * Returns a book of a specific ID
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBookAction($id)
    {
        $book = $this->bookRepo->find($id);

        if (!empty($book)) {
            return $this->handleView($this->view($book, 200));
        }

        $view = $this->view("Book not found", 404);
        return $this->handleView($view);
    }

    /**
     * adds a new book
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postBookAction(Request $request)
    {
        if (!$this->getUser()) {
            return $this->handleView($this->view("Must be logged in to create a book", 401));
        }

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        if ($request->getContentType() != "json") {
            return $this->handleView($this->view("Requested content is not valid JSON", 400));
        }

        if (empty($book->getBookTitle())) {
            return $this->handleView($this->view("There must be a book title", 400));
        }

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $book->setUser($this->getUser());
            $book->setCreated(new \DateTime());
            $this->emi->persist($book);
            $this->emi->flush();

            return $this->handleView($this->view("Book added successfully", 201)
                ->setLocation(
                    $this->generateUrl("api_book_get_book",
                        ["id" => $book->getId()]
                    )
                )
            );
        }
        return $this->handleView($this->view($form, 400));
    }

    /**
     * Updates a book
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putBookAction($id, Request $request)
    {
        $book = $this->bookRepo->find($id);

        if (!$this->getUser()) {
            return $this->handleView($this->view("Must be logged in to update a book", 401));
        }

        if (empty($book)) {
            return $this->handleView($this->view("The book cannot be found", 404));
        }

        if ($book->getUser() != $this->getUser()) {
            return $this->handleView($this->view("You cannot update someone else's book", 403));
        }

        $form = $this->createForm(BookType::class, $book, ["action" => $request->getUri()]);

        $form->handleRequest($request);

        if ($request->getContentType() != "json") {
            return $this->handleView($this->view("Requested content is not valid JSON", 400));
        }

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $this->emi->flush();
            return $this->handleView($this->view("Book updated successfully", 200)
                ->setLocation(
                    $this->generateUrl(
                        "api_book_get_book", ["id" => $book->getId()]
                    )
                )
            );
        }

        return $this->handleView($this->view($form, 400));
    }

    /**
     * Deletes a book
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteBookAction($id)
    {
        if (!$this->getUser()) {
            return $this->handleView($this->view("Must be logged in to create a book", 401));
        }

        $book = $this->bookRepo->find($id);

        if (!$book->getUser() != $this->getUser()) {
            return $this->handleView($this->view("You cannot delete someone else's book", 403));
        }

        if (!empty($book)) {
            $this->emi->remove($book);
            $this->emi->flush();
            return $this->handleView($this->view("Book deleted", 200));
        }

        return $this->handleView($this->view("The book does not exist", 404));
    }
}