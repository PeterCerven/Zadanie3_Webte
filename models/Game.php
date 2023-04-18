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
    private bool $gameStarted;
    private int $score;

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    public function __construct(int $numberOfPlayers)
    {
        $this->players = [];
        $this->walls = [];
        $this->goals = [];
        $this->createGame($numberOfPlayers);
        $this->gameStarted = false;
        $this->score = 0;
    }

    private function createGame(int $numberOfPlayers): void
    {
        $speedX = rand(-5, 5);
        $speedY = rand(-5, 5);
        $this->ball = new Ball(300, 300, 15, $speedX, $speedY);
        $this->createDefaultWalls();
        $pa = [Side::LEFT->playerAttributes(), Side::RIGHT->playerAttributes(), Side::TOP->playerAttributes(), Side::BOTTOM->playerAttributes()];
        $ga = [Side::LEFT->goalAttributes(), Side::RIGHT->goalAttributes(), Side::TOP->goalAttributes(), Side::BOTTOM->goalAttributes()];
        for ($i = 0; $i < 4; $i++) {
            $player = new Player($pa[$i]->x, $pa[$i]->y, $pa[$i]->width, $pa[$i]->height, 3, $pa[$i]->color, $pa[$i]->name, $pa[$i]->side, $pa[$i]->isYou);
            if ($numberOfPlayers > 0) {
                $player->setAlive(true);
                $numberOfPlayers--;
            }
            $this->players[] = $player;
            $this->goals[] = new Goal($ga[$i]->x, $ga[$i]->y, $ga[$i]->width, $ga[$i]->height, $player);
        }
        $this->wallPlayer();
    }

    /**
     * @return bool
     */
    public function isGameStarted(): bool
    {
        return $this->gameStarted;
    }

    /**
     * @param bool $gameStarted
     */
    public function setGameStarted(bool $gameStarted): void
    {
        $this->gameStarted = $gameStarted;
    }

    private function createDefaultWalls(): void
    {
        //top left
        $this->walls[] = new Wall(50, 50, Side::TOP, "black");
        $this->walls[] = new Wall(100, 50, Side::TOP, "black");
        $this->walls[] = new Wall(50, 100, Side::LEFT, "black");
        //top right
        $this->walls[] = new Wall(450, 50, Side::TOP, "black");
        $this->walls[] = new Wall(500, 50, Side::TOP, "black");
        $this->walls[] = new Wall(500, 100, Side::RIGHT, "black");
        //bottom left
        $this->walls[] = new Wall(50, 450, Side::LEFT, "black");
        $this->walls[] = new Wall(100, 500, Side::BOTTOM, "black");
        $this->walls[] = new Wall(50, 500, Side::BOTTOM, "black");
        //bottom right
        $this->walls[] = new Wall(500, 500, Side::BOTTOM, "black");
        $this->walls[] = new Wall(500, 450, Side::RIGHT, "black");
        $this->walls[] = new Wall(450, 500, Side::BOTTOM, "black");
    }

    private function wallPlayer() : void {
        $this->walls = [];
        foreach ($this->players as $player) {
            if (!$player->isAlive()) {
                switch ($player->getSide()) {
                    case Side::LEFT:
                        for ($j = 0; $j < 6; $j++) {
                            $this->walls[] = new Wall(50, (3 + $j) * 50, Side::LEFT, "green", $player);
                        }
                        break;
                    case Side::RIGHT:
                        for ($j = 0; $j < 6; $j++) {
                            $this->walls[] = new Wall(500, (3 + $j) * 50, Side::RIGHT, "yellow", $player);
                        }
                        break;
                    case Side::TOP:
                        for ($j = 0; $j < 6; $j++) {
                            $this->walls[] = new Wall((3 + $j) * 50, 50, Side::TOP, "red", $player);
                        }
                        break;
                    case Side::BOTTOM:
                        for ($j = 0; $j < 6; $j++) {
                            $this->walls[] = new Wall((3 + $j) * 50, 500, Side::BOTTOM, "blue", $player);
                        }
                        break;
                }
            }
        }
        $this->createDefaultWalls();
    }

    public function sentData(): string
    {
        return json_encode($this->toArray());
    }

    public function getLastPlayer()
    {
        $currPlayer = null;
        foreach ($this->players as $player) {
            if ($player->isAlive()) {
                $currPlayer = $player;
            }
        }
        return $currPlayer;
    }

    private function checkBounce(mixed $objects): void
    {
        foreach ($objects as $object) {
            if ($this->ball->checkCollision($object)) {
                $this->ball->increaseSpeed();
                $this->score++;
                switch ($object->getSide()) {
                    case Side::BOTTOM:
                        $this->ball->setY($object->getY() - $this->ball->getRadius() - 1);
                        $this->ball->setSpeedY(-$this->ball->getSpeedY());
                        break;
                    case Side::TOP:
                        $this->ball->setY($object->getY() + $object->getHeight() + $this->ball->getRadius() + 1);
                        $this->ball->setSpeedY(-$this->ball->getSpeedY());
                        break;
                    case Side::RIGHT:
                        $this->ball->setX($object->getX() - $this->ball->getRadius() - 1);
                        $this->ball->setSpeedX(-$this->ball->getSpeedX());
                        break;
                    case Side::LEFT:
                        $this->ball->setX($object->getX() + $object->getWidth() + $this->ball->getRadius() + 1);
                        $this->ball->setSpeedX(-$this->ball->getSpeedX());
                        break;
                }
            }
        }
    }

    private function checkCollision(): void
    {
        $this->checkBounce($this->walls);
        $this->checkBounce($this->players);
        foreach ($this->goals as $goal) {
            if ($this->ball->checkCollision($goal)) {
                $this->ball->reset();
                $player = $goal->getPlayer();
                $player->loseLife();
                if ($player->getLives() == 0) {
                    $player->setAlive(false);
                    $this->wallPlayer();
                }
            }
        }

    }


    public function updateBall(): void
    {
        $this->ball->move();
        $this->checkCollision();
    }

    public function updatePlayer($data): void
    {
        $player = $this->getPlayerWithSide($data['playerSide']);
        $player->setX($player->getX() + $data['x']);
        $player->setY($player->getY() + $data['y']);
    }

    private function getPlayerWithSide($side): ?Player
    {
        foreach ($this->players as $player) {
            if ($player->getSide()->name == $side) {
                return $player;
            }
        }
        return null;
    }

    public function toArray(): array
    {
        return [
            'players' => array_map(fn($player) => $player->toArray(), $this->players),
            'ball' => $this->ball->toArray(),
            'walls' => array_map(fn($wall) => $wall->toArray(), $this->walls),
            'goals' => array_map(fn($goal) => $goal->toArray(), $this->goals),
            'gameStarted' => $this->gameStarted,
            'score' => $this->score
        ];
    }


}