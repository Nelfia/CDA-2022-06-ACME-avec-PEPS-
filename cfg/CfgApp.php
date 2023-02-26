<?php

declare(strict_types=1);

namespace cfg;

use NumberFormatter;
use peps\core\Cfg;
use peps\session\SessionMode;

/**
 * Classe 100% statique de configuration générale de l'application.
 * DEVRAIT être étendue par une sous-classe par serveur.
 * 
 * @see Cfg
 */
class CfgApp extends Cfg {

    /**
     * Constructeur privé
     */
    private function __construct() {}

    /**
     * Initialise la configuration de l'application.
     * PROTECTED parce que sous-classes présentes.
     *
     * @return void
     */
    protected static function init() : void {
        // Initialiser la configuration de la classe parente.
        parent::init();

        // Titre de l'application.
        self::register('appTitle', "ACME PEPS");

        // Locale.
        self::register('appLocale', 'fr-FR');

        // Devise.
        self::register('appCurrency', 'EUR');

        // Instance de NumberFormatter pour formater un nombre avec 2 décimales selon la locale.
        self::register('appLocale2dec', NumberFormatter::create(self::get('appLocale'), NumberFormatter::PATTERN_DECIMAL, '#,##0.00'));

        // Durée de vie des sessions (secondes).
        self::register('sessionTimeout', 300); // 5 minutes

        // Mode des sessions (PERSISTENT | HYBRID | ABSOLUTE).
        self::register('sessionMode', SessionMode::PERSISTENT);
    }
}