<?php

namespace Tests\kbATeam\Version;

use kbATeam\Version\GitVersion;
use kbATeam\Version\IVersion;

/**
 * Class Tests\kbATeam\Version\GitVersionTest
 *
 * Test retrieving version from git.
 *
 * @package Tests\kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
class GitVersionTest extends \PHPUnit_Framework_TestCase
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
     * Test retrieving version information from git.
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @throws \PHPUnit_Framework_Exception
     */
    public function testGitVersionRetrieval()
    {
        $gitVersion = new GitVersion($this->tempDir);
        static::assertInstanceOf(IVersion::class, $gitVersion);
        static::assertTrue($gitVersion->exists());
        static::assertSame('master', $gitVersion->getBranch());
        static::assertSame('9c9e437', $gitVersion->getCommit());
        $actual = json_encode($gitVersion);
        $expected = json_encode([
            'branch' => 'master',
            'commit' => '9c9e437'
        ]);
        static::assertJsonStringEqualsJsonString($expected, $actual);
    }
}
