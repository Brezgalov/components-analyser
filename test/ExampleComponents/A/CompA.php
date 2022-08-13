<?php

namespace ExampleComponents\A;

use ExampleComponents\B\CompB;

class CompA
{
    public function circleCallStart()
    {
        $b = new CompB();

        return $b->circleCall();
    }

    public function circleCallFinish()
    {
        return 1;
    }
}