<?php

namespace ReviewStar\BookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ReviewStar\UserBundle\Entity\User as OriginalPoster;

/**
 * Book
 *
 * @ORM\Table(name="rs_books")
 * @ORM\Entity(repositoryClass="ReviewStar\BookBundle\Repository\BookRepository")
 */
class Book
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ReviewStar\UserBundle\Entity\User", inversedBy="books")
     * @ORM\JoinColumn(name="rs_user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="book_author", type="string", length=255)
     */
    private $bookAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="book_title", type="string", length=255)
     */
    private $bookTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="book_publisher", type="string", length=255, nullable=true)
     */
    private $bookPublisher;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="book_publishdate", type="datetimetz", nullable=true)
     */
    private $bookPublishdate;

    /**
     * @var string
     *
     * @ORM\Column(name="book_cover", type="string", length=255, nullable=true)
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
     */
    private $bookSynopsis;


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
     * @param OriginalPoster $user
     *
     * @return Book
     */
    public function setUser(OriginalPoster $user)
    {
        $this->$user = $user;

        return $this;
    }

    /**
     * Get bookSynopsis
     *
     * @return OriginalPoster
     */
    public function getUser()
    {
        return $this->user;
    }
}

