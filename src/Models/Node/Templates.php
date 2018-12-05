<?php

namespace Ximdex\Models\Node;

use Ximdex\Models\Node;

class Templates extends Node
{
    /**
     * Set bassic properties to the node
     *
     * @var array
     */
    protected $nodeProperties = [
        'icon' => 'template',
        'isHidden' => false,
        'isPublishable' => false
    ];
}
