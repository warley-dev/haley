<?php
namespace Database;
use Haley\Database\Migrations\Builder\Seeder;
use Haley\Database\Migrations\Builder\Table;

/**
 * Created at 20/05/2023 - 01:47:21
 */ 
class acessos_20230520014721
{
    public bool $active = true;

    public function migrate(Table $table)
    {  
        $table->definitions('acessos');       
        $table->primary('id');  
    
        $table->updateDate();
        $table->createdDate();
    }

    public function seeder(Seeder $seeder)
    {
        $seeder->definitions('acessos'); 

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