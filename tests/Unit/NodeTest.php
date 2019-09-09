<?php

namespace Tests\Unit;

use Tests\TestCase;
use Ximdex\Seeds\NodeTypesSeeder;
use Ximdex\Models\Node;
use Ximdex\Models\Node\Container;
use Ximdex\Models\Node\File\NoStructured\Image;
use Ximdex\Models\Node\File\Structured\HTML;

class NodeTest extends TestCase
{
    private static $nodes = [];

    /**
     * Generate node types with seeder test
     */
    public function testGenerateNodeTypes(): void
    {
        $this->seed(NodeTypesSeeder::class);
        $this->assertTrue(true);
    }

    /**
     * Nodes creation test and node types check
     */
    public function testNodesCreation(): void
    {
        self::$nodes['HTMLcontainer'] = $this->createNode('HTML container', Container::class);
        $container = $this->loadNode(self::$nodes['HTMLcontainer']);
        self::$nodes['index'] = $this->createNode('index.html', HTML::class, $container);
        $html = $this->loadNode(self::$nodes['index']);
        $this->assertInstanceOf(Node::class, $html->parent);
        $parent = $this->loadNode($html->parent->id);
        $this->assertEquals(class_basename(Container::class), $parent->type);
    }

    /**
     * Get node dependencies tests
     */
    public function testNodeDependencies(): void
    {
        $imageId = $this->createNode('beach.jpg', Image::class);
        $htmlId = $this->createNode('contact.html', HTML::class, $this->loadNode(self::$nodes['HTMLcontainer']));
        $html = $this->loadNode(self::$nodes['index']);
        $html->dependencies()->syncWithoutDetaching([$imageId, $htmlId]);
        $html->load('dependencies');
        foreach ($html->dependencies as $dependency) {
            $this->assertInstanceOf(Node::class, $dependency);
            self::$nodes[$dependency->name] = $dependency->id;
        }
        $this->assertCount(2, $html->dependencies);
        $html->dependencies()->detach();
        $html->load('dependencies');
        $this->assertCount(0, $html->dependencies);
    }

    /**
     * Nodes deletion test
     */
    public function testNodesDeletion(): void
    {
        foreach (array_reverse(self::$nodes) as $id) {
            $this->assertTrue($this->loadNode($id)->delete());
        }
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
     * Load a node by ID field returning an instance for its node type
     *
     * @param int $id
     * @return Node|NULL
     */
    private function loadNode(int $id): ?Node
    {
        $node = Node::instanceFromNodeType($id);
        $this->assertInstanceOf("{$node->node_type->namespace}\\{$node->type}", $node);
        return $node;
    }
}
