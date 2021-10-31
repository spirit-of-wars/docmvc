<?php

namespace DocMVC\Utils;

use DocMVC\Exception\FileOperations\DirectoryPermissionException;
use DocMVC\Exception\FileOperations\FileNotExistedException;
use DocMVC\Exception\FileOperations\FileOperationException;
use DocMVC\Exception\FileOperations\FileOperationsExceptionInterface;

class FileOperations
{
    /**
     * Copy file to new folder from property saveFilePath
     *
     * @param string $filePath
     * @param string $newFilePath
     * @throws FileOperationsExceptionInterface
     */
    public static function copyFile(string $filePath, string $newFilePath, $isRewritable = false): void
    {
        $newDir = dirname($newFilePath);
        if (!file_exists($newDir) || !is_dir($newDir)) {
            throw new FileNotExistedException(sprintf("Directory '%s' is not existed", $newDir));
        }
        if (!is_writable($newDir)) {
            throw new DirectoryPermissionException(sprintf("Directory '%s' not allowed for writable", $newDir));
        }
        if (!file_exists($filePath)) {
            throw new FileNotExistedException(sprintf("Origin file '%s' is not existed", $filePath));
        }
        if (!$isRewritable && file_exists($newFilePath)) {
            throw new FileOperationException(sprintf("File '%s' is already existed", $newFilePath));
        }

        if (!copy($filePath, $newFilePath)) {
            throw new FileOperationException(sprintf("File '%s' copying error", $newFilePath));
        }
    }

    /**
     * Remove file from $filePath
     *
     * @param string $filePath
     * @throws FileOperationsExceptionInterface
     */
    public static function removeFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new FileNotExistedException(sprintf("File '%s' is not existed", $filePath));
        }

        if (!unlink($filePath)) {
            throw new FileOperationException("File removing error", $filePath);
        }
    }
}