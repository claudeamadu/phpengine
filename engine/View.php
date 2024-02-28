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

        // Return the rendered view content
        return $content;
    }
}
