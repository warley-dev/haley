<?php

namespace App\Console;

use Haley\Shell\Shell;

class Example
{
    public function helo($name)
    {
        Shell::normal('helo')->red($name)->br();
    }
}
