<?php

declare(strict_types=1);

namespace controllers;

use entities\Category;
use entities\Product;
use entities\User;
use Exception;
use peps\core\Cfg;
use peps\core\Router;

/**
 * Classe 100% statique de gestion des produits.
 */
final class ProductController {
    /**
     * Constructeur privé
     */
    private function __construct() {}

    /**
     * Affiche la liste des produits par catégorie.
     * 
     * GET / 
     * GET /product/list
     *
     * @return void
     */
    public static function list() : void {
        // Récupérer toutes les categories dans l'ordre alphabétique.
        $categories = Category::findAllBy([], ['name'=>'ASC']);
        // Rendre la vue.
        Router::render('listProducts.php', ['categories' => $categories]);
    }

    /**
     * Affiche le détail d'un produit.
     * 
     * GET /product/show/{idProduct}
     *
     * @param array $assocParams Tableau associatif des paramètres.
     * @return void
     */
    public static function show(array $assocParams) : void {
        // Récupérer l'idProduct.
        $idProduct = (int)$assocParams['idProduct'];
        // Instancier le produit.
        $product = new Product($idProduct);
        // Hydrater le produit.
        if(!$product->hydrate())
            Router::render('error404.php');
        // Définir le chemin de l'image.
        $imagePath = "/assets/img/products/{$product->idCategory}_" . ($product->idProduct % 10) . ".png";
        // Définir le prix formatté.
        $formattedPrice = Cfg::get('appLocale2dec')->format($product->price);
        // Rendre la vue.
        Router::render('showProduct.php', ['product' => $product,'imagePath' => $imagePath, 'formattedPrice' => $formattedPrice]);
    }

    /**
     * Affiche le formulaire de saisie d'un nouveau produit.
     *
     * GET /product/create/{idCategory}
     * 
     * @param array $assocParams Tableau associatif des paramètres.
     * @return void
     */
    public static function create(array $assocParams) : void {
        // Si utilisateur non logué, rediriger.
        if(!User::getLoggedUser()) Router::redirect('/user/signin');
        // Créer un produit.
        $product = new Product();
        // Récupérer idCategory et caler le menu déroulant.
        $product->idCategory = (int)$assocParams['idCategory'];
        // Récupérer les catégories pour peupler le menu déroulant.
        $categories = Category::findAllBy([], ['name' => 'ASC']);
        // Rendre la vue
        Router::render('editProduct.php',['product' => $product, 'categories' => $categories]);
    }

    /**
     * Affiche le formulaire de modification d'un produit existant.
     *
     * GET /product/update/{idProduct}
     * 
     * @param array $assocParams Tableau associatif des paramètres.
     * @return void
     */
    public static function update(array $assocParams) : void {
        // Créer un produit.
        $product = new Product((int)$assocParams['idProduct']);
        // Si l'hydratratation échoue, erreur 404.
        if(!$product->hydrate()) 
            Router::render('error404.php');
        // Récupérer les catégories pour peupler le menu déroulant.
        $categories = Category::findAllBy([], ['name' => 'ASC']);
        // Rendre la vue
        Router::render('editProduct.php',['product' => $product, 'categories' => $categories]);
    }

    /**
     * Sauvegarde le produit en DB.
     * 
     * POST /product/save
     *
     * @return void
     */
    public static function save() : void {
        // Créer un produit.
        $product = new Product();
        // Initialiser le tableau des erreurs
        $errors = [];
        // Récupérer et valider les données
        $product->idProduct = filter_input(INPUT_POST, 'idProduct', FILTER_VALIDATE_INT) ?: null;
        $product->idCategory = filter_input(INPUT_POST, 'idCategory', FILTER_VALIDATE_INT) ?: null;
        if(!$product->idCategory || $product->idCategory <= 0)
            $errors[] = ProductControllerException::INVALID_CATEGORY;
        $product->name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?: null;
        if(!$product->name || mb_strlen($product->name) > 50)
            $errors[] = ProductControllerException::INVALID_NAME;
        $product->ref = filter_input(INPUT_POST, 'ref', FILTER_SANITIZE_SPECIAL_CHARS) ?: null;
        if(!$product->ref || mb_strlen($product->ref) > 10)
            $errors[] = ProductControllerException::INVALID_REF;
        $product->price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT) ?: null;
        if(!$product->price || $product->price <= 0 || $product->price > 10000)
            $errors[] = ProductControllerException::INVALID_PRICE;
        // Si aucune erreur, persister le produit et rediriger
        if(!$errors){
            // Persister en tenant compte des éventuels doublons de référence
            try {
                $product->persist();
            } catch (Exception) {
                $errors[] = ProductControllerException::INVALID_DUPLICATE_REF;
            }
            // Si toujours aucune erreur, rediriger.
            if(!$errors)
            Router::redirect('/');
        }
        // Récupérer les catégories pour peupler le menu déroulant.
        $categories = Category::findAllBy([], ['name' => 'ASC']);
        // Rendre la vue.
        Router::render('editProduct.php', ['product' => $product, 'categories' => $categories, 'errors' => $errors]);
    }

    /**
     * Supprime un produit.
     *
     * GET /product/remove/{idProduct}
     * 
     * @param array $assocParams Tableau des paramètres.
     * @return void
     */
    public static function remove(array $assocParams) : void {
        // Créer le produit tout en récupérant son idProduct puis le supprimer en DB
        (new Product((int)$assocParams['idProduct']))->remove();
        // Rediriger.
        Router::redirect('/');
    }
}