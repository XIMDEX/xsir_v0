<?php

namespace Ximdex\Seeds;

use Ximdex\Models\Node;
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
        $parent = null;
        $nodes = $this->getNodeTypes(dirname(__DIR__), 'Models');
        DB::beginTransaction();
        try {
            foreach ($nodes as $node) {
                [
                    'level' => $level,
                    'class' => $class
                ] = $node;
                $type = class_basename($class);
                $base = explode('\\', str_replace('Ximdex\\Models', '', $class))[$level] ?? null;
                if ($base === null) {
                    continue;
                }
                if (! empty($base)) {
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
            DB::commit();
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            DB::rollBack();
        }
    }

    private function getNodeTypes(string $path, string $folder, int $level = 0): array
    {
        $result = [];
        foreach (scandir("{$path}/{$folder}", SCANDIR_SORT_NONE) as $file) {
            if (in_array($file, [
                '.',
                '..'
            ])) {
                continue;
            }
            $filepath = "{$path}/{$folder}/{$file}";
            try {
                if (is_dir($filepath)) {
                    $result = array_merge($result, $this->getNodeTypes($path, "{$folder}/{$file}", $level + 1));
                } else {
                    $dir = str_replace('/', '\\', $folder);
                    $class = "Ximdex\\{$dir}\\" . str_replace_last('.php', '', $file);
                    if (strcmp(Node::class, $class) == 0 || is_subclass_of($class, Node::class)) {
                        $result[] = [
                            'level' => $level,
                            'class' => $class
                        ];
                    }
                }
            } catch (\ErrorException $ex) {
                echo $ex->getMessage();
            }
        }
        return array_values(array_sort($result, function ($value) {
            return $value['level'];
        }));
    }
}
