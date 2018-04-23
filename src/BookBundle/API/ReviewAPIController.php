<?php

namespace BookBundle\API;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ReviewAPIController extends FOSRestController
{
    private $emi;
    private $reviewRepo;

    /**
     * ReviewAPIController constructor.
     *
     * @param EntityManagerInterface $emi
     */
    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
        $this->reviewRepo = $this->emi->getRepository("BookBundle:Review");
    }

    /**
     * Gets all the books.
     * @param $bookId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getReviewsAction($bookId)
    {
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(array('http://www.example.com'));
        $client->setAllowedGrantTypes(array('token', 'authorization_code'));
        $clientManager->updateClient($client);

        $reviews = $this->reviewRepo->getReviewsByBook($bookId);

        if (empty($reviews)) {
            return $this->handleView($this->view("There are no reviews for this book", 204));
        }

        if ($this->get("security.context")->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->handleView($this->view("NOT LOGGED IN FUCK OFF", 200));
        }

        return $this->handleView($this->view($reviews, 200));
    }
}