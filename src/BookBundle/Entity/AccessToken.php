<?php
/**
 * Created by PhpStorm.
 * User: John Bere
 * Date: 18/04/2018
 * Time: 04:54
 */

namespace BookBundle\Entity;

use FOS\OAuthServerBundle\Entity\AccessToken as BaseAccessToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AccessToken
 * @package BookBundle\Entity
 * @ORM\Entity
 */
class AccessToken extends BaseAccessToken
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