<?php

namespace kbATeam\Version;

/**
 * Class kbATeam\Version\GitVersion
 *
 * Determine the project version from the git repository.
 *
 * @package kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
class GitVersion extends AbstractVersion
{
    /**
     * @var string The git directory.
     */
    private $dir;

    /**
     * @var string The git HEAD.
     */
    private $head;

    /**
     * @var bool Cache result for isRepo().
     */
    private $isRepo;

    /**
     * @var string|null The current branch name in the git repository.
     */
    private $branch;

    /**
     * @var string The last commit ID in the git repository.
     */
    private $commit;

    /**
     * GitVersion constructor.
     * @param string $appDir The application directory.
     */
    public function __construct(string $appDir)
    {
        $this->dir = $appDir . DIRECTORY_SEPARATOR . '.git';
        $this->head = $this->dir . DIRECTORY_SEPARATOR . 'HEAD';
    }

    /**
     * Is this a valid repository?
     * @return bool
     */
    private function isRepo(): bool
    {
        if ($this->isRepo === null) {
            $this->isRepo = (is_dir($this->dir) && file_exists($this->head));
        }
        return $this->isRepo;
    }

    /**
     * Get the branch string.
     * @return string|null
     */
    public function getBranch(): ?string
    {
        if ($this->branch === null && $this->isRepo()) {
            $lines = file($this->head);
            if ($lines !== false && array_key_exists(0, $lines)) {
                $refLine = trim($lines[0]);
                $chunks = explode('/', $refLine);
                $this->branch = array_pop($chunks);
            }
        }
        return $this->branch;
    }

    /**
     * Get the latest commit ID.
     * @return string|null
     */
    public function getCommit(): ?string
    {
        if ($this->commit === null && $this->isRepo()) {
            /**
             * Build filepath from branch name.
             */
            $refFile = $this->dir . DIRECTORY_SEPARATOR
                . 'refs' . DIRECTORY_SEPARATOR
                . 'heads' . DIRECTORY_SEPARATOR
                . $this->getBranch();
            /**
             * read ref file
             */
            if (file_exists($refFile)) {
                $lines = file($refFile);
                if ($lines !== false && array_key_exists(0, $lines)) {
                    $commitLine = trim($lines[0]);
                    // shorten commit hash
                    $this->commit = substr($commitLine, 0, 7);
                }
            }
        }
        return $this->commit;
    }
}
