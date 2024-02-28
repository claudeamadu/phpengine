<?php
class CustomError extends Exception {
    public function errorMessage() {
        // Error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile() . ': <b>' . $this->getMessage() . '</b> <br>';
        return $errorMsg;
    }
}