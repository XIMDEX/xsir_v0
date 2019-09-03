<?php

namespace Ximdex\Models\Node;

use Ximdex\Models\Node;

class Container extends Node
{
    /**
     * Set basic properties to the node
     *
     * @var array
     */
    protected $nodeProperties = [
        'icon' => 'container',
        'isHidden' => false,
        'isPublishable' => false
    ];
}
