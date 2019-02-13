<?php
namespace kbATeam\Version;

interface IVersion extends \JsonSerializable
{
    /**
     * Determine whether version information exists.
     * @return bool
     */
    public function exists();

    /**
     * Get the branch string.
     * @return string
     */
    public function getBranch();

    /**
     * Get the latest commit ID.
     * @return string
     */
    public function getCommit();
}
