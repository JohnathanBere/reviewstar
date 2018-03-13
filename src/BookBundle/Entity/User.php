<?php

namespace BookBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BasedUser;

/**
 * User
 *
 * @ORM\Table(name="rs_users")
 * @ORM\Entity(repositoryClass="BookBundle\Repository\UserRepository")
 */
class User extends BasedUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="BookBundle\Entity\Book", mappedBy="user")
     */
    private $books;

    /**
     * @return mixed
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @ORM\OneToMany(targetEntity="BookBundle\Entity\Review", mappedBy="user")
     */
    private $reviews;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        parent::__construct();
        $this->books = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }
}

