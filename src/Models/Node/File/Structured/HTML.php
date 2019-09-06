<?php

namespace Ximdex\Models\Node\File\Structured;

use Ximdex\Models\Node\File\Structured;

class HTML extends Structured
{
    /**
     * Set basic properties to the node
     *
     * @var array
     */
    private $properties = [
        'icon' => 'code_file',
        'isPublishable' => true,
        'isVersionable' => true
    ];
    
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties($this->properties);
    }
}
