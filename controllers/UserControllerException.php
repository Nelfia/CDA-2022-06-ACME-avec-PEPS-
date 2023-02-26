<?php

declare(strict_types=1);

namespace controllers;

use Exception;

/**
 * Exceptions en lien avec ProductControllerException.
 * Classe 100% statique.
 * @see Entity
 */
final class UserControllerException extends Exception {
    // Messages d'erreurs.
    public const LOGIN_FAILED = "Login failed.";
}