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
        $children = $this->getChildrenNodeTypes($model);
        $builder->whereIn('node_type_id', $children);
    }

    private function getChildrenNodeTypes(Model $model) : array
    {
        $nodeType = NodeType::where('type', 'like', class_basename($model))->first();
        return $this->cleanTree($nodeType->tree(-1));
    }

    private function cleanTree(array $tree) : array
    {
        $data = [];
        foreach ($tree as $value) {
            ['node' => $node, 'children' => $children] = array_replace(['node' => null, 'children' => null], $value);
            $data[] = $node->id;
            if (is_array($children) && count($children) > 0) {
                $data = array_merge($data, $this->cleanTree($children));
            }
        }
        return $data;
    }
}
