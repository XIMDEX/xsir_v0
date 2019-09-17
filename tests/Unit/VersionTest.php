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
     * Verify that the major version is created correctly
     */
    public function testMajorVersion(): void
    {
        $index = Node::instanceFromNodeType(self::$nodes['index']);
        $actualVersion = clone $index->version;
        $this->assertTrue(Version::increaseMajor($index));
        $this->assertCount(2, $index->versions);
        $version = $index->version;
        $this->assertEquals(1, $version->major);
        $this->assertEquals(0, $version->minor);
        $this->assertEquals($version->file, $actualVersion->file);
    }
    
    /**
     * Nodes deletion test
     */
    public function testNodesDeletion(): void
    {
        $this->deleteNodes();
    }
}
