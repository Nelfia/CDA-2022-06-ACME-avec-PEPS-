<?php

declare(strict_types=1);

namespace entities;

use peps\core\Entity;

/**
 * Entité Product.
 * Toutes mes propriétés sont initialisées par défaut pour les éventuels formulaires de saisie.
 * 
 * @see Entity
 */
class Product extends Entity {
    /**
     * PK.
     *
     * @var integer|null
     */
    public ?int $idProduct = null;
    /**
     * FK de la categorie.
     *
     * @var integer|null
     */
    public ?int $idCategory = null;
    /**
     * Nom.
     *
     * @var string|null
     */
    public ?string $name = null;
    /**
     * Référence.
     *
     * @var string|null
     */
    public ?string $ref = null;
    /**
     * Prix.
     *
     * @var float|null
     */
    public ?float $price = null;
    /**
     * Catégorie du produit.
     * Chargement en lazy loading.
     *
     * @var Category|null
     */
    protected ?Category $category = null;

    /**
     * Constructeur.
     *
     * @param integer|null $idProduct
     */
    public function __construct(?int $idProduct = null) {
        $this->idProduct = $idProduct;
    }

    /**
     * Retourne la catégorie du produit en lazy loading.
     *
     * @return Category|null Catégorie.
     */
    public function getCategory(): ?Category {
        if ($this->category === null) {
            $this->category = Category::findOneBy(['idCategory' => $this->idCategory]);
        }
        return $this->category;
    }

}
