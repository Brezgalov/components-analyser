<?php

namespace Parser\Components\C;

use Parser\Components\A\CompA;

class CompC
{
    public function myFunc1()
    {
        $a = new CompA();
        return $a->myFunc2();
    }
}