<?php

namespace Tests\kbATeam\Version;

use kbATeam\Version\FileVersion;
use kbATeam\Version\IVersion;
use PHPUnit\Framework\TestCase;

/**
 * Class Tests\kbATeam\Version\FileVersionTest
 *
 * Test retrieving version from JSON encoded file.
 *
 * @package Tests\kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
class FileVersionTest extends TestCase
{
    /**
     * @var string
     */
    private $tempDir;

    use TempDirTrait;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = $this->tempdir();
        file_put_contents($this->tempDir.'/commit.json', json_encode([
            'branch' => 'master',
            'commit' => '9c9e437'
        ]));
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->rmDir($this->tempDir);
    }

    /**
     * Test retrieving branch and commit from a JSON encoded file.
     * @return void
     */
    public function testFileVersionRetrieval(): void
    {
        $fileVersion = new FileVersion($this->tempDir.'/commit.json');
        $this->assertInstanceOf(IVersion::class, $fileVersion);
        $this->assertTrue($fileVersion->exists());
        $this->assertSame('master', $fileVersion->getBranch());
        $this->assertSame('9c9e437', $fileVersion->getCommit());
        $actual = (string)json_encode($fileVersion);
        $expected = (string)json_encode([
            'branch' => 'master',
            'commit' => '9c9e437'
        ]);
        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }

    /**
     * Test retrieving commit from a JSON encoded file.
     * @return void
     */
    public function testGettingCommit(): void
    {
        $fileVersion = new FileVersion($this->tempDir.'/commit.json');
        $this->assertSame('9c9e437', $fileVersion->getCommit());
    }

    /**
     * Test retrieving branch from a JSON encoded file.
     * @return void
     */
    public function testGettingBranch(): void
    {
        $fileVersion = new FileVersion($this->tempDir.'/commit.json');
        $this->assertSame('master', $fileVersion->getBranch());
    }
}
