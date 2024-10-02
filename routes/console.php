<?php

use Haley\Console\Console;

// --------------------------------------------------------------------------|
//                             CONSOLE ROUTES                                |
// --------------------------------------------------------------------------|

Console::namespace('App\Console')->group(function () {
    Console::command('example {name}', 'Example@helo')->description('command example');
});
