<?php
/**
 * Created by PhpStorm.
 * User: John Bere
 * Date: 04/04/2018
 * Time: 20:24
 */

namespace BookBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GuzzleHttp\Client;

class JoindinController extends Controller
{
    private $events;
    private $client;

    public function hydrate()
    {
        $client = new Client(["base_uri" => "https://api.joind.in/v2.1/"]);
        $response = $client->get('events?filter=past');
        $respBody = $response->getBody();
        $results = \GuzzleHttp\json_decode($respBody);
        $this->events = $results->events;
        $this->client = $client;
    }

    public function __construct(/*Client $client*/)
    {
        // $this->client = $client;
        $this->hydrate();
    }

    public function eventsAction()
    {
        return $this->render("BookBundle:Joindin:events.html.twig", ["events" => $this->events]);
    }

    public function eventAction($index)
    {
        $event = $this->events[$index - 1];
        return $this->render("BookBundle:Joindin:event.html.twig", ["event" => $event]);
    }

    public function talksAction($index)
    {
        $event = $this->events[$index - 1];

        $parsedUrl = parse_url($event->uri);
        $tmp = explode("/", $parsedUrl["path"]);
        $id = end($tmp);
        $talks = [];


        if ($event->talks_count > 0) {
            $talks = $this->getTalksForEvent($id);
        }

        return $this->render("BookBundle:Joindin:talks.html.twig", ["talks" => $talks]);
    }

    public function getTalksForEvent($eventId)
    {
        $response = $this->client->get("events/" . $eventId . "/talks");
        $respBody = $response->getBody();
        $results = \GuzzleHttp\json_decode($respBody);

        return $results->talks;
    }
}