<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function Ratchet\Client\connect;

class WebsocketAPIController extends Controller
{
    public function emit(Request $request) {
        connect('ws://localhost:8085/chat')->then(function($conn) {
            $conn->on('message', function($msg) use ($conn) {
                Log::debug("Message has been received.");
                Log::debug($msg);
                $conn->close();
            });
            $conn->send('Hello to everyone!');
            $conn->close();
        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });
    }
    public function enableMaintenance_api(Request $request) {
        Log::debug("Trying to emit a message to the broadcaster.");

        connect('ws://localhost:8085/broadcast')->then(function($conn) {
            $conn->on('message', function($msg) use ($conn) {
                Log::debug("Message has been received.");
                Log::debug($msg);
                $conn->close();
            });
            $conn->send(json_encode(["type" => "maintenance", "message" => "In Kürze verbessern wir Abalo für Sie! Nach einer kurzen Pause sind wir wieder für Sie da! Versprochen."]));
            $conn->close();
        }, function ($e) {
            Log::debug("Couldn't emit to the websocket.");
            Log::debug($e->getMessage());
        });

        return response()->json(["message" => "Maintenance mode has been enabled."], 200);
    }
}

