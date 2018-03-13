<?php

namespace BookBundle\API;

use FOS\RestBundle\Controller\FOSRestController;
use BookBundle\Entity\Book;
use BookBundle\Entity\User;
use BookBundle\Form\BookType;
use Symfony\Component\HttpFoundation\Request;

class BookAPIController extends FOSRestController {
    public function getBooksAction() {
        $em = $this->getDoctrine()->getManager();

        $books = $em->getRepository("ReviewStarBookBundle:Book")->findAll();

        return $this->handleView($this->view($books));
    }

    public function getBookAction($id) {
        $em = $this->getDoctrine()->getManager();

        $book = $em->getRepository("ReviewStarBookBundle:Book")->find($id);

        if (!empty($book)) {
            return $this->handleView($this->view($book));
        }

        $view = $this->view(null, 404);
        return $this->handleView($view);
    }

    public function postBookAction(Request $request) {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        if ($request->getContentType() != "json") {
            return $this->handleView($this->view(null, 400));
        }

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // $user = $em->getRepository("ReviewStarBookBundle:User")->find(1);
            $book->setUser($this->getUser());
            $book->setCreated(new \DateTime());
            $em->persist($book);
            $em->flush();

            return $this->handleView($this->view(null, 202)
                ->setLocation(
                    $this->generateUrl("api_book_get_book",
                        ["id" => $book->getId()]
                    )
                )
            );
        } else {
            return $this->handleView($this->view($form, 400));
        }
    }
}