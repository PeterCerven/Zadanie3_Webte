<?php

namespace models;


use models\Ball;
use models\Player;
use models\Wall;
use Side;
use ArrayObject;
use stdClass;


class Game
{
    private array $players;
    private Ball $ball;
    private array $walls;
    private array $goals;

    public function __construct(int $numberOfPlayers)
    {
        $this->createGame($numberOfPlayers);
    }

    private function createGame(int $numberOfPlayers): void
    {
        $speedX = (mt_rand() / mt_getrandmax()) * 2 - 1;
        $speedY = (mt_rand() / mt_getrandmax()) * 2 - 1;
        $this->ball = new Ball(250, 250, 30, $speedX, $speedY);
        $this->createDefaultWalls();
        $pa = [Side::LEFT->playerAttributes(), Side::RIGHT->playerAttributes(), Side::TOP->playerAttributes(), Side::BOTTOM->playerAttributes()];
        $ga = [Side::LEFT->goalAttributes(), Side::RIGHT->goalAttributes(), Side::TOP->goalAttributes(), Side::BOTTOM->goalAttributes()];
        for ($i = 0; $i < $numberOfPlayers; $i++) {
            $player = new Player($pa[$i]->x, $pa[$i]->y, $pa[$i]->width, $pa[$i]->height, 5, 3, $pa[$i]->color, $pa[$i]->name);
            $this->players[] = $player;
            $this->goals[] = new Goal($ga[$i]->x, $ga[$i]->y, $ga[$i]->width, $ga[$i]->height, $player);
        }
        $this->createWalls(4 - $numberOfPlayers);
    }

    private function createDefaultWalls(): void
    {
        //top left
        $this->walls[] = new Wall(0, 0);
        $this->walls[] = new Wall(50, 0);
        $this->walls[] = new Wall(0, 50);
        //top right
        $this->walls[] = new Wall(400, 0);
        $this->walls[] = new Wall(450, 0);
        $this->walls[] = new Wall(450, 50);
        //bottom left
        $this->walls[] = new Wall(0, 400);
        $this->walls[] = new Wall(50, 450);
        $this->walls[] = new Wall(0, 450);
        //bottom right
        $this->walls[] = new Wall(450, 450);
        $this->walls[] = new Wall(450, 400);
        $this->walls[] = new Wall(400, 450);
    }

    private function createWalls(int $num): void
    {
        // bottom
        if ($num > 0) {
            for ($j = 0; $j < 6; $j++) {
                $this->walls[] = new Wall((2 + $j) * 50, 450);
            }
        }
        // top
        if ($num > 1) {
            for ($j = 0; $j < 6; $j++) {
                $this->walls[] = new Wall((2 + $j) * 50, 0);
            }
        }
        // right
        if ($num > 2) {
            for ($j = 0; $j < 6; $j++) {
                $this->walls[] = new Wall(450, (2 + $j) * 50);
            }
        }
        // left
        if ($num > 3) {
            for ($j = 0; $j < 6; $j++) {
                $this->walls[] = new Wall(0, (2 + $j) * 50);
            }
        }
    }

    public function sentData(): string
    {
        return json_encode($this->toArray());
    }

    private function checkCollision(): void
    {

        foreach ($this->walls as $wall) {
            if ($this->ball->checkCollision($wall)) {
                $this->ball->setSpeedX(-$this->ball->getSpeedX());
                $this->ball->setSpeedY(-$this->ball->getSpeedY());
            }
        }
        foreach ($this->players as $player) {
            if ($this->ball->checkCollision($player)) {
                $this->ball->setSpeedX(-$this->ball->getSpeedX());
                $this->ball->setSpeedY(-$this->ball->getSpeedY());
            }
        }
        foreach ($this->goals as $goal) {
            if ($this->ball->checkCollision($goal)) {
                $player = $goal->getPlayer();
                $player->loseLife();
                if ($player->getLives() == 0) {
                    echo $player->getName() . ' has lost the game!';
                }
                return;
            }
        }
    }


    public function updateBall(): void
    {
        $this->ball->move();
        $this->checkCollision();
    }

    public function toArray(): array
    {
        return [
            'players' => array_map(fn($player) => $player->toArray(), $this->players),
            'ball' => $this->ball->toArray(),
            'walls' => array_map(fn($wall) => $wall->toArray(), $this->walls),
            'goals' => array_map(fn($goal) => $goal->toArray(), $this->goals),
        ];
    }


}