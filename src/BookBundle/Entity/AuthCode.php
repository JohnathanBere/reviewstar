<?php

namespace BookBundle\Entity;

use FOS\OAuthServerBundle\Entity\AuthCode as BaseAuthCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AuthCode
 * @package BookBundle\Entity
 * @ORM\Entity
 */
class AuthCode extends BaseAuthCode
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="BookBundle\Entity\Client")
     * @ORM\JoinColumn(name="rs_client_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="BookBundle\Entity\User")
     * @ORM\JoinColumn(name="rs_user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;
}