<?php

namespace Tests\Unit;

use Tests\TestCase;
use Ximdex\Seeds\NodeTypesSeeder;
use Ximdex\Models\Node;
use Ximdex\Models\Node\Container;

class NodeTest extends TestCase
{
    private static $id;
    
    /**
     * Generate node types with seeder test
     */
    public function testGenerateNodeTypes(): void
    {
        $this->seed(NodeTypesSeeder::class);
        $this->assertTrue(true);
    }
    
    /**
     * Node creation test
     */
    public function testNodeCreation(): void
    {
        $node = new Container();
        $node->name = 'HTML container';
        $res = $node->save();
        $this->assertTrue($res);
        self::$id = $node->id;
    }
    
    /**
     * Get node dependencies tests
     */
    public function testNodeDependencies(): void
    {
        $node = $this->loadNode(self::$id);
        foreach ($node->dependencies as $dependency) {
            $this->assertInstanceOf('Node', $dependency);
        }
    }
    
    /**
     * Node deletion test
     */
    public function testNodeDeletion(): void
    {
        $node = $this->loadNode(self::$id);
        $res = $node->delete();
        $this->assertTrue($res);
    }
    
    private function createNode(string $name, string $class = Node::class): int
    {
        $node = (new $class);
        $node->name = $name;
        $res = $node->save();
        $this->assertTrue($res);
        return $node->id;
    }
    
    /**
     * Load a node by ID field and optional class
     * 
     * @param int $id
     * @param string $class
     * @return Node|NULL
     */
    private function loadNode(int $id, string $class = Node::class): ?Node
    {
        $node = (new $class)::find($id);
        $this->assertIsObject($node);
        return $node;
    }
}
