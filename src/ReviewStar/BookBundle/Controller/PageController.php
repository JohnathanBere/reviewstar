<?php

namespace ReviewStar\BookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller {
    function indexAction() {
        return $this->render('ReviewStarBookBundle:Page:index.html.twig');
    }
}