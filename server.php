<?php
/**
 * @var Game $game
 */

use Workerman\Worker;
use Workerman\Lib\Timer;

require_once __DIR__ . '/vendor/autoload.php';


use models\Game;
use models\Ball;


require_once 'models/Game.php';
require_once 'models/Ball.php';
require_once 'models/Wall.php';
require_once 'models/Player.php';
require_once 'models/Side.php';
require_once 'models/Side.php';
require_once 'models/Goal.php';
require_once 'models/PlayerAttributes.php';
require_once 'models/GoalAttributes.php';




$num_players = 0;
$game = new Game($num_players);

function game(): false|string
{
    return $GLOBALS['game']->sentData();
}

function updateBall(): false|string
{
    $GLOBALS['game']->updateBall();
    return game();
}

function updatePlayer($data): false|string
{
    $GLOBALS['game']->updatePlayer($data);
    return game();
}







// Create A Worker and Listens 9000 port, use Websocket protocol
$ws_worker = new Worker("websocket://0.0.0.0:9000");


// 4 processes
$ws_worker->count = 1;

// Add a Timer to Every worker process when the worker process start
$ws_worker->onWorkerStart = function ($ws_worker) {
    $GLOBALS['userdata'] = 0;
    // Timer every 5 seconds
    Timer::add(0.001, function () use ($ws_worker) {
        // Iterate over connections and send the time
        foreach ($ws_worker->connections as $connection) {
            $connection->send(updateBall());
        }
    });


    // Emitted when new connection come
    $ws_worker->onConnect = function ($connection) {
        $GLOBALS['num_players']++;
        $GLOBALS['game'] = new Game($GLOBALS['num_players']);
        // Emitted when websocket handshake done
        $connection->onWebSocketConnect = function ($connection) {
            $connection->send(game());
        };
    };

    $ws_worker->onMessage = function ($connection, $data) {
        $GLOBALS['userdata'] = $data;
        // Send hello $data
        $connection->send(updatePlayer($data));
    };

    // Emitted when connection closed
    $ws_worker->onClose = function ($connection) {
        $GLOBALS['num_players']--;
        $GLOBALS['game'] = new Game($GLOBALS['num_players']);
        $connection->send(game());
    };
};
// Run worker
Worker::runAll();
