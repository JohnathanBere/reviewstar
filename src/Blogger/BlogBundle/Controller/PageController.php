<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\BloggerBlogBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Post;

class PageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $blogPosts = $em->getRepository('BloggerBlogBundle:Post')->getLatest(10, 0);

        return $this->render('BloggerBlogBundle:Page:index.html.twig', [
            'blogPosts' => $blogPosts
        ]);
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
