<?php

namespace Tests\Unit;

use Ximdex\Seeds\NodeTypesSeeder;
use Ximdex\Models\Node;
use Ximdex\Models\Node\Container;
use Ximdex\Models\Node\File\NoStructured\Image;
use Ximdex\Models\Node\File\Structured\HTML;

class NodeTest extends CommonTest
{
    /**
     * Generate node types with seeder test
     */
    public function testGenerateNodeTypes(): void
    {
        $this->seed(NodeTypesSeeder::class);
        $this->assertTrue(true);
    }

    /**
     * Nodes creation test, parent container and node types check
     */
    public function testNodesCreation(): void
    {
        $container = $this->createNode('HTMLcontainer', Container::class);
        $index = $this->createNode('index', HTML::class, $container);
        $this->assertInstanceOf(Node::class, $index->parent);
        $parent = $this->loadNode($index->parent->id);
        $this->assertEquals(class_basename(Container::class), $parent->type);
    }

    /**
     * Node dependencies and referencies tests
     */
    public function testNodeDependencies(): void
    {
        $image = $this->createNode('beach.jpg', Image::class);
        $contact = $this->createNode('contact', HTML::class, $this->loadNode(self::$nodes['HTMLcontainer']));
        $index = $this->loadNode(self::$nodes['index']);
        $index->dependencies()->syncWithoutDetaching([$image->id, $contact->id]);
        $index->load('dependencies');
        foreach ($index->dependencies as $dependency) {
            $this->assertInstanceOf(Node::class, $dependency);
            self::$nodes[$dependency->name] = $dependency->id;
        }
        $this->assertCount(2, $index->dependencies);
        // $contact = $this->loadNode($contact->id);
        $contact->dependencies()->syncWithoutDetaching($image->id);
        // $image = $this->loadNode($imageId);
        $this->assertCount(2, $image->referencies);
        $index->dependencies()->detach();
        $index->load('dependencies');
        $this->assertCount(0, $index->dependencies);
    }

    /**
     * Nodes deletion test
     */
    public function testNodesDeletion(): void
    {
        $this->deleteNodes();
    }
}
