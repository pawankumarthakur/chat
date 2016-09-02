<?php

namespace Chat\Repository;

use Ratchet\ConnectionInterface;
use SplObjectStorage;
use Chat\Connection\ChatConnection;

/**
 *
 */
class ChatRepository implements ChatRepositoryInterface
{

    private $clients;

    function __construct()
    {
        $this->clients = new SplObjectStorage;
    }

    public function getClientByName($name)
    {
        foreach ($this->clients as $client) {
            if ($client->getName() === $name) {
                return $client;
            }

            return null;
        }
    }

    public function getClientByConnection(ConnectionInterface $conn)
    {
        foreach ($this->clients as $client) {
            if ($client->getConnection() === conn) {
                return $client;
            }

            return null;
        }
    }

    public function addClient(ConnectionInterface $conn)
    {
        $this->clients->attach(new ChatConnection($conn, $this));
    }

    public function removeClient(ConnectionInterface $conn)
    {
        $client = $this->getClientByConnection($conn);

        if ($client !== null) {
            $this->clients->detach($client);
        }
    }

    public function getClients()
    {
        return $this->clients;
    }
}
