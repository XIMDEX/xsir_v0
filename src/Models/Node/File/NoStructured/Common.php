<?php

namespace Ximdex\Models\Node\File\NoStructured;

use Ximdex\Models\Node\File\NoStructured;

class Common extends NoStructured
{
    /**
     * @inheritDoc
     */
    protected $nodeProperties = [
        'icon' => 'common_file',
        'isHidden' => false,
        'isPublishable' => true
    ];
}
