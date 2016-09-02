<?php

namespace Chat;

use Chat\Repository\ChatRepository;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Exception;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
  protected $repository;

  public function __construct()
  {
      $this->repository = new ChatRepository;
  }

  public function onOpen(ConnectionInterface $conn)
  {
      $this->repository->addClient($conn);
  }

  public function onClose(ConnectionInterface $conn)
  {
      $this->repository->removeClient($conn);
  }

  public function onMessage(ConnectionInterface $conn, $msg)
  {
      $data = $this->parseMessage($msg);
      $currClient = $this->repository->getClientByConnection($conn);

      if ($data->action == "setname") {
          $currClient->setName($data->username);
      } else if($data->action == "message") {
          if ($currClient->getName() === "") {
              return;
          }

          foreach ($this->repository->getClients() as $client) {
              if ($currClient->getName() !== $client->getName()) {
                  $client->sendMsg($currClient->getName(), $data->msg);
              }
          }

      }
  }

  private function parseMessage($msg)
  {
      return json_decode($msg);
  }

  public function onError(ConnectionInterface $conn, Exception $e)
  {
      echo "The following error occured".$e->getMessage();
      $client = $this->repository->getClientByConnection($conn);
      if ($client !== null) {
          $client->getConnection()->close();
          $this->repository->removeClient($conn);
      }
  }

}
