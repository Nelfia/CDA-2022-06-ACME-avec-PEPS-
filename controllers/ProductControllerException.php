<?php

declare(strict_types=1);

namespace controllers;

use Exception;

/**
 * Exceptions en lien avec ProductControllerException.
 * Classe 100% statique.
 * @see Entity
 */
final class ProductControllerException extends Exception {
    // Messages d'erreurs.
    public const INVALID_CATEGORY = "Invalid category.";
    public const INVALID_NAME = "Invalid name.";
    public const INVALID_REF = "Invalid ref.";
    public const INVALID_PRICE = "Invalid price.";
    public const INVALID_DUPLICATE_REF = "Invalid duplicate ref.";
}