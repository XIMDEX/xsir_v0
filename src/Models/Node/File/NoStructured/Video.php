<?php

namespace Ximdex\Models\Node\File\NoStructured;

use Ximdex\Models\Node\File\NoStructured;

class Video extends NoStructured
{
    /**
     * /**
     * Set specified properties to the node
     * 
     * @var array
     */
    private $properties = [
        'icon' => 'video_file'
    ];
    
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties($this->properties);
    }
}
