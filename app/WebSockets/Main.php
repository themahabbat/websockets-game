<?php

namespace App\WebSockets;

use App\User;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Main implements MessageComponentInterface
{

    protected $clients;
    protected $users;
    protected $velocity;

    protected $canvasW = 500;
    protected $canvasH = 500;

    public function __construct()
    {
        $this->clients = [];
        $this->velocity = 50;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients[$conn->resourceId] = $conn;

        $this->users[$conn->resourceId] = [
            'id' => $conn->resourceId,
            'x' => rand(50, 450),
            'y' => rand(50, 450)
        ];

        $this->emit_all([
            'event' => 'users',
            'users' => $this->users
        ]);


        echo "{$conn->resourceId} connected\n";
    }

    public function onMessage(ConnectionInterface $sender, $data)
    {
        $data = json_decode($data);

        $event = $data->event;

        if ($event === 'movement') {

            $to = $data->to;

            $me = $this->users[$sender->resourceId];

            $x = $me['x'];
            $y = $me['y'];

            if ($to === 'ArrowUp') $y = $y - $this->velocity;
            else if ($to === 'ArrowDown') $y = $y + $this->velocity;
            else if ($to === 'ArrowLeft') $x = $x - $this->velocity;
            else if ($to === 'ArrowRight') $x = $x + $this->velocity;

            $minX = 0;
            $maxX = $this->canvasW - 50;

            $minY = 0;
            $maxY = $this->canvasH - 50;

            if ($x < $minX) $x = $minX;
            else if ($x > $maxX) $x = $maxX;
            else if ($y < $minY) $y = $minY;
            else if ($y > $maxY) $y = $maxY;

            $this->users[$sender->resourceId]['x'] = $x;
            $this->users[$sender->resourceId]['y'] = $y;

            $this->emit_all([
                'event' => 'users',
                'users' => $this->users
            ]);



            //
        } else if ($event === 'name') {
            $this->users[$sender->resourceId]['name'] = $data->name;
            $this->emit_all([
                'event' => 'users',
                'users' => $this->users
            ]);
        }

        //
    }

    public function onClose(ConnectionInterface $conn)
    {
        unset($this->clients[$conn->resourceId]);
        unset($this->users[$conn->resourceId]);

        echo "{$conn->resourceId}  disconnected\n";

        //
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();

        //
    }

    protected function emit_all($data, $encodeJson = true)
    {
        if ($encodeJson) $data = json_encode($data);

        foreach ($this->clients as $client) $client->send($data);

        //
    }

    protected function emit_except($data, array $exceptions, $encodeJson = true)
    {

        if ($encodeJson) $data = json_encode($data);

        foreach ($this->clients as $client) {

            $check = !in_array($client->resourceId, $exceptions);

            if ($check) $client->send($data);

            //
        }

        //
    }

    protected function emit($to, $data, $encodeJson = true)
    {
        if ($encodeJson) $data = json_encode($data);

        $to->send($data);

        //
    }
}
