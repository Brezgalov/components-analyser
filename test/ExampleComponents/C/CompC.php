<?php

namespace ExampleComponents\C;

use ExampleComponents\A\CompA;

class CompC
{
    public function circleCall()
    {
        $a = new CompA();

        return $a->circleCallFinish();
    }
}