<?php

namespace Ximdex\Core\Database\Eloquent\Traits;

use Illuminate\Database\Eloquent\Collection;

trait Tree
{
    public function children() : Collection
    {
        return static::where($this->parentIdField ?? 'parent_id', $this->id)
            ->get();
    }

    public function tree(int $depth = 1, bool $toarray = false, int $pass = 0) : array
    {
        $result = [];
        if ($pass <= $depth || $depth < 0) {
            $node = $toarray ? $this->toArray() : $this;
            $result[$pass] = [
                'node' => $node
            ];

            $children = $this->children()->all();

            foreach ($children as $key => &$child) {
                $child = $child->tree($depth, $toarray, $pass + 1);
                if (count($child) === 0) {
                    unset($child);
                    unset($children[$key]);
                    continue;
                }
                $child = reset($child);
            }

            if (count($children) > 0) {
                $result[$pass]['children'] = $children;
            }
        }

        return $result;
    }
}
