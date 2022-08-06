<?php

namespace ExampleComponentsCircled\A\MyFolder;

use ExampleComponentsCircled\C\CompC;
use ExampleComponentsCircled\B\CompB;

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