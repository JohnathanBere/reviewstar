<?php
namespace BookBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OAuthAddClientCommand extends ContainerAwareCommand
{
    /**
     * We outchea creating our own commands boyyssss.
     */
    protected function configure()
    {
        $this
            ->setName("oauth:add-client")
            ->setDescription("Adds a new client for OAuth");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // we are getting the uri from the current route using a service
        $redirectUri = $this->getContainer()->getParameter("router.request_context.scheme") . "://" . $this->getContainer()->getParameter("router.request_context.host");
        // gets the client manager from fos auth server
        $clientManager = $this->getContainer()->get("fos_oauth_server.client_manager.default");
        // we create a client here.
        $client = $clientManager->createClient();
        // we set the redirect uri for that client.
        $client->setRedirectUris(array($redirectUri));
        // we now set the grant type for that client.
        $client->setAllowedGrantTypes(array("refresh_token", "password"));
        // $client->setAllowedGrantTypes(array("token", "authorization_code"));
        // we now update the client accordingly,
        $clientManager->updateClient($client);
    }
}