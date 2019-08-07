<?php

namespace Ximdex\Models;

use Ximdex\Core\Database\Eloquent\Model;
use Ximdex\Core\Database\Eloquent\Traits\Tree;

class NodeType extends Model
{
    use Tree;

    protected $fillable = [
        'parent_id',
        'type'
    ];
}
