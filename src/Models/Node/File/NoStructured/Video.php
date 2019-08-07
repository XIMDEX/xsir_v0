<?php

namespace Ximdex\Models\Node\File\NoStructured;

use Ximdex\Models\Node\File\NoStructured;

class Video extends NoStructured
{
    /**
     * @inheritDoc
     */
    protected $nodeProperties = [
        'icon' => 'video_file',
        'isHidden' => false,
        'isPublishable' => true
    ];
}
