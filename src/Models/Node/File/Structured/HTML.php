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
    protected $nodeProperties = [
        'icon' => 'code_file',
        'isHidden' => false,
        'isPublishable' => true,
        'isVersionable' => true
    ];
}
