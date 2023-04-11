<?php

namespace models;

class Wall
{
    private int $width;
    private int $height;

    public function __construct(
        private float $x,
        private float $y,
    )
    {
        $this->width = 50;
        $this->height = 50;
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
        ];
    }


}