<?php

namespace kbATeam\Version;

/**
 * Class kbATeam\Version\Version
 *
 * Determine the version using multiple ways.
 *
 * @package kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
class Version extends AbstractVersion
{
    /**
     * @var array of IVersion objects.
     */
    private $versions;

    /**
     * @var array Branch and commit.
     */
    private $version;

    /**
     * Version constructor.
     */
    public function __construct()
    {
        $this->versions = [];
    }

    /**
     * Register a new way to determine the version.
     * @param \kbATeam\Version\IVersion $version
     */
    public function register(IVersion $version)
    {
        $this->versions[] = $version;
    }

    /**
     * Determine the current version.
     * @return array
     */
    private function determineVersion(): array
    {
        $result = [
            'branch' => null,
            'commit' => null
        ];
        foreach ($this->versions as $version) {
            /**
             * @var \kbATeam\Version\IVersion $version
             */
            if ($version->exists()) {
                $result['branch'] = $version->getBranch();
                $result['commit'] = $version->getCommit();
                break;
            }
        }
        return $result;
    }

    /**
     * Get the branch string.
     * @return string
     */
    public function getBranch()
    {
        if ($this->version === null) {
            $this->version = $this->determineVersion();
        }
        return $this->version['branch'];
    }

    /**
     * Get the latest commit ID.
     * @return string
     */
    public function getCommit()
    {
        if ($this->version === null) {
            $this->version = $this->determineVersion();
        }
        return $this->version['commit'];
    }
}
