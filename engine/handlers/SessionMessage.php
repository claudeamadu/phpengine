<?php
class SessionMessage {
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setMessage($message, $title, $type) {
        self::startSession();
        $_SESSION['message'] = $message;
        $_SESSION['title'] = $title;
        $_SESSION['modal_type'] = $type;
    }

    public static function unsetMessage() {
        self::startSession();
        unset($_SESSION['message']);
        unset($_SESSION['title']);
        unset($_SESSION['modal_type']);
    }

    public static function showMessage() {
        self::startSession();
        if (isset($_SESSION['message'])) {
            echo "
            <script>
                Swal.fire(
                    '" . $_SESSION['title'] . "',
                    '" . $_SESSION['message'] . "',
                    '" . $_SESSION['modal_type'] . "'
                );
            </script>
            ";
            self::unsetMessage();
        }
    }
}
