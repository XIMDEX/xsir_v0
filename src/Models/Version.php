<?php

namespace Ximdex\Models;

use Ximdex\Core\Database\Eloquent\Model;

class Version extends Model
{
    protected $fillable = [
        'node_id',
    ];

    protected $hidden = [
        'id',
        'node_id',
        'updated_at'
    ];
}
