<?php
use App\Commands\Command_Example;
use Haley\Console\Console;

// --------------------------------------------------------------------------|
//                            CONSOLE MCQUERY                                |
// --------------------------------------------------------------------------|

Console::command('example',false,[Command_Example::class,'example']);