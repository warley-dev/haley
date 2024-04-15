<?php

use Haley\Database\Migration\Builder\Builder;
use Haley\Database\Migration\Migration;

return new class
{
    public bool $single = true;
    public bool $active = true;

    public function up()
    {
        (new Migration)->up('testa', function (Builder $build) {
            $build->id('id', 'dfg');
            // $build->int('id');  
            $build->varchar('nome')->comment('helo word')->nullable(true);
            $build->varchar('email')->comment('helo word')->nullable(true);










            $build->int('fore')->comment('foreing test');

            // $build->foreign('fore', 'haley', 'id');



            // $build->varchar('varcfghar')->comment('test fdgsdfg')->default('sdgfsdfg sdfgsdfg')->notNull()->unique();
            // $build->varchar('varcfgr')->comment('vc')->default('v dfgdfc');
        });
    }
};
