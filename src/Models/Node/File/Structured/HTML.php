<?php

namespace Ximdex\Models\Node\File\Structured;

use Ximdex\Models\Node\File\Structured;

class HTML extends Structured
{
    /**
     * Set bassic properties to the node
     *
     * @var array
     */
    protected $nodeProperties = [
        'icon' => 'html',
        'isHidden' => false,
        'isPublishable' => true
    ];
}
