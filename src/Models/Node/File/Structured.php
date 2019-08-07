<?php

namespace Ximdex\Models\Node\File;

use Ximdex\Models\Node\File;

class Structured extends File
{
    /**
     * @inheritDoc
     */
    protected $nodeProperties = [
        'icon' => 'structured',
        'isHidden' => false
    ];
}
