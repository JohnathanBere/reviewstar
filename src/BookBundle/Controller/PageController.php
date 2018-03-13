<?php

namespace BookBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use BookBundle\Entity\Book;
use BookBundle\Form\UserFormType;
use BookBundle\Service\BookService;
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
    private $reviewRepo;
    private $userAccessPriv;

    public function __construct(BookService $bookService, EntityManagerInterface $emi)
    {
        $this->bookService = $bookService;
        $this->emi = $emi;
        $this->bookRepo = $this->emi->getRepository("BookBundle:Book");
        $this->userRepo = $this->emi->getRepository("BookBundle:User");
        $this->reviewRepo = $this->emi->getRepository("BookBundle:Review");
        $this->userAccessPriv = ['ROLE_ADMIN', 'ROLE_SITE_ADMIN', 'ROLE_USER_ADMIN'];
    }

    public function indexAction() {
        $books = $this->bookService->getAllBooks();

        return $this->render('ReviewStarBookBundle:Page:index.html.twig', [
            'books' => $books
        ]);
    }

    public function listAction($page = 1) {
        $bookPP = 1;

        $bookCount = $this->bookRepo->countBooks();
        $pageCount = ceil($bookCount / $bookPP);

        $pagedBooks = $this->bookService->getPage($bookPP, $page);

        return $this->render('BookBundle:Page:index.html.twig', [
            'books' => $pagedBooks, 'pageCount' => $pageCount, 'pageIndex' => $page
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

        return $this->render("BookBundle:Page:search.html.twig", [
            'form' => $form->createView()
        ]);
    }

    public function handleSearchAction(Request $request) {
        $string = $request->request->get('form')['search'];
        $filter = $request->request->get('form')['filter'];
        $books = $this->bookRepo->getBooksBySearch($string, $filter);

        return $this->render("BookBundle:Page:results.html.twig", [
            'books' => $books
        ]);
    }

    public function userIndexAction() {
        return $this->render("ReviewStarBookBundle:Page:users.html.twig", [
            'users' => $this->userRepo->findAll(),
            'privileges_user' => $this->userAccessPriv
        ]);
    }

    public function userEditAction(Request $request, $id) {
        $user = $this->userRepo->find($id);
        // Get the current roles, this is to ensure that the admin role does not get lost
        $roles = $user->getRoles();
        foreach($this->userAccessPriv as $privilege) {
            if ($this->getUser()->hasRole($privilege)) {
                if ($this->getUser() && !empty($user)) {
                    $form = $this->createForm(UserFormType::class, $user, [
                        'action' => $request->getUri()
                    ]);
                    $form->handleRequest($request);
                    if ($form->isValid()) {
                        foreach($roles as $role) {
                            if ($role == 'ROLE_ADMIN') {
                                $user->addRole($role);
                            }
                        }
                        $this->emi->flush();
                        return $this->redirectToUsersPage();
                    }

                    return $this->render("BookBundle:Page:user-edit.html.twig", [
                        'form' => $form->createView(),
                        'user' => $user
                    ]);
                }
            }
        }
        return $this->redirectToUsersPage();
    }

    public function userViewAction($id) {
        $user = $this->userRepo->find($id);

        if (!empty($user)) {
            $books = $this->bookRepo->getBooksByUser($id);
            $reviews = $this->reviewRepo->getReviewsByUser($id);

            return $this->render("ReviewStarBookBundle:Page:user-view.html.twig", [
                'books' => $books,
                'reviews' => $reviews,
                'user' => $user
            ]);
        }

        return $this->redirectToUsersPage();
    }

    public function redirectToUsersPage() {
        return $this->render("BookBundle:Page:users.html.twig", [
            'users' => $this->userRepo->findAll(),
            'privileges_user' => $this->userAccessPriv
        ]);
    }
}