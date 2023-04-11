<?php

class PlayerAttributes
{
    public function __construct(
        public string $color,
        public int $x,
        public int $y,
        public int $width,
        public int $height,
        public string $name,
    ) {}
}