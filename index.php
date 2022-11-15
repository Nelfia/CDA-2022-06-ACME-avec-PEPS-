<?php
declare(strict_types=1);

use cfg\CfgLocal;
use peps\core\Autoload;
use peps\core\AutoloadException;
use peps\core\CfgException;

require './peps/core/Autoload.php';

// Initialiser l'autoload (à faire EN PREMIER)
try {
    Autoload::init();
} catch (AutoloadException $e){
    exit($e->getMessage());
}

// Initialiser la configuration en fonction de l'IP du serveur(à faire EN DEUXIEME).
$serverIP = filter_input(INPUT_SERVER , 'SERVER_ADDR', FILTER_VALIDATE_IP) ?: filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP);
if(!$serverIP) exit ("Server variable SERVER_ADDR unavailable");
// ICI VOS CLASSES DE CONFIGURATION EN FONCTION DES IP DE VOS SERVEURS.
// Antislash initial obligatoire ici.

$classe = match ($serverIP) {
    "127.0.0.1", "::1" => \cfg\CfgLocal::init(),
    "212.17.32.5" => \cfg\CfgFree::init(),
    default => throw new CfgException("Adresse IP non reconnue.")
};
