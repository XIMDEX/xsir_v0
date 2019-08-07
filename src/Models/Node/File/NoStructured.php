<?php

namespace Ximdex\Models\Node\File;

use Ximdex\Models\Node\File;

class NoStructured extends File
{
    /**
     * @inheritDoc
     */
    protected $nodeProperties = [
        'icon' => 'nostructured',
        'isHidden' => false
    ];
}
