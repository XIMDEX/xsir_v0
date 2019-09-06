<?php

namespace Ximdex\Models\Node;

use Ximdex\Models\Node;

class Container extends Node
{
    /**
     * Set specified properties to the node
     *
     * @var array
     */
    private $properties = [
        'icon' => 'container',
        'isHidden' => false
    ];
    
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties($this->properties);
    }
}
