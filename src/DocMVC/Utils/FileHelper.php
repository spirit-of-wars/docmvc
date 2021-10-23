<?php

namespace DocMVC\Utils;

class FileHelper
{
    /**
     * Copy file to new folder from property saveFilePath
     */
    public static function copyFile($filePath, $newFilePath): void
    {
        if (file_exists($filePath) && file_exists($newFilePath)) { //@todo переделать вторую проверку на существование директории
            copy($filePath, $newFilePath);
            unlink($filePath);
        }
    }

    /**
     * Remove file for tmpFilePath
     */
    public static function removeFile($filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}