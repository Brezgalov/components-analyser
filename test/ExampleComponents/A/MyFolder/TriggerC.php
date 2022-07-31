<?php

namespace ExampleComponents\A\MyFolder;

use ExampleComponents\C\CompC;
use ExampleComponents\B\CompB;

class TriggerC
{
    public function run()
    {
        return new CompC();
    }

    public function getB()
    {
        return new CompB();
    }
}