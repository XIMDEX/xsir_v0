<?php

namespace Ximdex\Models\Node\File;

use Ximdex\Models\Node\File;

class NoStructured extends File
{
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties([
            'icon' => 'nostructured',
            'isPublishable' => true
        ]);
    }
}
