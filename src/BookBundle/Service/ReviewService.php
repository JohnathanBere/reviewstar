<?php
namespace BookBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use BookBundle\Controller\ReviewController;
use BookBundle\Entity\Book;
use BookBundle\Entity\Review;
use BookBundle\Form\ReviewType;
use Symfony\Component\HttpFoundation\Request;

class ReviewService
{
    private $emi, $reviewRepo;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
        $this->reviewRepo = $this->emi->getRepository("BookBundle:Review");
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

    public function getAllReviewsByUserId($userId) {
        return $this->reviewRepo->getReviewsByUser($userId);
    }

    public function getAllReviewsByBookandUser($bookId, $userId) {
        return $this->reviewRepo->getReviewsByBookAndUser($bookId, $userId);
    }
}