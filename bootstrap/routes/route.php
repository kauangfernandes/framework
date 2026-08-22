<?php

namespace Bootstrap\Routes;

use \Bootstrap\App\Server;
use \Bootstrap\App\Request;

/**
 * Classe Router para gerenciamento de rotas.
 */
class Route
{
    public function __construct() {}

    // Armazena as rotas registradas por método HTTP.
    protected static array $routes = ['GET' => [], 'POST' => [], 'DELETE' => [], 'PUT' => []];

    // Array (pilha) para armazenar os prefixos de grupo.
    private static array $prefix = ["/"];

    // 
    private static array $middlewares = [];

    //
    private static string $lastMethod = '';

    /**
     * Registra uma rota HTTP GET.
     * Registra uma rota HTTP POST.
     * Registra uma rota HTTP DELETE.
     * Registra uma rota HTTP PUT.
     * @param string $route A URI da rota.
     * @param array|callable $action A ação a ser executada.
     */

    public static function get(string $route, array|callable $action)
    {
        $route = self::formatRoute($route);
        $route = self::applyPrefix($route);
        self::$routes['GET'][$route] = $action;
        return new static;
    }

    public static function post(string $route, array|callable $action)
    {
        $route = self::formatRoute($route);
        $route = self::applyPrefix($route);
        self::$routes['POST'][$route] = $action;
        return new static;
    }

    public static function delete(string $route, array|callable $action)
    {
        $route = self::formatRoute($route);
        $route = self::applyPrefix($route);
        self::$routes['DELETE'][$route] = $action;
        return new static;
    }

    public static function put(string $route, array|callable $action)
    {
        $route = self::formatRoute($route);
        $route = self::applyPrefix($route);
        self::$routes['PUT'][$route] = $action;
        return new static;
    }

    /**
     * Formata as rotas registradas.
     */
    private static function formatRoute(string $route): string
    {
        // Formatar route para lowercase.
        $route = strtolower($route);

        // Remove espaços em branco no início e no final da rota.
        $route = trim($route, '/');

        // Normaliza a rota para remover barras duplas.
        $route = str_replace('//', '/', $route);

        return $route;
    }

    private static function applyPrefix(string $route): string
    {
        // Verifica se há prefixos de grupo para aplicar.
        if (!empty(self::$prefix)) {
            $temp_route = "";

            foreach (self::$prefix as $chave => $prefix) {
                // Concatena o prefixo com a rota atual.
                $temp_route = "{$temp_route}{$prefix}";

                if ($prefix != "/") {
                    // Adiciona uma barra antes do prefixo, se não for a raiz.
                    $temp_route .= "/";
                }
            }

            $route = "{$temp_route}{$route}";
        }

        return $route;
    }


    public function redirect(String $route, array $parametros = null){
        return header("location: {$route}");
    }
}
