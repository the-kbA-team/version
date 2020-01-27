<?php

namespace kbATeam\Version;

/**
 * Class kbATeam\Version\FileVersion
 *
 * Determine the project version from the JSON file produced by the deployment script.
 *
 * @package kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
class FileVersion extends AbstractVersion
{
    /**
     * @var string The path to the commit.json file.
     */
    private $file;

    /**
     * @var bool Cache fileExists() function reply.
     */
    private $fileExists;

    /**
     * @var string Cache fileContents() function reply.
     */
    private $fileContents;

    /**
     * Initialize this class using the given JSON encoded file produced by the
     * deployment script.
     * @param string $file The JSON formatted file containig branch and commit.
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Check if the JSON file exists.
     * @return bool
     */
    private function fileExists(): bool
    {
        if ($this->fileExists === null) {
            $this->fileExists = file_exists($this->file);
        }
        return $this->fileExists;
    }

    /**
     * Get the contents of the JSON encoded file.
     * @return array
     */
    private function fileContents(): array
    {
        $result = [
            'branch' => null,
            'commit' => null
        ];
        if ($this->fileExists()) {
            $jsonStr = file_get_contents($this->file);
            if ($jsonStr !== false) {
                $json = json_decode($jsonStr, true);
                if ($json !== null) {
                    if (array_key_exists('branch', $json)) {
                        $result['branch'] = $json['branch'];
                    }

                    if (array_key_exists('commit', $json)) {
                        $result['commit'] = $json['commit'];
                    }
                }
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
        if ($this->fileContents === null) {
            $this->fileContents = $this->fileContents();
        }
        return $this->fileContents['branch'];
    }

    /**
     * Get the latest commit ID.
     * @return string
     */
    public function getCommit()
    {
        if ($this->fileContents === null) {
            $this->fileContents = $this->fileContents();
        }
        return $this->fileContents['commit'];
    }
}
