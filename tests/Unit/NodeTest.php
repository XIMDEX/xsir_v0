<?php

namespace Tests\Unit;

use Tests\TestCase;
use Ximdex\Seeds\NodeTypesSeeder;
use Ximdex\Models\Node;
use Ximdex\Models\Node\Container;
use Ximdex\Models\Node\File\Structured\HTML;

class NodeTest extends TestCase
{
    private static $containerId;

    private static $htmlId;

    /**
     * Generate node types with seeder test
     */
    public function testGenerateNodeTypes(): void
    {
        $this->seed(NodeTypesSeeder::class);
        $this->assertTrue(true);
    }

    /**
     * Nodes creation test
     */
    public function testNodesCreation(): void
    {
        self::$containerId = $this->createNode('HTML container', Container::class);
        $container = $this->loadNode(self::$containerId);
        self::$htmlId = $this->createNode('HTML document', HTML::class, $container);
        $html = $this->loadNode(self::$htmlId);
        $this->assertInstanceOf(Node::class, $html->parent);
        $this->assertEquals('Container', $html->parent->type);
    }

    /**
     * Get node dependencies tests
     */
    public function testNodeDependencies(): void
    {
        $node = $this->loadNode(self::$containerId);
        foreach ($node->dependencies as $dependency) {
            $this->assertInstanceOf(Node::class, $dependency);
        }
    }

    /**
     * Nodes deletion test
     */
    public function testNodesDeletion(): void
    {
        $node = $this->loadNode(self::$htmlId);
        $this->assertTrue($node->delete());
        $node = $this->loadNode(self::$containerId);
        $this->assertTrue($node->delete());
    }

    /**
     * Generic node creator
     * 
     * @param string $name
     * @param string $class
     * @param Node $parent
     * @return int
     */
    private function createNode(string $name, string $class = Node::class, Node $parent = null): int
    {
        $node = (new $class());
        $node->name = $name;
        if ($parent) {
            $node->parent()->associate($parent);
        }
        $res = $node->push();
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
        $node = (new $class())::find($id);
        $this->assertInstanceOf(Node::class, $node);
        if ($class != Node::class and $node->type != 'Node') {
            $this->assertInstanceOf("{$node->node_type->namespace}\\{$node->type}", $node);
        }
        return $node;
    }
}
