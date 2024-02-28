<?php

class FileManager {
    private $basePath;

    public function __construct($basePath) {
        $this->basePath = $basePath;
    }

    public function deleteFile($fileName) {
        $filePath = $this->basePath . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                return true;
            } else {
                throw new Exception("Failed to delete file");
            }
        }

        return false;
    }

    public function copyFile($sourceFileName, $destinationFileName) {
        $sourceFilePath = $this->basePath . DIRECTORY_SEPARATOR . $sourceFileName;
        $destinationFilePath = $this->basePath . DIRECTORY_SEPARATOR . $destinationFileName;

        if (file_exists($sourceFilePath)) {
            if (copy($sourceFilePath, $destinationFilePath)) {
                return true;
            } else {
                throw new Exception("Failed to copy file");
            }
        }

        return false;
    }

    public function moveFile($sourceFileName, $destinationFileName) {
        $sourceFilePath = $this->basePath . DIRECTORY_SEPARATOR . $sourceFileName;
        $destinationFilePath = $this->basePath . DIRECTORY_SEPARATOR . $destinationFileName;

        if (file_exists($sourceFilePath)) {
            if (rename($sourceFilePath, $destinationFilePath)) {
                return true;
            } else {
                throw new Exception("Failed to move file");
            }
        }

        return false;
    }

    // Additional methods for managing files can be added as needed
}