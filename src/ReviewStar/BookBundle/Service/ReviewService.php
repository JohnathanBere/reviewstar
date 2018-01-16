<?php
namespace ReviewStar\BookBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use ReviewStar\BookBundle\Controller\ReviewController;
use ReviewStar\BookBundle\Entity\Book;
use ReviewStar\BookBundle\Entity\Review;
use ReviewStar\BookBundle\Form\ReviewType;
use Symfony\Component\HttpFoundation\Request;

class ReviewService
{
    private $emi, $reviewRepo;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
        $this->reviewRepo = $this->emi->getRepository("ReviewStarBookBundle:Review");
    }

    public function insertReview(Review $review, Book $book, $currentUser): void {
        if (!$review->getId()) {
            $review->setUser($currentUser);
            $review->setCreated(new \DateTime());

            $review->setBook($book);

            $this->emi->persist($review);
            $this->emi->flush();
        }
    }

    public function getReviewById($id) {
        return $this->reviewRepo->find($id);
    }

    public function getAllReviews() {
        return $this->reviewRepo->findAll();
    }

    public function getAllReviewsByBookId($bookId) {
        return $this->reviewRepo->getReviewsByBook($bookId);
    }
}