<?php

namespace BookBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use BookBundle\Entity\User as OriginalPoster;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Book
 *
 * @ORM\Table(name="rs_books")
 * @ORM\Entity(repositoryClass="BookBundle\Repository\BookRepository")
 * @ExclusionPolicy("all")
 */
class Book
{
    /**
     * Book constructor.
     */
    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

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
     * @ORM\ManyToOne(targetEntity="BookBundle\Entity\User", inversedBy="books")
     * @ORM\JoinColumn(name="rs_user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="\BookBundle\Entity\Review", mappedBy="book", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @var string
     *
     * @ORM\Column(name="book_author", type="string", length=255)
     * @Expose
     */
    private $bookAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="book_title", type="string", length=255)
     * @Expose
     */
    private $bookTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="book_publisher", type="string", length=255, nullable=true)
     * @Expose
     */
    private $bookPublisher;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="book_publishdate", type="datetime", nullable=true)
     * @Expose
     */
    private $bookPublishdate;

    /**
     * @var string
     * @Assert\NotBlank(message="Please put an image")
     * @ORM\Column(name="book_cover", type="string", length=255, nullable=true)
     * @Expose
     */
    private $bookCover;

    /**
     * @var string
     *
     * @ORM\Column(name="book_thumbnail", type="string", length=255, nullable=true)
     */
    private $bookThumbnail;

    /**
     * @var string
     *
     * @ORM\Column(name="book_synopsis", type="string", length=255, nullable=true)
     * @Expose
     */
    private $bookSynopsis;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     * @Expose
     */
    private $created;


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
     * Set bookAuthor
     *
     * @param string $bookAuthor
     *
     * @return Book
     */
    public function setBookAuthor($bookAuthor)
    {
        $this->bookAuthor = $bookAuthor;

        return $this;
    }

    /**
     * Get bookAuthor
     *
     * @return string
     */
    public function getBookAuthor()
    {
        return $this->bookAuthor;
    }

    /**
     * Set bookTitle
     *
     * @param string $bookTitle
     *
     * @return Book
     */
    public function setBookTitle($bookTitle)
    {
        $this->bookTitle = $bookTitle;

        return $this;
    }

    /**
     * Get bookTitle
     *
     * @return string
     */
    public function getBookTitle()
    {
        return $this->bookTitle;
    }

    /**
     * Set bookPublisher
     *
     * @param string $bookPublisher
     *
     * @return Book
     */
    public function setBookPublisher($bookPublisher)
    {
        $this->bookPublisher = $bookPublisher;

        return $this;
    }

    /**
     * Get bookPublisher
     *
     * @return string
     */
    public function getBookPublisher()
    {
        return $this->bookPublisher;
    }

    /**
     * Set bookPublishdate
     *
     * @param \DateTime $bookPublishdate
     *
     * @return Book
     */
    public function setBookPublishdate($bookPublishdate)
    {
        $this->bookPublishdate = $bookPublishdate;

        return $this;
    }

    /**
     * Get bookPublishdate
     *
     * @return \DateTime
     */
    public function getBookPublishdate()
    {
        return $this->bookPublishdate;
    }

    /**
     * Set bookCover
     *
     * @param string $bookCover
     *
     * @return Book
     */
    public function setBookCover($bookCover)
    {
        $this->bookCover = $bookCover;

        return $this;
    }

    /**
     * Get bookCover
     *
     * @return string
     */
    public function getBookCover()
    {
        return $this->bookCover;
    }

    /**
     * Set bookThumbnail
     *
     * @param string $bookThumbnail
     *
     * @return Book
     */
    public function setBookThumbnail($bookThumbnail)
    {
        $this->bookThumbnail = $bookThumbnail;

        return $this;
    }

    /**
     * Get bookThumbnail
     *
     * @return string
     */
    public function getBookThumbnail()
    {
        return $this->bookThumbnail;
    }

    /**
     * Set bookSynopsis
     *
     * @param string $bookSynopsis
     *
     * @return Book
     */
    public function setBookSynopsis($bookSynopsis)
    {
        $this->bookSynopsis = $bookSynopsis;

        return $this;
    }

    /**
     * Get bookSynopsis
     *
     * @return string
     */
    public function getBookSynopsis()
    {
        return $this->bookSynopsis;
    }

    /**
     * Set bookSynopsis
     *
     * @param \DateTime $created
     *
     * @return Book
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTIme
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set user
     *
     * @param OriginalPoster $user
     *
     * @return OriginalPoster
     */
    public function setUser(OriginalPoster $user = null)
    {
        $this->user = $user;

        return $this->user;
    }

    /**
     * Get user
     *
     * @return OriginalPoster
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    public function getAverageRating() {
        $sum = 0.0;

        foreach($this->getReviews() as $review) {
            $sum += $review->getReviewRating();
        }

        $avg = count($this->getReviews()) > 0 ? $sum / count($this->getReviews()) : 0;

        return $avg;
    }
}

