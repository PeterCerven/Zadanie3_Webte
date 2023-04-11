<?php

namespace models;

class Ball
{


    public function __construct(
        private float $x,
        private float $y,
        private int $radius,
        private float $speedX,
        private float $speedY,
    ) {}

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getRadius(): int
    {
        return $this->radius;
    }

    public function getSpeedX(): float
    {
        return $this->speedX;
    }

    public function getSpeedY(): float
    {
        return $this->speedY;
    }

    public function setX(float $x): void
    {
        $this->x = $x;
    }

    public function setY(float $y): void
    {
        $this->y = $y;
    }

    public function setRadius(int $radius): void
    {
        $this->radius = $radius;
    }

    public function setSpeedX(float $speedX): void
    {
        $this->speedX = $speedX;
    }

    public function setSpeedY(float $speedY): void
    {
        $this->speedY = $speedY;
    }

    public function move(): void
    {
        $this->x += $this->speedX;
        $this->y += $this->speedY;
    }

    public function increaseSpeed(): void
    {
        $this->speedX *= 1.1;
        $this->speedY *= 1.1;
    }

    public function decreaseSpeed(): void
    {
        $this->speedX *= 0.9;
        $this->speedY *= 0.9;
    }

    public function resetSpeed(): void
    {
        $this->speedX = 5;
        $this->speedY = 5;
    }

    public function resetPosition(): void
    {
        $this->x = 400;
        $this->y = 300;
    }

    public function draw() :string
    {
        return "<div class='ball' style='position: absolute; top: {$this->y}px; left: {$this->x}px;'></div>";
    }




    public function checkCollision(mixed $object) : bool
    {
        $objectX = $object->getX();
        $objectY = $object->getY();
        $objectWidth = $object->getWidth();
        $objectHeight = $object->getHeight();
        $ballX = $this->x;
        $ballY = $this->y;
        $ballRadius = $this->radius;

        // Calculate the closest point on the object to the ball
        $closestX = $this->clamp($ballX, $objectX, $objectX + $objectWidth);
        $closestY = $this->clamp($ballY, $objectY, $objectY + $objectHeight);

        // Calculate the distance between the closest point and the ball's center
        $distanceX = $ballX - $closestX;
        $distanceY = $ballY - $closestY;

        // Calculate the squared distance
        $squaredDistance = ($distanceX * $distanceX) + ($distanceY * $distanceY);

        // Check if the squared distance is less than or equal to the squared radius of the ball
        return $squaredDistance <= ($ballRadius * $ballRadius);
    }

    public function clamp($value, $min, $max): float{
        return min(max($value, $min), $max);
    }



    public function toArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'radius' => $this->radius,
            'speedX' => $this->speedX,
            'speedY' => $this->speedY,
        ];
    }


}