<?php

defined('DS') or exit('No direct script access.');

class Access_Access_Seed_Role_Permissions
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
        // ..
    }

    public function down()
    {
        // ..
    }
}
