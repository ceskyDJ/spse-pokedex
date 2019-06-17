<?php

declare(strict_types = 1);

namespace App\Utils;

use App\Exceptions\NonExistingFileException;
use function file_exists;
use function file_get_contents;
use function explode;
use function file_put_contents;
use function is_file;
use function unlink;

/**
 * Helper for working with files
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @copyright (C) 2019-now Michal ŠMAHEL, Václav Pavlíček
 * @package App\Utils
 */
class FileHelper
{
    /**
     * Create empty file
     *
     * @param string $file File address
     */
    public function createFile(string $file): void
    {
        file_put_contents($file, "");
    }

    /**
     * Updates file content
     *
     * @param string $file File for editing
     * @param $newContent string New content
     *
     * @throws \App\Exceptions\NonExistingFileException Invalid file
     */
    public function updateFile(string $file, string $newContent): void
    {
        if(!is_file($file)) {
            throw new NonExistingFileException("Invalid file address has been entered ({$file})");
        }

        file_put_contents($file, $newContent);
    }

    /**
     * Add a record to file
     *
     * @param string $file File for editing
     * @param $content string Content for adding
     * @param $separator string Delimiter (Default: line break)
     *
     * @throws \App\Exceptions\NonExistingFileException Invalid file
     */
    public function addToFile(string $file, string $content, string $separator = "\n"): void
    {
        $oldContent = $this->getFileContent($file);

        $this->updateFile($file, $oldContent . $separator . $content);
    }

    /**
     * Remove file content (clean the file)
     *
     * @param string $file File for editing
     *
     * @throws \App\Exceptions\NonExistingFileException Invalid file
     */
    public function cleanFile(string $file): void
    {
        $this->updateFile($file, "");
    }

    /**
     * Remove the file
     *
     * @param string $file File for editing
     *
     * @throws \App\Exceptions\NonExistingFileException Invalid file
     */
    public function deleteFile(string $file): void
    {
        if(!is_file($file)) {
            throw new NonExistingFileException("Invalid file address has been entered ({$file})");
        }

        unlink($file);
    }

    /**
     * Returns file content
     *
     * @param string $file File for editing
     *
     * @return string File content
     * @throws \App\Exceptions\NonExistingFileException Invalid file
     */
    public function getFileContent(string $file): string
    {
        if(!is_file($file)) {
            throw new NonExistingFileException("Invalid file address has been entered ({$file})");
        }

        return file_get_contents($file);
    }

    /**
     * Returns parsed file
     *
     * @param string $file File for editing
     *
     * @return string[] Parsed file (by lines)
     * @throws \App\Exceptions\NonExistingFileException Invalid file
     */
    public function parseFile(string $file): array
    {
        return explode("\n", $this->getFileContent($file));
    }
}