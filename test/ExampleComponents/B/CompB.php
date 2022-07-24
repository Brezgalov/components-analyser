<?php

namespace Parser\Components\B;

use Parser\Components\C\CompC;

class CompB
{
    public function myFunc1()
    {
        $c = new CompC();
        return $c->myFunc1();
    }
}