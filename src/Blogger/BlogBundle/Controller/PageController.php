<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Post;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            // ...
        ));
    }

    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig', array(
            // ...
        ));
    }

    public function loginAction()
    {
        return $this->render('FOSUserBundle:Security:login.html', array(
           //
        ));
    }
}
