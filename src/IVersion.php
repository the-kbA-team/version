<?php
namespace kbATeam\Version;

use JsonSerializable;

/**
 * Interface IVersion
 *
 * @package kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
interface IVersion extends JsonSerializable
{
    /**
     * Determine whether version information exists.
     * @return bool
     */
    public function exists(): bool;

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
