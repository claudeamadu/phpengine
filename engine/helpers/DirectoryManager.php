<?php

class DirectoryManager {
    private $baseDirectory;

    public function __construct($baseDirectory) {
        $this->baseDirectory = $baseDirectory;
    }

    public function createDirectory($directoryName) {
        $targetDirectory = $this->baseDirectory . DIRECTORY_SEPARATOR . $directoryName;

        if (!file_exists($targetDirectory)) {
            if (mkdir($targetDirectory, 0777, true)) {
                return true;
            } else {
                throw new Exception("Failed to create directory");
            }
        }

        return false;
    }
}