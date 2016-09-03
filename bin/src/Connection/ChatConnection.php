<?php

namespace Chat\Connection;

use Ratchet\ConnectionInterface;
use Chat\Repository\ChatRepositoryInterface;

class ChatConnection implements ChatConnectionInterface
{

    private $connection;
    private $name;
    private $repository;

    function __construct(ConnectionInterface $conn, ChatRepositoryInterface $repository, $name = "")
    {
        $this->connection = $conn;
        $this->name = $name;
        $this->repository = $repository;
    }

    public function sendMsg($sender, $msg)
    {
        $this->send([
            'action' => 'message',
            'username' => $sender,
            'msg' => $msg
        ]);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function setName($name)
    {
        if ($name === "") {
            return;
        }

        if ($this->repository->getClientByName($name) !== null) {
            $this->send([
                'action' => 'setname',
                'success' => false,
                'username' => $this->name
            ]);
            return;
        }

        $this->name = $name;

        $this->send([
            'action' => 'setname',
            'success' => true,
            'username' => $this->name
        ]);
    }

    public function getName()
    {
        return $this->name;
    }

    private function send(array $data)
    {
        $this->connection->send(json_encode($data));
    }
}
