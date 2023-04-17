<?php

namespace models;


use Side;
use models\Wall;
use models\Ball;
use models\Player;
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
        $this->players = [];
        $this->walls = [];
        $this->goals = [];
        $this->createGame($numberOfPlayers);
    }

    private function createGame(int $numberOfPlayers): void
    {
        $speedX = (mt_rand() / mt_getrandmax()) * 2 - 1;
        $speedY = (mt_rand() / mt_getrandmax()) * 2 - 1;
        $this->ball = new Ball(250, 250, 15, $speedX, $speedY);
        $this->createDefaultWalls();
        $pa = [Side::LEFT->playerAttributes(), Side::RIGHT->playerAttributes(), Side::TOP->playerAttributes(), Side::BOTTOM->playerAttributes()];
        $ga = [Side::LEFT->goalAttributes(), Side::RIGHT->goalAttributes(), Side::TOP->goalAttributes(), Side::BOTTOM->goalAttributes()];
        for ($i = 0; $i < $numberOfPlayers; $i++) {
            $player = new Player($pa[$i]->x, $pa[$i]->y, $pa[$i]->width, $pa[$i]->height, 5, 3, $pa[$i]->color, $pa[$i]->name, $pa[$i]->side, $pa[$i]->isYou);
            $this->players[] = $player;
            $this->goals[] = new Goal($ga[$i]->x, $ga[$i]->y, $ga[$i]->width, $ga[$i]->height, $player);
        }
        $this->createWalls(4 - $numberOfPlayers);
    }

    private function createDefaultWalls(): void
    {
        //top left
        $this->walls[] = new Wall(0, 0, Side::TOP, "black");
        $this->walls[] = new Wall(50, 0, Side::TOP, "black");
        $this->walls[] = new Wall(0, 50, Side::LEFT, "black");
        //top right
        $this->walls[] = new Wall(400, 0, Side::TOP, "black");
        $this->walls[] = new Wall(450, 0, Side::TOP, "black");
        $this->walls[] = new Wall(450, 50, Side::RIGHT, "black");
        //bottom left
        $this->walls[] = new Wall(0, 400, Side::LEFT, "black");
        $this->walls[] = new Wall(50, 450, Side::BOTTOM, "black");
        $this->walls[] = new Wall(0, 450, Side::BOTTOM, "black");
        //bottom right
        $this->walls[] = new Wall(450, 450, Side::BOTTOM, "black");
        $this->walls[] = new Wall(450, 400, Side::RIGHT, "black");
        $this->walls[] = new Wall(400, 450, Side::BOTTOM, "black");
    }

    private function createWalls(int $num): void
    {
        // bottom
        if ($num > 0) {
            for ($j = 0; $j < 6; $j++) {
                $this->walls[] = new Wall((2 + $j) * 50, 450, Side::BOTTOM, "blue");
            }
        }
        // top
        if ($num > 1) {
            for ($j = 0; $j < 6; $j++) {
                $this->walls[] = new Wall((2 + $j) * 50, 0, Side::TOP, "yellow");
            }
        }
        // right
        if ($num > 2) {
            for ($j = 0; $j < 6; $j++) {
                $this->walls[] = new Wall(450, (2 + $j) * 50, Side::RIGHT, "red");
            }
        }
        // left
        if ($num > 3) {
            for ($j = 0; $j < 6; $j++) {
                $this->walls[] = new Wall(0, (2 + $j) * 50, Side::LEFT, "black");
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
                $this->ball->increaseSpeed();
                switch ($wall->getSide()) {
                    case Side::BOTTOM:
                        $this->ball->setY($wall->getY() - $this->ball->getRadius());
                        $this->ball->setSpeedY(-$this->ball->getSpeedY());
                        break;
                    case Side::TOP:
                        $this->ball->setY($wall->getY() + $wall->getHeight() + $this->ball->getRadius());
                        $this->ball->setSpeedY(-$this->ball->getSpeedY());
                        break;
                    case Side::RIGHT:
                        $this->ball->setX($wall->getX() - $this->ball->getRadius());
                        $this->ball->setSpeedX(-$this->ball->getSpeedX());
                        break;
                    case Side::LEFT:
                        $this->ball->setX($wall->getX() + $wall->getWidth() + $this->ball->getRadius());
                        $this->ball->setSpeedX(-$this->ball->getSpeedX());
                        break;
                }
            }
        }
        foreach ($this->players as $player) {
            if ($this->ball->checkCollision($player)) {
                $this->ball->increaseSpeed();
                switch ($player->getSide()) {
                    case Side::BOTTOM:
                        $this->ball->setY($player->getY() - $this->ball->getRadius());
                        $this->ball->setSpeedY(-$this->ball->getSpeedY());
                        break;
                    case Side::TOP:
                        $this->ball->setY($player->getY() + $player->getHeight() + $this->ball->getRadius());
                        $this->ball->setSpeedY(-$this->ball->getSpeedY());
                        break;
                    case Side::RIGHT:
                        $this->ball->setX($player->getX() - $this->ball->getRadius());
                        $this->ball->setSpeedX(-$this->ball->getSpeedX());
                        break;
                    case Side::LEFT:
                        $this->ball->setX($player->getX() + $player->getWidth() + $this->ball->getRadius());
                        $this->ball->setSpeedX(-$this->ball->getSpeedX());
                        break;
                }
            }
        }
        foreach ($this->goals as $goal) {
            if ($this->ball->checkCollision($goal)) {
                $this->ball->reset();
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