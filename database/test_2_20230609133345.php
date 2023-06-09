<?php
namespace Database;
use Haley\Database\Migrations\Builder\Seeder;
use Haley\Database\Migrations\Builder\Table;

/**
 * Created at 09/06/2023 - 13:33:45
 */ 
class test_2_20230609133345
{
    public bool $active = true;

    public function migrate(Table $table)
    {  
        $table->definitions('test_2');       
        $table->primary('id');  
        $table->int('test'); 
        
        
    
        
        $table->updateDate();
        $table->createdDate();
    }

    public function seeder(Seeder $seeder)
    {
        $seeder->definitions('test_2'); 

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