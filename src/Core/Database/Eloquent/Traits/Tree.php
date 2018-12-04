<?php

namespace Ximdex\Core\Database\Eloquent\Traits;

trait Tree
{
    public function getChildren(int $level = 1, int $pass = 0) : array
    {
        $nodes = [];
        if ($pass < $level || $level < 0) {
            $nodes[] = $this->id;
            $children = static::where($this->parentIdField ?? 'parent_id', $this->id)->get();
            
            foreach ($children as $nodeType) {
                $nodes = array_merge($nodes, $nodeType->getChildren($level, $pass + 1));
            }
        }

        return $nodes;
    }
}
