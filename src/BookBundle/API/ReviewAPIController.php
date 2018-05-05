<?php

namespace BookBundle\API;

use BookBundle\Entity\Review;
use BookBundle\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Remember to run "php bin/console debug:router" to check all routes that you have generated.
 * Class ReviewAPIController
 * @package BookBundle\API
 */
class ReviewAPIController extends FOSRestController
{
    private $emi;
    private $reviewRepo;
    private $bookRepo;

    /**
     * ReviewAPIController constructor.
     *
     * @param EntityManagerInterface $emi
     */
    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
        $this->reviewRepo = $this->emi->getRepository("BookBundle:Review");
        $this->bookRepo = $this->emi->getRepository("BookBundle:Book");
    }

    /**
     * Gets all the reviews.
     * @param $bookId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getReviewsAction($bookId)
    {
        $reviews = $this->reviewRepo->getReviewsByBook($bookId);

        if (empty($reviews)) {
            return $this->handleView($this->view("There are no reviews for this book", 204));
        }

        return $this->handleView($this->view($reviews, 200));
    }

    /**
     * Gets a single review resource.
     * @param $bookId
     * @param $reviewId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getReviewAction($bookId, $reviewId) {
        $review = $this->reviewRepo->getSingleReviewByBook($bookId, $reviewId);

        if (empty($review)) {
            return $this->handleView($this->view("Review not found", 204));
        }

        return $this->handleView($this->view($review, 200));
    }

    /**
     * Creates a single review resource
     * @param Request $request
     * @param $bookId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postReviewAction(Request $request, $bookId) {
        if (!$this->getUser()) {
            return $this->handleView($this->view("Unable to make a post, are you logged in?", 401));
        }

        $book = $this->bookRepo->find($bookId);

        if (empty($book)) {
            return $this->handleView($this->view("The requested book could not be found", 404));
        }

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);

        if ($request->getContentType() != "json") {
            return $this->handleView($this->view("Requested content is not valid JSON", 400));
        }

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $review->setUser($this->getUser());
            $review->setCreated(new \DateTime());
            $review->setBook($book);
            $this->emi->persist($review);
            $this->emi->flush();

            return $this->handleView($this->view("Review added successfully", 201)
                ->setLocation(
                    $this->generateUrl("api_review_get_book_review",
                        ["reviewId" => $review->getId(), "bookId" => $book->getId()]
                    )
                )
            );
        }
        return $this->handleView($this->view($form, 400));
    }

    /**
     * Creates a single review resource
     * @param Request $request
     * @param $bookId
     * @param $reviewId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putReviewAction(Request $request, $bookId, $reviewId) {
        if (!$this->getUser()) {
            return $this->handleView($this->view("Unable to update a review, are you logged in?", 401));
        }

        $book = $this->bookRepo->find($bookId);

        if (empty($book)) {
            return $this->handleView($this->view("The requested book could not be retrieved", 400));
        }

        $review = $this->reviewRepo->find($reviewId);

        if (empty($review)) {
            return $this->handleView($this->view("The requested review could not be retrieved", 400));
        }

        if ($this->getUser() != $review->getUser()) {
            return $this->handleView($this->view("Unable to update a review, are you the one who made the review?", 401));
        }

        $form = $this->createForm(ReviewType::class, $review, ["action" => $request->getUri()]);

        if ($request->getContentType() != "json") {
            return $this->handleView($this->view("Requested content is not valid JSON", 400));
        }

        $form->submit(json_decode($request->getContent(),true));

        if ($form->isValid()) {
            $this->emi->flush();

            return $this->handleView($this->view("Review updated successfully", 202)
                ->setLocation(
                    $this->generateUrl("api_review_get_book_review",
                        ["reviewId" => $review->getId(), "bookId" => $book->getId()]
                    )
                )
            );
        }
        return $this->handleView($this->view($form, 400));
    }

    /**
     * Deletes a book
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteReviewAction($id)
    {
        $review = $this->reviewRepo->find($id);

        if (!$review->getUser() != $this->getUser()) {
            return $this->handleView($this->view("You cannot delete someone else's review", 401));
        }

        if (!empty($review)) {
            $this->emi->remove($review);
            $this->emi->flush();
            return $this->handleView($this->view("Review deleted", 202));
        }

        return $this->handleView($this->view("The requested review cannot exist", 404));
    }
}