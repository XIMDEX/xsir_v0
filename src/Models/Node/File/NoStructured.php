<?php

namespace Ximdex\Models\Node\File;

use Ximdex\Models\Node\File;

class NoStructured extends File
{
    /**
     * Set specified properties to the node
     * 
     * @var array
     */
    private $properties = [
        'icon' => 'nostructured'
    ];
    
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties($this->properties);
    }
}
