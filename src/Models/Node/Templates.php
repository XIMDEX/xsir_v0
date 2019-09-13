<?php

namespace Ximdex\Models\Node;

use Ximdex\Models\Node;

class Templates extends Node
{
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties([
            'icon' => 'template',
            'isHidden' => false
        ]);
    }
}
