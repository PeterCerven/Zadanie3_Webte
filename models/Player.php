<?php

namespace models;

use Side;

class Player
{
    private int $upperBound;
    private int $lowerBound;
    private bool $alive;

    public function __construct(
        private float  $x,
        private float  $y,
        private float  $width,
        private float  $height,
        private int    $lives,
        private string $color,
        private string $name,
        private Side   $side,
        private bool   $isYou,
    )
    {
        $this->upperBound = 350;
        $this->lowerBound = 150;
        $this->alive = false;
    }


    /**
     * @return int
     */
    public function getUpperBound(): int
    {
        return $this->upperBound;
    }

    /**
     * @return bool
     */
    public function isAlive(): bool
    {
        return $this->alive;
    }

    /**
     * @param bool $alive
     */
    public function setAlive(bool $alive): void
    {
        $this->alive = $alive;
    }

    /**
     * @return int
     */
    public function getLowerBound(): int
    {
        return $this->lowerBound;
    }

    /**
     * @return bool
     */
    public function isYou(): bool
    {
        return $this->isYou;
    }

    /**
     * @param bool $isYou
     */
    public function setIsYou(bool $isYou): void
    {
        $this->isYou = $isYou;
    }

    /**
     * @return Side
     */
    public function getSide(): Side
    {
        return $this->side;
    }


    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }


    public function getLives(): int
    {
        return $this->lives;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setX(float $x): void
    {
        if ($this->side == Side::BOTTOM || $this->side == Side::TOP) {
            if ($x < $this->lowerBound) {
                $x = $this->lowerBound;
            } elseif ($x > $this->upperBound) {
                $x = $this->upperBound;
            }
        }
        $this->x = $x;
    }

    public function setY(float $y): void
    {
        if ($this->side == Side::LEFT || $this->side == Side::RIGHT) {
            if ($y < $this->lowerBound) {
                $y = $this->lowerBound;
            } elseif ($y > $this->upperBound) {
                $y = $this->upperBound;
            }
        }
        $this->y = $y;
    }

    public function setWidth(float $width): void
    {
        $this->width = $width;
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
    }


    public function setLives(int $lives): void
    {
        $this->lives = $lives;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function loseLife(): void
    {
        $this->lives--;
    }

    public function isDead(): bool
    {
        return $this->lives <= 0;
    }


    public function toArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'width' => $this->width,
            'height' => $this->height,
            'lives' => $this->lives,
            'color' => $this->color,
            'name' => $this->name,
            'isYou' => $this->isYou,
            'side' => $this->side->name,
            'alive' => $this->alive,
        ];
    }


}