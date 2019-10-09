<?php

namespace App\Console\Commands;

use App\WebSockets\Main;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;


class RunWebsocketsServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ws:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $port = 4444;

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Main()
                )
            ),
            $port
        );

        $server->run();





        //
    }
}
