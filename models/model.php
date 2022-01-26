<?php

namespace Esyede\Access\Models;

defined('DS') or exit('No direct script access.');

use Facile;
use Config;

class Model extends Facile
{
    protected $prefix;

    public function __construct(array $attributes = [], $exists = false)
    {
        parent::__construct($attributes, $exists);
        $prefix = Config::get('access::access.prefix', null);

        $this->prefix = is_null($prefix) ? '' : rtrim(trim($prefix), '_');
    }

    public function table()
    {
        return $this->prefix.parent::table();
    }
}
