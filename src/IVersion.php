<?php
namespace kbATeam\Version;

/**
 * Interface IVersion
 *
 * @package kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
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
