<?php

namespace BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Review
 *
 * @ORM\Table(name="rs_reviews")
 * @ORM\Entity(repositoryClass="BookBundle\Repository\ReviewRepository")
 * @ExclusionPolicy("all")
 */
class Review
{
    /**
     * @ORM\ManyToOne(targetEntity="BookBundle\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn(name="rs_user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="BookBundle\Entity\Book", inversedBy="reviews")
     * @ORM\JoinColumn(name="rs_book_id", referencedColumnName="id")
     *
     */
    private $book;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="review_title", type="string", length=255, nullable=true)
     * @Expose
     */
    private $reviewTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="review_content", type="text", nullable=true)
     * @Expose
     */
    private $reviewContent;

    /**
     * @var float
     *
     * @ORM\Column(name="review_rating", type="float")
     * @Expose
     */
    private $reviewRating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     * @Expose
     */
    private $created;

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $reviewTitle
     *
     * @return Review
     */
    public function setReviewTitle($reviewTitle)
    {
        $this->reviewTitle = $reviewTitle;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getReviewTitle()
    {
        return $this->reviewTitle;
    }

    /**
     * Set content
     *
     * @param string $reviewContent
     *
     * @return Review
     */
    public function setReviewContent($reviewContent)
    {
        $this->reviewContent = $reviewContent;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getReviewContent()
    {
        return $this->reviewContent;
    }

    /**
     * Set rating
     *
     * @param float $reviewRating
     *
     * @return Review
     */
    public function setReviewRating($reviewRating)
    {
        $this->reviewRating = $reviewRating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getReviewRating()
    {
        return $this->reviewRating;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     *
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param mixed $book
     */
    public function setBook($book)
    {
        $this->book = $book;
    }


}

