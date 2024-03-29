<?php

namespace models;

use Side;

class Wall
{
    private int $width;
    private int $height;


    public function __construct(
        private float $x,
        private float $y,
        private Side $side,
        private string $color,
        private ?Player $player = null,
        private bool $on = true,
    )
    {
        $this->width = 50;
        $this->height = 50;
    }

    /**
     * @return bool
     */
    public function isOn(): bool
    {
        return $this->on;
    }

    /**
     * @param bool $on
     */
    public function setOn(bool $on): void
    {
        $this->on = $on;
    }


    /**
     * @return Side
     */
    public function getSide(): Side
    {
        return $this->side;
    }

    /**
     * @param Side $side
     */
    public function setSide(Side $side): void
    {
        $this->side = $side;
    }

    /**
     * @return Player|null
     */
    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    /**
     * @param Player|null $player
     */
    public function setPlayer(?Player $player): void
    {
        $this->player = $player;
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

    public function draw() : string
    {
        return "<div class='wall' style='background-color: black; width: {$this->width}px; height: {$this->height}px; position: absolute; top: {$this->y}px; left: {$this->x}px;'></div>";
    }

    public function toArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'width' => $this->width,
            'height' => $this->height,
            'color' => $this->color,
            'on' => $this->on,
        ];
    }


}