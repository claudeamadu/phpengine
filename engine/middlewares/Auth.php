<?php
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class Auth implements IMiddleware {
    public function handle(Request $request): void {
        // Check if the user is authenticated (example logic)
        if (!$this->isAuthenticated()) {
            // Redirect the user to the login page or perform necessary actions
            $request->setRewriteUrl(ROUTE_URL."login");
        }
    }

    private function isAuthenticated(): bool {
        // Example authentication check - replace this with your actual authentication logic
        return isset($_SESSION['user_id']); // Check if user_id is set in the session
    }
}

?>
