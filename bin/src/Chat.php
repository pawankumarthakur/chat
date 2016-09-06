<?php

namespace Chat;

use Chat\Repository\ChatRepository;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $repository;
    protected $userlist;

    public function __construct()
    {
        $this->repository = new ChatRepository;
        $this->userlist = array();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->repository->addClient($conn);

    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {

        $data = $this->parseMessage($msg);
        $currClient = $this->repository->getClientByConnection($conn);

        if ($data->action === "setname") {
            $currClient->setName($data->username);
            $this->userlist[$data->username] = $data->username;
            // echo $this->userlist;
            $this->onMessage($conn, json_encode(['action' => 'connectionupdate', 'usercount' => $this->repository->getClients()->count()]));
            // $currClient->sendCustomMsg($this->userlist, $this->repository->getClients()->count());
        } else if ($data->action === "message") {
            if ($currClient->getName() === "")
            return;

            foreach ($this->repository->getClients() as $client) {
                if ($currClient->getName() !== $client->getName())
                $client->sendMsg($currClient->getName(), $data->msg);
            }

        } else if ($data->action === "connectionupdate") {
            foreach ($this->repository->getClients() as $client) {
                $client->sendCustomMsg($this->userlist, $data->usercount);
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
