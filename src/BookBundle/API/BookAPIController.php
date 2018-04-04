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
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        if ($request->getContentType() != "json") {
            return $this->handleView($this->view("Requested content is not valid JSON", 400));
        }

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $book->setUser($this->getUser());
            $book->setCreated(new \DateTime());
            $this->emi->persist($book);
            $this->emi->flush();

            return $this->handleView($this->view("Book added successfully", 202)
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
        $form = $this->createForm(BookType::class, $book, ["action" => $request->getUri()]);

        $form->handleRequest($request);

        if ($request->getContentType() != "json") {
            return $this->handleView($this->view("Requested content is not valid JSON", 400));
        }

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $this->emi->flush();
            return $this->handleView($this->view("Book updated successfully", 202)->setLocation($this->generateUrl("api_book_get_book", ["id" => $book->getId()])));
        }

        return $this->handleView($this->view($form, 400));
    }

    /**
     * Deletes a book
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteBookAction($id)
    {
        $book = $this->bookRepo->find($id);

        if (!empty($book)) {
            $this->emi->remove($book);
            $this->emi->flush();
            return $this->handleView($this->view("File deleted mate...", 202));
        }

        return $this->handleView($this->view("Whoops something went wrong", 404));
    }
}