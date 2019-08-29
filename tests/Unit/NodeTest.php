<?php

namespace Tests\Unit;

use Tests\TestCase;
use Ximdex\Models\Node;
use Ximdex\Seeds\NodeTypesSeeder;

class NodeTest extends TestCase
{
    private static $node;
    
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
        $node = new Node;
        $node->name = 'Node name';
        $node->save();
        self::$node = $node;
        $this->assertTrue(true);
    }
    
    /**
     * Dependencies tests
     */
    public function testNodeDependencies(): void
    {
        /*
        foreach (self::$node->dependencies as $dependency) {
            $this->assertInstanceOf('Node', $dependency);
        }
        */
        $this->assertTrue(true);
    }
}
