<?php

namespace ReviewStar\BookBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ReviewStar\BookBundle\Entity\Book;
use ReviewStar\BookBundle\Form\UserFormType;
use ReviewStar\BookBundle\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller {
    private $bookService;
    private $emi;
    private $bookRepo;
    private $userRepo;

    public function __construct(BookService $bookService, EntityManagerInterface $emi)
    {
        $this->bookService = $bookService;
        $this->emi = $emi;
        $this->bookRepo = $this->emi->getRepository("ReviewStarBookBundle:Book");
        $this->userRepo = $this->emi->getRepository("ReviewStarBookBundle:User");
    }

    public function indexAction() {
        $books = $this->bookService->getAllBooks();

        return $this->render('ReviewStarBookBundle:Page:index.html.twig', [
            'books' => $books
        ]);
    }

    public function listAction($page = 1) {
        $bookPP = 8;

        $bookCount = $this->bookRepo->countBooks();
        $pageCount = ceil($bookCount / $bookPP);

        $pagedBooks = $this->bookService->getPage($bookPP, $page);

        return $this->render('ReviewStarBookBundle:Page:index.html.twig', [
            'books' => $pagedBooks, 'pageCount' => $pageCount
        ]);
    }

    public function searchAction() {
        $form = $this->createFormBuilder(null)
            ->add("search", TextType::class, [ 'required' => false ])
            ->add("button", SubmitType::class, [ 'label' => 'Go'])
            ->add("filter", ChoiceType::class, [
                'label' => 'Search by',
                'choices' => [
                    'Author' => 'author',
                    'Title'=> 'title',
                    'Publisher' => 'publisher'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->getForm();

        return $this->render("ReviewStarBookBundle:Page:search.html.twig", [
            'form' => $form->createView()
        ]);
    }

    public function handleSearchAction(Request $request) {
        $string = $request->request->get('form')['search'];
        $filter = $request->request->get('form')['filter'];
        $books = $this->bookRepo->getBooksBySearch($string, $filter);

        return $this->render("ReviewStarBookBundle:Page:results.html.twig", [
            'books' => $books
        ]);
    }

    public function userIndexAction() {
        return $this->render("ReviewStarBookBundle:Page:users.html.twig", [
            'users' => $this->userRepo->findAll()
        ]);
    }

    public function userEditAction(Request $request, $id) {
        $user = $this->userRepo->find($id);
        if ($this->getUser()->hasRole('ROLE_ADMIN') && !empty($user)) {
            $form = $this->createForm(UserFormType::class, $user, [
                'action' => $request->getUri()
            ]);
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($this->getUser() == $user && $user->hasRole('ROLE_ADMIN')) {
                    $user->addRole('ROLE_ADMIN');
                }
                $this->emi->flush();
                return $this->redirectToUsersPage();
            }

            return $this->render("ReviewStarBookBundle:Page:user-edit.html.twig", [
                'form' => $form->createView(),
                'user' => $user
            ]);
        }
        return $this->redirectToUsersPage();
    }

    public function redirectToUsersPage() {
        return $this->render("ReviewStarBookBundle:Page:users.html.twig", [
            'users' => $this->userRepo->findAll()
        ]);
    }
}