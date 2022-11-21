<?php

declare(strict_types=1);

namespace entities;

use peps\core\Entity;

class Truc extends Entity {
    public int $a = 3;
    public int $b = 5;
    protected int $c = 7;
}