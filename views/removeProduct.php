<?php

declare(strict_types=1);

require 'classes/Autoload.php';

use classes\Autoload;
use entities\Product;
use entities\User;

// Initialiser l'autoload
Autoload::init();

// Ouverture de la session utilisateur
session_start();

// Si user non logué, rediriger
if(!User::getLoggedUser()) {
    header('Location:signin.php');
    exit('Redirection: Session invalide !');
}

// Récupérer l'idProduct reçu en GET
$idProduct = filter_input(INPUT_GET, 'idProduct', FILTER_VALIDATE_INT);

var_dump($idProduct);
// Si idProduct invalide, rediriger
if(!$idProduct || $idProduct <= 0){
    header('Location:error404.php');
    exit('Redirection: idProduct invalide !');
}
// Créer le produit et le supprimer en DB
(new Product($idProduct))->remove();
// Rediriger.
header('Location:listProducts.php');
