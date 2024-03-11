<?php

namespace kbATeam\Version;

/**
 * Class kbATeam\Version\AbstractVersion
 *
 * Implement common methods of the IVersion interface.
 *
 * @package kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
abstract class AbstractVersion implements IVersion
{
    /**
     * Specify data which should be serialized to JSON
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'branch' => $this->getBranch(),
            'commit' => $this->getCommit()
        ];
    }

    /**
     * Determine whether version information exists.
     * @return bool
     */
    public function exists(): bool
    {
        return ($this->getBranch() !== null && $this->getCommit() !== null);
    }

    /**
     * Get the branch string.
     * @return string|null
     */
    abstract public function getBranch();

    /**
     * Get the latest commit ID.
     * @return string|null
     */
    abstract public function getCommit();
}
