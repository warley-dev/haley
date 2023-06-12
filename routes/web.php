<?php

use Haley\Database\Migration\Builder\BuilderMemory;
use Haley\Database\Query\DB;
use Haley\Router\Route;

// --------------------------------------------------------------------------|
//                               WEB ROUTES                                  |
// --------------------------------------------------------------------------|

Route::namespace('App\Controllers\Web')->name('web')->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
});

Route::prefix('test')->group(function () {
    Route::get('/', function () {


        $migration_up = require directoryRoot('database/migrations/test.php');
        $migration_up->up();

        $connection = BuilderMemory::$connection;
        $helper = DB::helper($connection);

        $build_columns = BuilderMemory::getColumns();
        $build_id = BuilderMemory::$id;
        $build_primary = BuilderMemory::$primary;
        $build_table = BuilderMemory::$table;
        $build_rename = BuilderMemory::$rename;

        $columns = [];

        // create table
        if (!$helper->table()->has($build_table)) {
            foreach ($build_columns as $value) {
                $columns[$value['name']] = str_replace(['[CL:NAME]', '[CL:TYPE]'], [$value['name'], $value['type']], $value['query']);
            }

            $helper->table()->create($build_table, $columns);
        }

        // modifi columns 
        else {
            // rename columns
            foreach ($build_rename as $column => $to) {
                if ($helper->column()->has($build_table, $column) and !$helper->column()->has($build_table, $to)) {
                    $helper->column()->rename($build_table, $column, $to);
                }
            }

            // change or create columns
            foreach ($build_columns as $value) {
                $type = trim(str_replace(['[CL:NAME]', '[CL:TYPE]'], ['', $value['type']], $value['query']));

                if ($helper->column()->has($build_table, $value['name'])) {
                    $helper->column()->change($build_table, $value['name'], $type, $rename);
                } else {
                    $helper->column()->create($build_table, $value['name'], $type);
                }
            }
        }

        // column id primary key
        if (count($build_id)) {
            $helper->Constraint()->setId($build_table, $build_id['name'], $build_id['comment']);
        }

        // column primary key
        else {
            $atual_primary = $helper->Constraint()->getPrimaryKey($build_table);

            if ($build_primary !== null) {
                if ($atual_primary !== $build_primary) {
                    $helper->Constraint()->dropPrimaryKey($build_table);
                    $helper->Constraint()->setPrimaryKey($build_table, $build_primary);
                }
            }
        }
    });
});
