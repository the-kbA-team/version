<?php

namespace Tests\kbATeam\Version;

use kbATeam\Version\FileVersion;
use kbATeam\Version\IVersion;

/**
 * Class Tests\kbATeam\Version\FileVersionTest
 *
 * Test retrieving version from JSON encoded file.
 *
 * @package Tests\kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
class FileVersionTest extends \PHPUnit_Framework_TestCase
{
    private $tempDir;

    use TempDirTrait;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->tempDir = static::tempdir();
        file_put_contents($this->tempDir.'/commit.json', json_encode([
            'branch' => 'master',
            'commit' => '9c9e437'
        ]));
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
        static::rmDir($this->tempDir);
    }

    /**
     * Test retrieving branch and commit from a JSON encoded file.
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit_Framework_Exception
     */
    public function testFileVersionRetrieval()
    {
        $fileVersion = new FileVersion($this->tempDir.'/commit.json');
        static::assertInstanceOf(IVersion::class, $fileVersion);
        static::assertTrue($fileVersion->exists());
        static::assertSame('master', $fileVersion->getBranch());
        static::assertSame('9c9e437', $fileVersion->getCommit());
        $actual = json_encode($fileVersion);
        $expected = json_encode([
            'branch' => 'master',
            'commit' => '9c9e437'
        ]);
        static::assertJsonStringEqualsJsonString($expected, $actual);
    }

    /**
     * Test retrieving commit from a JSON encoded file.
     */
    public function testGettingCommit()
    {
        $fileVersion = new FileVersion($this->tempDir.'/commit.json');
        static::assertSame('9c9e437', $fileVersion->getCommit());
    }

    /**
     * Test retrieving branch from a JSON encoded file.
     */
    public function testGettingBranch()
    {
        $fileVersion = new FileVersion($this->tempDir.'/commit.json');
        static::assertSame('master', $fileVersion->getBranch());
    }
}
