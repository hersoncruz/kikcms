<?php

namespace KikCMS\Models;

use KikCMS\Classes\Model\Model;

class DummyProducts extends Model
{
    public function initialize()
    {
        $this->setSource('products_dummy');
    }
}