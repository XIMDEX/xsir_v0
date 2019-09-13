<?php

namespace Tests\Unit;

use Tests\TestCase;
use Ximdex\Models\Node;

abstract class CommonTest extends TestCase
{
    protected static $nodes = [];
    
    /**
     * Generic node creator
     *
     * @param string $name
     * @param string $class
     * @param Node $parent
     * @return int
     */
    protected function createNode(string $name, string $class = Node::class, Node $parent = null): Node
    {
        $node = (new $class());
        $node->name = $name;
        $node->parent()->associate($parent);
        $this->assertTrue($node->push());
        self::$nodes[$name] = $node->id;
        return $this->loadNode($node->id);
    }
    
    /**
     * Load a node by ID field returning an instance for its node type
     *
     * @param int $id
     * @return Node
     */
    protected function loadNode(int $id): Node
    {
        $node = Node::instanceFromNodeType($id);
        $this->assertInstanceOf("{$node->node_type->namespace}\\{$node->type}", $node);
        return $node;
    }
    
    /**
     * Remove all nodes created in a reverse way
     */
    protected function deleteNodes(): void
    {
        // foreach (array_reverse(self::$nodes) as $id) {
        while ($id = array_pop(self::$nodes)) {
            $this->assertTrue($this->loadNode($id)->delete());
        }
    }
}