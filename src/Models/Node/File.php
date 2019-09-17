<?php

namespace Ximdex\Models\Node;

use Ximdex\Models\Node;

class File extends Node
{
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties([
            'icon' => 'file',
            'isHidden' => false,
            'isVersionable' => true
        ]);
    }
}
