<?php
namespace Database;
use Haley\Database\Migrations\Builder\Seeder;
use Haley\Database\Migrations\Builder\Table;

/**
 * Created at 20/05/2023 - 01:47:07
 */ 
class usuarios_20230520014707
{
    public bool $active = true;

    public function migrate(Table $table)
    {  
        $table->definitions('usuarios');       
        $table->primary('id');  
        $table->int('id_acesso');

        $table->varchar('nome');
        $table->varchar('email');
        $table->varchar('senha');
        
        $table->index('email');    
        
        $table->updateDate();
        $table->createdDate();
    }

    public function seeder(Seeder $seeder)
    {
        $seeder->definitions('usuarios'); 

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