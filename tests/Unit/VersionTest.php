<?php

namespace Tests\Unit;

use Ximdex\Models\Node;
use Ximdex\Models\Version;
use Ximdex\Models\Node\File\Structured\HTML;

class VersionTest extends CommonTest
{
    /**
     * Create a new node a verify the new version associated to itself
     */
    public function testNewVersion(): void
    {
        $index = $this->createNode('index', HTML::class);
        $this->assertCount(1, $index->versions);
        $this->assertInstanceOf(Version::class, $index->version);
    }
    
    /**
     * Check that the last version from an updated node is correct 
     */
    public function testUpdateVersion(): void
    {
        $index = Node::instanceFromNodeType(self::$nodes['index']);
        $this->assertCount(1, $index->versions);
        $index->update();
        $this->assertCount(2, $index->versions);
        $this->assertInstanceOf(Version::class, $index->version);
        $this->assertEquals(1, $index->version->minor);
    }
    
    /**
     * Nodes deletion test
     */
    public function testNodesDeletion(): void
    {
        $this->deleteNodes();
    }
}
