<?php

namespace Tests\kbATeam\Version;

use kbATeam\Version\GitVersion;
use kbATeam\Version\IVersion;
use PHPUnit\Framework\TestCase;

/**
 * Class Tests\kbATeam\Version\GitVersionTest
 *
 * Test retrieving version from git.
 *
 * @package Tests\kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
class GitVersionTest extends TestCase
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
     * Test retrieving version information from git.
     * @return void
     */
    public function testGitVersionRetrieval(): void
    {
        $gitVersion = new GitVersion($this->tempDir);
        $this->assertInstanceOf(IVersion::class, $gitVersion);
        $this->assertTrue($gitVersion->exists());
        $this->assertSame('master', $gitVersion->getBranch());
        $this->assertSame('9c9e437', $gitVersion->getCommit());
        $actual = (string)json_encode($gitVersion);
        $expected = (string)json_encode([
            'branch' => 'master',
            'commit' => '9c9e437'
        ]);
        static::assertJsonStringEqualsJsonString($expected, $actual);
    }
}
