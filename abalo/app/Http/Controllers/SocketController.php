<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Ratchet\MessageComponentInterface;

use Ratchet\ConnectionInterface;

class SocketController extends Controller implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $querystring = $conn->httpRequest->getUri()->getQuery();
        parse_str($querystring,$queryarray);


        $this->clients[$conn->resourceId] = ["email" => $queryarray["email"], "conn" => $conn];
        echo "New connection! ({$conn->resourceId}, {$queryarray["email"]})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        echo "OnMessage\n";
        $msg_parsed = json_decode($msg, true);
        
        if(isset($msg_parsed["email"]) === true) {
            echo "Requesting to an email: ";
            echo $msg_parsed["email"];
            echo "\n";
            $conn = null;

            // search for the requested email
            foreach($this->clients as $client) {
                echo $msg_parsed["email"];
                echo " === ";
                echo $client["email"];
                echo "\n";
                if($msg_parsed["email"] == $client["email"]) {
                    echo "found the email\n";
                    $conn = $client["conn"];
                    break;
                }
            }

            if(empty($conn) === false) {
                echo "Sending to {$conn->resourceId}";
                $conn->send($msg);
            }

        } else {
            foreach ($this->clients as $client) {
                if ($from !== $client["conn"]) {
                    // The sender is not the receiver, send to each client connected
                    $client["conn"]->send($msg);
                }
            }
        }


    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        echo "onClose\n";
        $email = $this->clients[$conn->resourceId]["email"];
        unset($this->clients[$conn->resourceId]);

        echo "Connection {$conn->resourceId} ({$email}) has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

