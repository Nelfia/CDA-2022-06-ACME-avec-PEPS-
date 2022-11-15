<?php

declare(strict_types=1);

namespace classes;

use Error;

abstract class Entity {
    // Retourne le résultat de l'invocation de la méthode get{PropertyName}() si existante. Exemple: si propriété 'truc', invoquer getTruc()
    // Sinon, retourne null.
    public function __get(string $propertyName): mixed
    {
        try {
            $methodName = "get" . ucfirst($propertyName);
            return $this->$methodName();
        } catch (Error) {
            return null;
        }
    }
}