<?php

namespace Ximdex\Models\Node;

use Ximdex\Models\Node;

class File extends Node
{
    /**
     * Set bassic properties to the node
     *
     * @var array
     */
    protected $nodeProperties = [
        'icon' => 'file',
        'isHidden' => false
    ];
}
