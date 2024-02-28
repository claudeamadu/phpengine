<?php
/**
 * Route Manager
 */
class Route
{
    protected $routePath;

    /**
     * Initialize Route
     *
     * @param string $routePath
     */
    public function __construct(string $routePath)
    {
        $this->routePath = $routePath;
    }

    /**
     * Returns API route
     *
     * @param string $routePath
     * @return string
     */
    public static function api(string $routePath): string
    {
        $route = new self(ROUTE_URL . 'v' . API_VERSION . '/' . $routePath);
        return $route->display();
    }

    /**
     * Returns route
     *
     * @param string $routePath
     * @return string
     */
    public static function define(string $routePath): string
    {
        $route = new self(ROUTE_URL . '/' . $routePath);
        return $route->display();
    }

    /**
     * Returns route
     *
     * @return string
     */
    public function display(): string
    {
        return $this->routePath;
    }

    /**
     * Redirects to route
     *
     * @param string $routePath
     * @return void
     */
    public static function head($routePath)
    {
        header('Location: ' . ROUTE_URL . $routePath);
    }
}
