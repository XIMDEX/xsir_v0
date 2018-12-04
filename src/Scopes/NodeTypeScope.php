<?php

namespace Ximdex\Scopes;

use Ximdex\Models\NodeType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class NodeTypeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $nodeType = class_basename($model);
        $nodeType = NodeType::select('id')->where('type', 'like', $nodeType)->first();
       
        $children = $nodeType->getChildren(-1);
        $builder->whereIn('node_type_id', $children);
    }
}
