<?php

declare(strict_types=1);

namespace cfg;

/**
 * Classe 100% statique de configuration de l'application pour le serveur local.
 * FINAL parce que dernier niveau d'héritage.
 * 
 * @see CfgApp
 */
final class CfgFree extends CfgApp {

    /**
     * Constructeur privé
     */
    private function __construct() {}

    /**
     * Initialise la configuration de l'application.
     * PUBLIC parce que dernier niveau d'héritage.
     *
     * @return void
     */
    public static function init() : void {
        // Initialiser la configuration de la classe parente.
        parent::init();
        var_dump(self::class);
        // Driver PDO de la DB.
        self::register('dbDriver', "mysql");

        // Hôte de la DB.
        self::register('dbHost', 'localhost');

        // Port de l'hôte de la DB.
        self::register('dbPort', '3306');

        // Nom de la DB.
        self::register('dbName', 'acme');

        // Identifiant de l'utilisateur de la DB.
        self::register('dbLog', 'root');

        // Mot de passe de la DB.
        self::register('dbPwd', '');

        // Jeu de caractère de la DB.
        self::register('dbCharset', 'utf8mb4');
    }
}