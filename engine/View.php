<?php

/**
 * View Manager
 */
class View
{
    protected $viewPath;

    /**
     * Initialize View
     *
     * @param string $viewPath
     */
    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
    }

    /**
     * Displays a view from views folder
     *
     * @param string $viewPath
     * @return string|false
     */
    public static function make(string $viewPath): mixed
    {
        $view = new self('views/' . $viewPath . '.php');
        return $view->render();
    }
    /**
     * Displays a pagee with header and footer
     * @param string $viewPath
     * @param bool $nav
     * @param bool $footer
     * @return bool|string
     */
    public static function page(string $viewPath, bool $nav = true, bool $footer = true): mixed
    {
        // Initialize the view
        $view = new self('views/' . $viewPath . '.php');

        // Capture the rendered view
        $content = $view->render();

        // Include nav.php if $nav is true
        if ($nav) {
            $navPath = 'views/layout/navbar.php';
            if (file_exists($navPath)) {
                ob_start();
                include $navPath;
                $navContent = ob_get_clean();
                $content = $navContent . $content;
            }
        }

        // Include footer.php if $footer is true
        if ($footer) {
            $footerPath = 'views/layout/footer.php';
            if (file_exists($footerPath)) {
                ob_start();
                include $footerPath;
                $footerContent = ob_get_clean();
                $content .= $footerContent;
            }
        }

        return $content;
    }

    /**
     * Returns a view from errors folder
     *
     * @param string $name
     * @return string|false
     */
    public static function error(string $name): mixed
    {
        $view = new self('views/errors/' . $name . '.php');
        return $view->render();
    }

    /**
     * Returns a view from layout folder
     *
     * @param string $name
     * @return string|false
     */
    public static function layout(string $name): mixed
    {
        $view = new self('views/layout/' . $name . '.php');
        return $view->render();
    }

    /**
     * Renders the view
     *
     * @param array $data
     * @return string|false
     */
    public function render($data = []): mixed
    {
        extract($data); // Extract data array to individual variables

        // Start output buffering to capture HTML content
        ob_start();

        // Include the view file
        include $this->viewPath;

        // Get the content from the buffer
        $content = ob_get_clean();
        // Replace {{ ... }} with PHP code
        $content = preg_replace_callback('/\{\{(.+?)\}\}/s', function ($matches) {
            $lines = explode("\n", $matches[1]);
            $result = '';

            foreach ($lines as $line) {
                $code = trim($line);

                // Skip empty lines
                if (empty ($code)) {
                    continue;
                }

                // Replace {{variable}}, {{CONSTANT}}, or {{function()}}
                if (strpos($code, '$') === 0) {
                    // It's a variable
                    $result .= "<?=" . $code . "?>";
                } elseif (strpos($code, '(') !== false || strpos($code, '::') !== false) {
                    // It's a function or method
                    $result .= "<?=" . $code . "?>";
                } elseif (preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $code)) {
                    // It's a constant
                    $result .= "<?=" . $code . "?>";
                } else {
                    // It's a statement
                    $result .= "<?=" . $code . "?>";
                }
            }

            return $result;
        }, $content);



        // Evaluate the PHP code in the content
        ob_start();
        eval ('?>' . $content);
        $content = ob_get_clean();

        // Return the rendered view content
        return $content;
    }


}
