<?php

namespace ReviewStar\BookBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ReviewStar\BookBundle\Entity\Review;
use ReviewStar\BookBundle\Form\ReviewType;
use ReviewStar\BookBundle\Service\BookService;
use ReviewStar\BookBundle\Service\ReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends Controller
{
    private $reviewService;
    private $bookService;
    private $emi;

    public function __construct(ReviewService $reviewService, BookService $bookService, EntityManagerInterface $emi)
    {
        $this->reviewService = $reviewService;
        $this->bookService = $bookService;
        $this->emi = $emi;
    }

    public function createAction(Request $request, $bookId) {
        $book = $this->bookService->getBook($bookId);
        $reviews = $this->getUser() ? $this->reviewService->getAllReviewsByUserId($this->getUser()->getId()) : null;
        if ($this->getUser() && !empty($book) && count($reviews) == 0) {
            $review = new Review();
            $form = $this->createForm(ReviewType::class, $review, [
                'action' => $request->getUri()
            ]);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->reviewService->insertReview($review, $book, $this->getUser());
                return $this->redirect($this->generateUrl('rs_book_view', [
                    'id' => $book->getId()
                ]));
            }

            return $this->render("ReviewStarBookBundle:Review:create.html.twig", [
                'form' => $form->createView()
            ]);
        }
        return $this->redirect($this->generateUrl('rs_book_view', [ 'id' => $bookId ]));
    }

    public function editAction(Request $request, $bookId, $id) {
        $book = $this->bookService->getBook($bookId);
        if ($this->getUser() && !empty($book)) {
            if ($book->getUser() == $this->getUser()) {
                $review = $this->reviewService->getReviewById($id);
                if (!empty($review) && $review->getUser() == $this->getUser()) {
                    $form = $this->createForm(ReviewType::class, $review, [
                        'action' => $request->getUri(),
                    ]);
                    $form->handleRequest($request);

                    if ($form->isValid()) {
                        $this->emi->flush();
                        return $this->redirect($this->generateUrl('rs_book_view', [
                            'id' => $book->getId()
                        ]));
                    }

                    return $this->render("ReviewStarBookBundle:Review:edit.html.twig", [
                        'form' => $form->createView()
                    ]);
                }
                return $this->redirect($this->generateUrl('rs_book_view', [
                    'id' => $book->getId()
                ]));
            }
        }
        return $this->redirect($this->generateUrl('index'));
    }

    public function deleteAction($id) {
        $review = $this->reviewService->getReviewById($id);
        $book = $this->bookService->getBook($review->getBook()->getId());
        if (!empty($review) && $this->getUser() == $review->getUser()) {
            $this->emi->remove($review);
            $this->emi->flush();
            return $this->redirect($this->generateUrl('rs_book_view', ['id' => $book->getId()]));
        }
        return $this->redirect($this->generateUrl('rs_book_view', ['id' => $book->getId()]));
    }
}
