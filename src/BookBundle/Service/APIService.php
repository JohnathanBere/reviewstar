<?php

namespace BookBundle\Service;

use GuzzleHttp\Client;

class APIService
{
    private $nytApiKey = "76e0955f7cea48e3b49ce3a2ff273636";
    public function getGoogleBookClient(): Client {
        return new Client(["base_uri" => "https://www.googleapis.com/books/v1/"]);
    }

    public function getNYTClient(): Client {
        return new Client(["base_uri" => "https://api.nytimes.com/svc/books/v3/"]);
    }

    public function getBestSellersFromNYTApi() {
        $responseBody = $this
            ->getNYTClient()
            ->get("lists/best-sellers/history.json?api-key=".$this->nytApiKey)
            ->getBody();

        $responseData = \GuzzleHttp\json_decode($responseBody);

        return $responseData->results;
    }

    public function getVolumeInfoFromFirstItemFromGoogleApi($bookName) {
        $parsedQuery = str_replace("%20" || " ", "+", $bookName);
        $responseBody = $this->getGoogleBookClient()->get("volumes?q=" . $parsedQuery)->getBody();
        $responseData = \GuzzleHttp\json_decode($responseBody);

        return $responseData->items[0]->volumeInfo;
    }

    public function getBooksFromGoogleApi($bookName) {
        $parsedQuery = str_replace("%20" || " ", "+", $bookName);
        $queryWOutChar = preg_replace('/[^A-Za-z0-9 \-]/', '', $parsedQuery);
        $responseBody = $this->getGoogleBookClient()->get("volumes?q=title=" . $queryWOutChar)->getBody();
        $responseData = \GuzzleHttp\json_decode($responseBody);

        return $responseData->items;
    }

    public function getSingleBookByNameAndAuthor($bookName, $bookAuthor) {
        $parsedBookName = str_replace("%20" || " ", "+", $bookName);
        $bookNameWOutChar = preg_replace('/[^A-Za-z0-9 \-]/', '', $parsedBookName);
        $parsedBookAuthor = str_replace("%20" || " ", "+", $bookName);
        $authorWOutChar = preg_replace('/[^A-Za-z0-9 \-]/', '', $parsedBookAuthor);
        $responseBody = $this->getGoogleBookClient()->get("volumes?q=title=".$bookNameWOutChar."+author=".$authorWOutChar)->getBody();
        $responseData = \GuzzleHttp\json_decode($responseBody);

        return $responseData->items[0];
    }

    public function getSingleBookFromGoogleApi($id) {
        $responseBody = $this->getGoogleBookClient()->get("volumes?q=id=".$id)->getBody();
        $responseData = \GuzzleHttp\json_decode($responseBody);

        return $responseData->items[0];
    }
}