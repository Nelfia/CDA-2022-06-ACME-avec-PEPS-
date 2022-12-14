<?php

declare(strict_types=1);

namespace peps\core;

/**
 * Classe 100% statique de configuration par défaut.
 * DOIT être étendue dans l'application par une classe ou plusieurs sous-classes de configuration.
 * Typiquement, une classe de configuration générale de l'application et une sous-classe par serveur
 */
class Cfg {
    /**
     * Tableau associatif des "constantes" de configuration.
     *
     * @var array
     */
    private static array $constants = [];

    /**
     * Constructeur privé
     */
    private function __construct() {}

    /**
     * Inscrit les constantes nécessaires au fonctionnement de PEPS.
     * DOIT être redéfinie dans chaque sous-classe pour y inscrire les constantes de l'application en invoquant parent::init() en première instruction.
     * Cette méthode DOIT être PUBLIC dans le dernier niveau des sous-classes qui seront appelées par le contrôleur frontal.
     * Les clés par défaut sont ici en SNAKE_CASE et sont les seules accessibles à PEPS.
     * Les clés ajoutées par les sous-classes DEVRAIENT être en camelcase.
     *
     * @return void
     */
    protected static function init() : void {
        // Chemin du fichier JSON des routes depuis la racine de l'application.
        self::register('ROUTES_FILE', 'cfg/routes.json');

        // Namespace des contrôleurs.
        self::register('CONTROLLERS_NAMESPACE', 'controllers');

        // Chemin du répertoire des vues depuis la racine de l'application.
        self::register('VIEWS_DIR', 'views');

        // Nom de la vue affichant l'erreur 404.
        self::register('ERROR_404_VIEW', 'error404.php');
    }

    /**
     * Inscrit une constante (paire clé/valeur) dans le tableau des constantes.
     *
     * @param string $key Clé.
     * @param mixed $val Valeur.
     * @return boolean True ou False selon que l'inscription a réussi ou non.
     */
    protected final static function register(string $key, mixed $val) : bool {
        // Si la clé existe déjà, ne rien faire et retourner false
        if(array_key_exists($key, self::$constants)) return false; 
        // Sinon, ajouter la paire et retourner true
        self::$constants[$key] = $val;
        return true;
    }

    /**
     * Retourne la valeur associée à la clé si existante.
     * Sinon, déclenche une exception.
     *
     * @throws CfgException Si clé inexistante.
     * @param string $key Clé.
     * @return mixed Valeur.
     */
    public final static function get(string $key) : mixed {
        if(array_key_exists($key, self::$constants)) return self::$constants[$key];
        throw new CfgException(CfgException::UNAVAILABLE_KEY);
    }

}