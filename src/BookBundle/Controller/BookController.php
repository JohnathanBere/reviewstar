<?php
namespace BookBundle\Controller;

use BookBundle\Service\APIService;
use Doctrine\ORM\EntityManagerInterface;
use BookBundle\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use BookBundle\Form\BookType;
use BookBundle\Entity\Book;
use GuzzleHttp\Client;

class BookController extends Controller {
    private $bookService;
    private $emi;
    private $bookAccessPriv;
    private $reviewAccessPriv;
    private $apiService;


    public function __construct(BookService $bookService, EntityManagerInterface $emi, APIService $apiService) {
        $this->bookService = $bookService;
        $this->emi = $emi;
        $this->bookAccessPriv = ['ROLE_ADMIN', 'ROLE_SITE_ADMIN', 'ROLE_BOOK_ADMIN'];
        $this->reviewAccessPriv = ['ROLE_ADMIN', 'ROLE_SITE_ADMIN', 'ROLE_BOOK_ADMIN', 'ROLE_MOD'];
        $this->apiService = $apiService;
    }

    public function fileHelper(Book $book) {
        $file = $book->getBookCover();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move(
            $this->getParameter('image_directory'), $fileName
        );

        $book->setBookCover($fileName);
    }

    // View one individual book redirect
    public function viewAction($id) {
        $book = $this->bookService->getBook($id);
        if(empty($book)) {
            return $this->render("BookBundle:Page:index.html.twig");
        }

        return $this->render("BookBundle:Book:view.html.twig", [
            'book' => $book,
            'currentUser' => $this->getUser(),
            'privileges_book' => $this->bookAccessPriv,
            'privileges_review' => $this->reviewAccessPriv,
            'item' => $this->apiService->getBooksFromGoogleApi($book->getBookTitle())[0]
        ]);
    }

    public function createAction(Request $request) {
        if ($this->getUser()) {
            $book = new Book();
            $bookFromSession = $this->get("session")->get("prepopBook");

            if ($bookFromSession != null) {
                $book = $bookFromSession;
            }

            $form = $this->createForm(BookType::class, $book, [
                'action' => $request->getUri()
            ]);

            $form->handleRequest($request);

            if ($form->isValid()) {
                // $this->fileHelper($book);
                $this->bookService->insertBook($book, $this->getUser());
                $this->get("session")->clear();
                return $this->redirect($this->generateUrl('rs_book_view', [
                    'id' => $book->getId()
                ]));
            }

            $this->get("session")->clear();
            return $this->render("BookBundle:Book:create.html.twig", [
                'form' => $form->createView()
            ]);
        }

        $this->get("session")->clear();
        return $this->redirect($this->generateUrl('index'));
    }

    public function getPopularAction() {
        $bestSellers = $this->apiService->getBestSellersFromNYTApi();

        return $this->render("BookBundle:Book:most-popular.html.twig", [
            'bestSellers' => $bestSellers,
        ]);
    }

    public function retrieveVolumeInfoAction(Request $request) {
        $bookTitle = $request->request->get("bookTitle");
        return new JsonResponse($this->apiService->getVolumeInfoFromFirstItemFromGoogleApi($bookTitle));
    }

    public function retrieveItemsAction(Request $request) {
        $name = $request->request->get("inputField");
        return new JsonResponse($this->apiService->getBooksFromGoogleApi($name));
    }

    public function viewApiBookAction($id) {
        $item = $this->apiService->getSingleBookFromGoogleApi($id);

        $this->get("session")->clear();

        $pubDate = strtotime(empty($item->volumeInfo->publishedDate) ? null : $item->volumeInfo->publishedDate);
        $formattedPubDate = $pubDate != null ? date("Y-m-d", $pubDate) : new \DateTime();
        $book = new Book();
        $book->setBookTitle($item->volumeInfo->title);
        $book->setBookAuthor(empty($item->volumeInfo->authors[0]) ? null : $item->volumeInfo->authors[0]);
        $book->setBookSynopsis($item->volumeInfo->description);
        $book->setBookPublisher(empty($item->volumeInfo->publisher) ? null : $item->volumeInfo->publisher);
        $book->setBookPublishdate(new \DateTime($formattedPubDate));
        $this->get("session")->set("prepopBook", $book);

        if (!empty($item)) {
            return $this->render("BookBundle:Book:api-book-view.html.twig", ["item" => $item]);
        }

        return $this->redirect($this->generateUrl("index"));
    }

    public function apiSearchAction() {
        return $this->render("BookBundle:Book:api-book-search.html.twig");
    }

    public function editAction($id, Request $request) {
        $book = $this->bookService->getBook($id);

        if (!empty($book)) {
            foreach($this->bookAccessPriv as $privilege) {
                if ($book->getUser() == $this->getUser() || $this->getUser()->hasRole($privilege))
                {
                    $form = $this->createForm(BookType::class, $book, [
                        'action' => $request->getUri()
                    ]);
                    $form->handleRequest($request);

                    if ($form->isValid()) {
                        $this->emi->flush();
                        return $this->redirect($this->generateUrl('rs_book_view',
                            ['id' => $book->getId()]));
                    }
                    return $this->render('BookBundle:Book:edit.html.twig', [
                        'form' => $form->createView(),
                        'book' => $book
                    ]);
                }
                return $this->redirect($this->generateUrl('rs_book_view', [
                    'id' => $book->getId(),
                    'privileges_review' => $this->reviewAccessPriv,
                    'privileges_book' => $this->bookAccessPriv
                ]));
            }
        }
        return $this->render($this->generateUrl('index'));
    }

    public function deleteAction($id) {
        $book = $this->bookService->getBook($id);
        if (!empty($book)) {
            foreach($this->bookAccessPriv as $privilege) {
                if ($book->getUser()->hasRole($privilege) || $this->getUser() == $book->getUser()) {
                    $this->emi->remove($book);
                    $this->emi->flush();
                    return $this->redirect($this->generateUrl('index'));
                }
            }
        }
        return $this->redirect($this->generateUrl('index'));
    }
}