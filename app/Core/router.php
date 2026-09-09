<?php

class Router
{
    private array $routes = [];
// en los parametros esta string para decir que es una cadena de texto y callable para decir que es una funcion que se puede llamar, en este caso el handler es la funcion que se va a ejecutar cuando se haga una peticion a la ruta especificada.
    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(
        string $method,
        string $path,
        callable $handler
    ): void {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $path): void
    {
        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo '404 - Page not found';
            return;
        }

        $handler();
    }
}