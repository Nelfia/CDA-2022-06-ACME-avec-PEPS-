<?php

declare(strict_types=1);

namespace entities;

use peps\core\Entity;

/**
 * Entité Category.
 * Toutes les propriétés sont initialisées par défaut pour les éventuels formulaires de saisie.
 * Chargement en Lazy Loading.
 * 
 * @see Entity
 */
class Category extends Entity
{
    /**
     * Clé primaire. PK.
     *
     * @var integer|null
     */
    public ?int $idCategory = null;
    /**
     * Nom de la catégorie.
     *
     * @var string|null
     */
    public ?string $name = null;
    /**
     * Tableau des produits de la catégorie.
     *
     * @var array|null
     */
    protected ?array $products = null;
    /**
     * Constructeur.
     *
     * @param integer|null $idCategory PK(facultatif).
     */
    public function __construct(?int $idCategory = null) {
        $this->idCategory = $idCategory;
    }

    /**
     * Retourne un tableau des produits (triés par nom) de la catégorie en lazy loading.
     *
     * @return array Tableau des produits.
     */
    public function getProducts(): array {
        if ($this->products === null) {
            // $q = "SELECT * FROM product WHERE idCategory = :idCategory ORDER BY name";
            $this->products = Product::findAllBy(['idCategory' => $this->idCategory], ['name' => 'ASC']);
        }
        return $this->products;
    }
}
