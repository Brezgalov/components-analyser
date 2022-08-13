<?php

namespace ExampleComponents\B;

use ExampleComponents\C\CompC;

class CompB
{
    public function circleCall()
    {
        $c = new CompC();

        return $c->circleCall();
    }
}