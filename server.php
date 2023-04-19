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


$gameStarted = false;
$active_players = 0;
$game = new Game($active_players);

function game(): false|string
{
    return $GLOBALS['game']->sentData();
}

function newGame() : false|string
{
    Timer::del($GLOBALS['timer']);
    $GLOBALS['gameStarted'] = false;
    $GLOBALS['active_players'] = 0;
    $GLOBALS['game'] = new Game($GLOBALS['active_players']);
    return game();
}

function updateBall(): false|string
{
    $GLOBALS['game']->updateBall();
    if (!$GLOBALS['game']->isGameOver()) {
        return game();
    } else {
        return newGame();
    }
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

function timer(): void
{
    $GLOBALS['timer'] = Timer::add(0.01, function () {
        $ballData = updateBall();
        foreach ($GLOBALS['ws_worker']->connections as $connection) {
            $connection->send($ballData);
        }
    });
}

// Add a Timer to Every worker process when the worker process start
$ws_worker->onWorkerStart = function ($ws_worker) {
    $ws_worker->onMessage = function ($connection, $data) {
        $data = json_decode($data, true);
        switch ($data['message']) {
            case 'start':
                if (!$data['admin']) {
                    break;
                }
                $GLOBALS['gameStarted'] = true;
                $GLOBALS['game']->setGameStarted(true);
                foreach ($GLOBALS['ws_worker']->connections as $connection) {
                    $connection->send(game());
                }
                timer();
                break;
            case 'stop':
                $GLOBALS['gameStarted'] = false;
                $GLOBALS['active_players'] = 0;
                $GLOBALS['game'] = new Game($GLOBALS['active_players']);
                foreach ($GLOBALS['ws_worker']->connections as $connection) {
                    $connection->send(game());
                }
                foreach ($GLOBALS['ws_worker']->connections as $connection) {
                    $connection->send(json_encode(['message' => 'reset']));
                }
                Timer::del($GLOBALS['timer']);
                break;
            case 'join':
                $GLOBALS['active_players']++;
                $GLOBALS['game'] = new Game($GLOBALS['active_players']);
                $GLOBALS['game']->getLastPlayer()->setNickname($data['name']);
                $side = $GLOBALS['game']->getLastPlayer()->getSide();
                foreach ($GLOBALS['ws_worker']->connections as $connection2) {
                    $connection2->send(game());
                }
                $admin = false;
                if ($GLOBALS['active_players'] === 1) {
                    $admin = true;
                }
                $connection->send(json_encode(['message' => 'player', 'side' => $side, 'isAdmin' => $admin, 'plName' => $data['name']]));

                break;
            case 'reset':
                $GLOBALS['gameStarted'] = false;
                $GLOBALS['game'] = new Game($GLOBALS['active_players']);
                break;
            case 'rageQuit':
                $GLOBALS['active_players']--;
                $side = $data['playerSide'];
                $GLOBALS['game']->getPlayer($side)->setAlive(false);
                $GLOBALS['game']->wallPlayer();
                if ($GLOBALS['game']->isGameOver()) {
                    foreach ($GLOBALS['ws_worker']->connections as $connection) {
                        $connection->send(newGame());
                    }
                } else {
                    foreach ($GLOBALS['ws_worker']->connections as $connection) {
                        $connection->send(game());
                    }
                }
                break;
            case 'update':
                foreach ($GLOBALS['ws_worker']->connections as $connection) {
                    $connection->send(updatePlayer($data));
                }
                break;
        }
    };


    // Emitted when new connection come
    $ws_worker->onConnect = function ($connection) {
        // Emitted when websocket handshake done
        $connection->onWebSocketConnect = function ($connection) {
            $connection->send(game());
        };
    };

    // Emitted when connection closed
    $ws_worker->onClose = function ($connection) {
//        $GLOBALS['num_players']--;
//        $GLOBALS['game'] = new Game($GLOBALS['num_players']);
//        $connection->send(game());
    };
};
// Run worker
Worker::runAll();
