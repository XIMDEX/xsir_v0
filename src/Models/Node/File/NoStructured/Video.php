<?php

namespace Ximdex\Models\Node\File\NoStructured;

use Ximdex\Models\Node\File\NoStructured;

class Video extends NoStructured
{
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties([
            'icon' => 'video_file'
        ]);
    }
}
