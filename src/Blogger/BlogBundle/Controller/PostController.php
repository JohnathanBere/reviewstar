<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Blogger\BlogBundle\Form\PostType;
use Blogger\BlogBundle\Entity\Post;

class PostController extends Controller
{
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $blogPost = $em->getRepository('BloggerBlogBundle:Post')->find($id);

        return $this->render('BloggerBlogBundle:Post:view.html.twig', [
            'post' => $blogPost
        ]);
    }

    public function createAction(Request $request)
    {
        $blogPost = new Post();

        $form = $this->createForm(PostType::class, $blogPost, [
            'action' => $request->getUri()
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $blogPost->setUser($this->getUser());

            $blogPost->setCreated(new \DateTime());

            $em->persist($blogPost);

            $em->flush();

            return $this->redirect($this->generateUrl('blogger_post_view',
                ['id' => $blogPost->getId()]));
        }

        return $this->render('BloggerBlogBundle:Post:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $blogPost = $em->getRepository('BloggerBlogBundle:Post')->find($id);

        $form = $this->createForm(PostType::class, $blogPost, [
            'action' => $request->getUri()
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('blogger_post_view',
                ['id' => $blogPost->getId()]));
        }

        return $this->render('BloggerBlogBundle:Post:edit.html.twig', [
            'form' => $form->createView(),
            'post' => $blogPost
        ]);
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $blogPost = $em->getRepository('BloggerBlogBundle:Post')->find($id);

        $em->remove($blogPost);
        $em->flush();
        return $this->redirect($this->generateUrl('index'));
    }

}
