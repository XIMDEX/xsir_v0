<?php

namespace Ximdex\Models\Node\File\NoStructured;

use Ximdex\Models\Node\File\NoStructured;

class Image extends NoStructured
{
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties([
            'icon' => 'image_file'
        ]);
    }
}
