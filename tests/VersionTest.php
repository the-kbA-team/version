<?php

namespace Tests\kbATeam\Version;

use kbATeam\Version\FileVersion;
use kbATeam\Version\GitVersion;
use kbATeam\Version\IVersion;
use kbATeam\Version\Version;
use PHPUnit\Framework\TestCase;

/**
 * Class Tests\kbATeam\Version\VersionTest
 *
 * Test multiple version determination.
 *
 * @package Tests\kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
class VersionTest extends TestCase
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
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->rmDir($this->tempDir);
    }

    /**
     * Test retrieving the version from the JSON encoded file rather that from the
     * git repository.
     * @return void
     */
    public function testRetrievingFileVersion()
    {
        $version = new Version();
        $this->assertInstanceOf(IVersion::class, $version);
        $version->register(new FileVersion($this->tempDir.'/commit.json'));
        //$version->register(new GitVersion($this->tempDir));
        $this->assertTrue($version->exists());
        $this->assertSame('retsam', $version->getBranch());
        $this->assertSame('765e9c9', $version->getCommit());
        $actual = (string)json_encode($version);
        $expected = (string)json_encode([
            'branch' => 'retsam',
            'commit' => '765e9c9'
        ]);
        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }


    /**
     * Test retrieving the version from the git repository rather that from the JSON
     * encoded file.
     * @return void
     */
    public function testRetrievingGitVersion()
    {
        $version = new Version();
        $this->assertInstanceOf(IVersion::class, $version);
        $version->register(new GitVersion($this->tempDir));
        $version->register(new FileVersion($this->tempDir.'/commit.json'));
        $this->assertSame('9c9e437', $version->getCommit());
        $this->assertTrue($version->exists());
        $this->assertSame('master', $version->getBranch());
        $actual = (string)json_encode($version);
        $expected = (string)json_encode([
            'branch' => 'master',
            'commit' => '9c9e437'
        ]);
        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }
}
