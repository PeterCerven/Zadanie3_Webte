<?php


use models\GoalAttributes;

enum Side : string
{
    case TOP = 'TOP';
    case BOTTOM = 'BOTTOM';
    case LEFT = 'LEFT';
    case RIGHT = 'RIGHT';

    public function playerAttributes(): PlayerAttributes
    {
        return match ($this) {
            self::TOP => new PlayerAttributes('red', 250, 70, 100, 20, 'top',Side::TOP, false),
            self::BOTTOM => new PlayerAttributes('blue', 250, 510, 100, 20, 'bottom',Side::BOTTOM, false),
            self::LEFT => new PlayerAttributes('green', 70, 250, 20, 100, 'left',Side::LEFT, false),
            self::RIGHT => new PlayerAttributes('yellow', 510, 250, 20, 100, 'right',Side::RIGHT, false),
        };
    }

    public function goalAttributes(): GoalAttributes
    {
        return match ($this) {
            self::TOP => new GoalAttributes(150, 50, 300, 5),
            self::BOTTOM => new GoalAttributes(150, 545, 300, 5),
            self::LEFT => new GoalAttributes(50, 150, 5, 300),
            self::RIGHT => new GoalAttributes(545, 150, 5, 300),
        };
    }


}


