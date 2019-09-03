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
        $node = $this->loadNode();
        foreach ($node->dependencies as $dependency) {
            $this->assertInstanceOf('Node', $dependency);
        }
    }
    
    /**
     * Node deletion test
     */
    public function testNodeDeletion(): void
    {
        $node = $this->loadNode();
        $res = $node->delete();
        $this->assertTrue($res);
    }
    
    private function loadNode(): ?Node
    {
        $node = Node::find(self::$id);
        $this->assertIsObject($node);
        return $node;
    }
}
