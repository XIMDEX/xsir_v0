<?php

namespace Ximdex\Models\Node\File\Structured;

use Ximdex\Models\Node\File\Structured;

class HTML extends Structured
{
    public function __construct()
    {
        parent::__construct();
        $this->loadProperties([
            'icon' => 'code_file',
            'isPublishable' => true
        ]);
    }
}
