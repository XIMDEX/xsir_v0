<?php

namespace Ximdex\Models\Node\File\NoStructured;

use Ximdex\Models\Node\File\NoStructured;

class Image extends NoStructured
{
    /**
     * @inheritDoc
     */
    protected $nodeProperties = [
        'icon' => 'image_file',
        'isHidden' => false,
        'isPublishable' => true
    ];
}
