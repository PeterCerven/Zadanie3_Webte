<?php


use models\GoalAttributes;

enum Side
{
    case TOP;
    case BOTTOM;
    case LEFT;
    case RIGHT;

    public function playerAttributes(): PlayerAttributes
    {
        return match ($this) {
            self::TOP => new PlayerAttributes('red', 200, 20, 100, 20, 'top',Side::TOP, false),
            self::BOTTOM => new PlayerAttributes('blue', 200, 460, 100, 20, 'bottom',Side::BOTTOM, false),
            self::LEFT => new PlayerAttributes('green', 20, 200, 20, 100, 'left',Side::LEFT, false),
            self::RIGHT => new PlayerAttributes('yellow', 460, 200, 20, 100, 'right',Side::RIGHT, false),
        };
    }

    public function goalAttributes(): GoalAttributes
    {
        return match ($this) {
            self::TOP => new GoalAttributes(100, 0, 300, 5),
            self::BOTTOM => new GoalAttributes(100, 495, 300, 5),
            self::LEFT => new GoalAttributes(0, 100, 5, 300),
            self::RIGHT => new GoalAttributes(495, 100, 5, 300),
        };
    }
}


