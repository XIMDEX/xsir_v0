<?php

namespace Ximdex\Models;

use Ximdex\Core\Database\Eloquent\Model;

class NodeType extends Model
{
    protected $fillable = [
        'parent_id',
        'type'
    ];
}
