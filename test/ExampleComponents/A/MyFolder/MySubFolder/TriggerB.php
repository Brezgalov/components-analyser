<?php

namespace Parser\Components\A\MyFolder;

use Parser\Components\B\CompB;

class TriggerB
{
    public function run()
    {
        return new CompB();
    }
}