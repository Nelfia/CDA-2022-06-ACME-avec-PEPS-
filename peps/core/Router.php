<?php

declare(strict_types=1);

namespace peps\core;

use Exception;

/**
 * Classe 100% statique de routage
 * Offre 5 méthodes de routage:
 *      render(): rendre une vue.
 *      text() :envoyer du text brut (text/plain).
 *      json() : envoyer du JSON (application/json)
 *      dowload(): envoyer un fichier en flux binaire
 *      redirect(): rediriger côté client 
 * Toutes ces méthodes ARRETENT l'éxécution.
 */
final class Router {
    /**
     * Constructeur privé.
     */
    private function __construct() {}

    /**
     * Routage initial.
     * Analyse de la requête du client, détermine la route et invoque la méthode appropriér du contrôleur approprié.
     *
     * @return void
     */
    public static function route() : void {
        // Récupérer le verbe HTTP et l'URI de la requête client.
        $verb = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS) ?: filter_var($_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_SPECIAL_CHARS);
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_SPECIAL_CHARS) ?: filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_SPECIAL_CHARS);
        // Si pas de verbe ou pas d'URI, erreur 404.
        if(!$verb || !$uri) exit('ERREUR 404'); //TODO
        // Charger la table de routage JSON.
        $routesJSON = @file_get_contents(Cfg::get('ROUTES_FILE'));
        // Si fichier introuvable, exception.
        if(!$routesJSON) throw new RouterException(RouterException::JSON_ROUTES_FILE_UNAVAILABLE);
        // Décoder le JSON.
        $routes = json_decode($routesJSON);
        if(!$routes) 
            throw new RouterException(RouterException::JSON_ROUTES_FILE_CORRUPTED);
        // Parcourir la table de routage.
        foreach($routes as $route) {
            // Utiliser l'expression régulière de l'URI avec un slash final optionnel.
            // Délimiteur @ au lieu de / (pour les routes).
            $regexp = "@^{$route->uri}/?$@";
            // Préparer un tableau pour stcker les éventuels paramètres capturés
            $matches = [];
            // Si route correspondante est trouvée...
            if(!strcasecmp($verb, $route->verb) && preg_match($regexp, $uri, $matches)){
                // Supprimer le premier élément de $matches.
                array_shift($matches);
                // Si clés présentes mais nb de clés différent du nombre de valeurs, exception.
                if(isset($route->params) && count($matches) !== count($route->params))
                    throw new RouterException(RouterException::WRONG_PARAMS_ARRAY);
                // Si paramètres, combiner les noms des paramètres avec les valeurs de l'URI pour obtenur un tableau associatif.
                $assocParams = $matches ? array_combine($route->params, $matches) : [];
                // Séparer le nom du contrôleur du nom de la méthode.
                [$controller, $method] = explode('.', $route->method);
                var_dump($method);
            }
        }
    }
}