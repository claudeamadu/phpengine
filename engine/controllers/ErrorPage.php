<?php

class ErrorPage {
    public function notFound() {
        http_response_code(404);
        echo View::error(404);
    }
    public function forbidden() {
        http_response_code(403);
        echo View::error(403);
    }
}

?>
