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
    private $properties = [
        'icon' => 'template',
        'isHidden' => false
    ];
    
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties($this->properties);
    }
}
