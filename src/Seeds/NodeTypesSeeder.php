<?php

namespace Ximdex\Seeds;

use Ximdex\Models\NodeType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NodeTypesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $type = null;
        $parent = 0;
        $path =  __DIR__ . '/../Models';

        DB::beginTransaction();

        try {
            foreach ($this->getNodeTypes($path) as $node) {
                ['level' => $level, 'class' => $class] = $node;
                $type = class_basename($class);
                $base = explode('\\', str_replace('Ximdex\\Models', '', $class))[$level] ?? null;

                if ($base === null) {
                    continue;
                }

                if (!empty($base)) {
                    $nodeType = NodeType::where('type', 'like', $base)->firstOrFail();
                    $parent = $nodeType->id;
                }

                $node = NodeType::where('parent_id', $parent)->where('type', $type)->first();
                if ($node) {
                    continue;
                }
                
                NodeType::create([
                    'parent_id' => $parent,
                    'type' => $type,
                    'namespace' => str_replace_last("\\{$type}", '', $class)
                ]);
            }
        } catch (\Exception $ex) {
            DB::rollBack();
        }

        DB::commit();
    }

    private function getNodeTypes(string $path, int $level = 0) : array
    {
        $result = [];
        foreach (scandir($path, SCANDIR_SORT_NONE) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            
            try {
                $result = array_merge($result, $this->getNodeTypes("{$path}/{$file}", $level + 1));
            } catch (\ErrorException $ex) {
                $dir = str_replace_first('\\', '', str_replace('/', '\\', explode('..', $path)[1]));
                $class = "Ximdex\\{$dir}\\" . str_replace_last('.php', '', $file);

                if ((new $class) instanceof \Ximdex\Models\Node) {
                    $result[] = [
                        'level' => $level,
                        'class' => $class
                    ];
                }
            }
        }
        
        $result = array_values(array_sort($result, function ($value) {
            return $value['level'];
        }));

        return $result;
    }
}
