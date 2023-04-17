<?php

namespace models;

use Side;

class Player
{
    public function __construct(
        private float  $x,
        private float  $y,
        private float  $width,
        private float  $height,
        private float  $speed,
        private int    $lives,
        private string $color,
        private string $name,
        private Side   $side,
        private bool   $isYou,
    )
    {
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

    public function getSpeed(): float
    {
        return $this->speed;
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
        $this->x = $x;
    }

    public function setY(float $y): void
    {
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

    public function setSpeed(float $speed): void
    {
        $this->speed = $speed;
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

    public function moveUp(): void
    {
        $this->y -= $this->speed;
    }

    public function moveDown(): void
    {
        $this->y += $this->speed;
    }

    public function moveLeft(): void
    {
        $this->x -= $this->speed;
    }

    public function moveRight(): void
    {
        $this->x += $this->speed;
    }

    public function loseLife(): void
    {
        $this->lives--;
    }

    public function isDead(): bool
    {
        return $this->lives <= 0;
    }

    public function draw(): string
    {
        return sprintf('<div class="player" style="left: %dpx; top: %dpx; width: %dpx; height: %dpx; background-color: %s;"></div>', $this->x, $this->y, $this->width, $this->height, $this->color);
    }

    public function toArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'width' => $this->width,
            'height' => $this->height,
            'speed' => $this->speed,
            'lives' => $this->lives,
            'color' => $this->color,
            'name' => $this->name,
            'isYou' => $this->isYou,
        ];
    }


}