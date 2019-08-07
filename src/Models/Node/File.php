<?php

namespace Ximdex\Models\Node;

use Ximdex\Models\Node;

class File extends Node
{
    /**
     * @inheritDoc
     */
    protected $nodeProperties = [
        'icon' => 'file',
        'isHidden' => false
    ];
}
