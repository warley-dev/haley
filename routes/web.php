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
        $build_constraint = BuilderMemory::$constraints;

        $columns = [];
        $columns_names = [];

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
                    $helper->column()->change($build_table, $value['name'], $type);
                } else {
                    $helper->column()->create($build_table, $value['name'], $type);
                }

                $columns_names[] = $value['name'];             
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

        // set constraints   
        $constraints_active = [];

        foreach ($build_constraint as $value) {
            $constraints_active[] = $value['name'];

            if (!$helper->constraint()->has($build_table, $value['name'])) {
                $helper->constraint()->create($build_table, $value['name'], $value['type'], $value['value']);
            }
        }

        // drop constraints if not in the build
        foreach ($columns_names as $name) {
            $constraints_check = $helper->constraint()->getNamesByColumn($build_table, $name);

            if($constraints_check !== null) {
                foreach($constraints_check as $value) {
                    if(!in_array($value, $constraints_active)) {
                        $helper->constraint()->drop($build_table,$value);
                    }
                }
            }    
        }







    });
});
