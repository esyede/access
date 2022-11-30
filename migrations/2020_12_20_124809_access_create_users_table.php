<?php

defined('DS') or exit('No direct script access.');

class Access_Access_Create_Users_Table
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
        Schema::create($this->prefix.'users', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 191)->index();
            $table->string('password', 60)->index();
            $table->string('email', 191)->index();
            $table->integer('role_id')->unsigned()->index();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on($this->prefix.'roles');
        });
    }

    public function down()
    {
        $prefix = $this->prefix;

        Schema::disable_fk_checks($prefix.'users');
        Schema::drop_if_exists($prefix.'users');
        Schema::enable_fk_checks($prefix.'users');
    }
}
