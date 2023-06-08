<?php
namespace Database;
use Haley\Database\Migrations\Builder\Seeder;
use Haley\Database\Migrations\Builder\Table;

/**
 * Created at 08/06/2023 - 09:48:12
 */ 
class test_20230608094812
{
    public bool $active = true;

    public function migrate(Table $table)
    {  
        $table->definitions('test');       
        $table->primary('id');  
    
        
        $table->updateDate();
        $table->createdDate();
    }

    public function seeder(Seeder $seeder)
    {
        $seeder->definitions('test'); 

        $seeder->values([
            [
               'id' => 1
            ],

            [
                'id' => 2
            ]
        ]);  
    }
}