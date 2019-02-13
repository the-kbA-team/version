<?php

namespace Tests\kbATeam\Version;

use kbATeam\Version\FileVersion;
use kbATeam\Version\GitVersion;
use kbATeam\Version\IVersion;
use kbATeam\Version\Version;

/**
 * Class Tests\kbATeam\Version\VersionTest
 *
 * Test multiple version determination.
 *
 * @package Tests\kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
class VersionTest extends \PHPUnit_Framework_TestCase
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
            'branch' => 'retsam',
            'commit' => '765e9c9'
        ]));
        mkdir($this->tempDir.'/.git/refs/heads/', 0777, true);
        file_put_contents($this->tempDir.'/.git/HEAD', 'ref: refs/heads/master');
        file_put_contents($this->tempDir.'/.git/refs/heads/master', '9c9e4373dbd136a4f405a828a9ecf445f207e49c');
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
     * Test retrieving the version from the JSON encoded file rather that from the
     * git repository.
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit_Framework_Exception
     */
    public function testRetrievingFileVersion()
    {
        $version = new Version();
        static::assertInstanceOf(IVersion::class, $version);
        $version->register(new FileVersion($this->tempDir.'/commit.json'));
        //$version->register(new GitVersion($this->tempDir));
        static::assertTrue($version->exists());
        static::assertSame('retsam', $version->getBranch());
        static::assertSame('765e9c9', $version->getCommit());
        $actual = json_encode($version);
        $expected = json_encode([
            'branch' => 'retsam',
            'commit' => '765e9c9'
        ]);
        static::assertJsonStringEqualsJsonString($expected, $actual);
    }


    /**
     * Test retrieving the version from the git repository rather that from the JSON
     * encoded file.
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit_Framework_Exception
     */
    public function testRetrievingGitVersion()
    {
        $version = new Version();
        static::assertInstanceOf(IVersion::class, $version);
        $version->register(new GitVersion($this->tempDir));
        $version->register(new FileVersion($this->tempDir.'/commit.json'));
        static::assertSame('9c9e437', $version->getCommit());
        static::assertTrue($version->exists());
        static::assertSame('master', $version->getBranch());
        $actual = json_encode($version);
        $expected = json_encode([
            'branch' => 'master',
            'commit' => '9c9e437'
        ]);
        static::assertJsonStringEqualsJsonString($expected, $actual);
    }
}
