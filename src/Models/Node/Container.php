<?php

namespace Ximdex\Models\Node;

use Ximdex\Models\Node;

class Container extends Node
{
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties([
            'icon' => 'container',
            'isHidden' => false
        ]);
    }
}
