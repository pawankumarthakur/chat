<?php

namespace Chat;

use Chat\Repository\ChatRepository;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $repository;
//    protected $usercount;

    public function __construct()
    {
        $this->repository = new ChatRepository;
//        $this->usercount = 0;
    }

    public function onOpen(ConnectionInterface $conn)
    {
//        $this->usercount =+ 1;
        $this->repository->addClient($conn);
        $this->onMessage($conn, json_encode(['action' => 'connectionupdate', 'connection' => $this->repository->getClients()->count()]));
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {

        $data = $this->parseMessage($msg);
        $currClient = $this->repository->getClientByConnection($conn);

        if ($data->action === "setname") {
            $currClient->setName($data->username);
//            foreach ($this->repository->getClients() as $client) {
//                $client->sendCustomMsg($this->usercount);
//            }
        } else if ($data->action === "message") {
            if ($currClient->getName() === "")
                return;

            foreach ($this->repository->getClients() as $client) {
                if ($currClient->getName() !== $client->getName())
                    $client->sendMsg($currClient->getName(), $data->msg);
            }

        } else if ($data->action === "connectionupdate") {
            foreach ($this->repository->getClients() as $client) {
                $client->sendCustomMsg($data->connection);
            }
        }
    }


    public function onClose(ConnectionInterface $conn)
    {
        $this->repository->removeClient($conn);
        $this->onMessage($conn, json_encode(['action' => 'connectionupdate', 'connection' => $this->repository->getClients()->count()]));
    }

    private function parseMessage($msg)
    {
        return json_decode($msg);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "The following error occured" . $e->getMessage();
        $client = $this->repository->getClientByConnection($conn);
        if ($client !== null) {
            $client->getConnection()->close();
            $this->repository->removeClient($conn);
        }
    }

}
