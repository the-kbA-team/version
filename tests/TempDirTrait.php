<?php

namespace Tests\kbATeam\Version;

/**
 * Class Tests\kbATeam\Version\TempDirTrait
 *
 * Creating and removing temporary directories.
 *
 * @package Tests\kbATeam\Version
 * @author  Gregor J.
 * @license MIT
 */
trait TempDirTrait
{
    /**
     * Create a temporary directory.
     * @return string
     * @throws \RuntimeException
     */
    private static function tempdir()
    {
        ob_start();
        $result = system('mktemp -d', $exitCode);
        ob_end_clean();
        if ($exitCode !== 0) {
            throw new \RuntimeException('Could not create temporary directory!');
        }
        return $result;
    }

    /**
     * Remove a directory and all its contents.
     * @param string $dir
     * @throws \RuntimeException
     */
    private static function rmDir($dir)
    {
        if (!is_dir($dir)) {
            throw new \RuntimeException(sprintf("Directory %s doesn't exist", $dir));
        }
        ob_start();
        system(sprintf('rm -Rf "%s"', $dir), $exitCode);
        ob_end_clean();
        if ($exitCode !== 0) {
            throw new \RuntimeException(sprintf('Could not remove directory %s!', $dir));
        }
    }
}
