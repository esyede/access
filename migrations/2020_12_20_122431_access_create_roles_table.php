<?php

defined('DS') or exit('No direct script access.');

class Access_Access_Create_Roles_Table
{
    private $prefix;

    public function __construct()
    {
        $prefix = Config::get('access::access.prefix', null);
        $prefix = is_null($prefix) ? '' : trim(rtrim($prefix, '_')).'_';
        $prefix = Str::replace_first('_', '', $prefix);

        $this->prefix = $prefix;
    }

    public function up()
    {
        Schema::create($this->prefix.'roles', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 100)->index();
            $table->string('slug', 120)->index();
            $table->boolean('deletable')->defaults(1);
            $table->string('description', 191)->nullable();
            $table->integer('level')->unsigned()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        $prefix = $this->prefix;

        Schema::disable_fk_checks($prefix.'roles');
        Schema::drop_if_exists($prefix.'roles');
        Schema::enable_fk_checks($prefix.'roles');
    }
}
