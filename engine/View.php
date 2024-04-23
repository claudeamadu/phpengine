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

        // Replace {{variable}}, {{CONSTANT}} or {{function()}}
        $content = preg_replace_callback('/\{\{(.+?)\}\}/', function ($matches) {
            $code = $matches[1];
            if (strpos($code, '$') === 0) {
                // It's a variable
                return "<?=" . $code . "?>";
            } elseif (strpos($code, '(') !== false || strpos($code, '::') !== false) {
                // It's a function or method
                return "<?=" . $code . "?>";
            } elseif (preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $code)) {
                // It's a constant
                return "<?=" . $code . "?>";
            }else{
                // It's a statement
                return "<?=" . $code . "?>";
            }
        }, $content);

        // Evaluate the PHP code in the content
        ob_start();
        eval ('?>' . $content);
        $content = ob_get_clean();

        // Return the rendered view content
        return $content;
    }


}
