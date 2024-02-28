<?php

class FileUploader {
    private $uploadDirectory;
    private $allowedExtensions;
    private $maxFileSize;
    private $directoryManager;

    public function __construct($uploadDirectory, $allowedExtensions = [], $maxFileSize = 1048576) {
        $this->uploadDirectory = $uploadDirectory;
        $this->allowedExtensions = $allowedExtensions;
        $this->maxFileSize = $maxFileSize;
        $this->directoryManager = new DirectoryManager('uploads');
    }

    public function uploadFile($file) {
        $uploadedFile = $this->validateFile($_FILES[$file]);
        $this->directoryManager->createDirectory('');
        if ($uploadedFile) {
            $newFileName = $this->generateUniqueFileName($uploadedFile['name']);
            $destination = $this->uploadDirectory . $newFileName;

            if (move_uploaded_file($uploadedFile['tmp_name'], $destination)) {
                return $newFileName;
            } else {
                throw new Exception("Failed to move uploaded file");
            }
        }

        return false;
    }

    private function validateFile($file) {
        if (
            isset($file['error']) &&
            is_uploaded_file($file['tmp_name']) &&
            $file['error'] == UPLOAD_ERR_OK
        ) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

            if (
                in_array(strtolower($extension), $this->allowedExtensions) &&
                $file['size'] <= $this->maxFileSize
            ) {
                return $file;
            } else {
                throw new Exception("Invalid file type or size");
            }
        }

        throw new Exception("Error in file upload");
    }

    private function generateUniqueFileName($originalFileName) {
        $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $uniqueFileName = uniqid('file_') . '_' . time() . '.' . $extension;

        return $uniqueFileName;
    }
}
