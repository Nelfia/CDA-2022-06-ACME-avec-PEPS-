<?php

declare(strict_types=1);

use classes\Autoload;
use entities\Category;
use entities\Product;
use entities\User;

require './classes/Autoload.php';
// Initialisation de l'autoload
Autoload::init();

// Ouverture de la session utilisateur
session_start();

// Si user non logué, rediriger
if(!User::getLoggedUser()) {
    header('Location:signin.php');
    exit('Redirection: Session invalide !');
}

// Initialiser le tableau des erreurs
$errors = [];
// Créer un produit
$product = new Product();
// Tenter de récupérer l'idCategory ou l'idProduct en GET + sécurité
$idCategory = filter_input(INPUT_GET, 'idCategory', FILTER_VALIDATE_INT);
$idProduct = filter_input(INPUT_GET, 'idProduct', FILTER_VALIDATE_INT);
$submit = filter_input(INPUT_POST, 'idProduct', FILTER_SANITIZE_SPECIAL_CHARS);

// Si arrivée en ajout ...
if($idCategory !== null) {
	// Si idCategory invalide, rediriger.
	if(!$idCategory || $idCategory <= 0) {
		header('Location:error404.php');
		exit('Redirection: idCategory invalide !');
	}
	// Définir l'idCategory du produit pour caler le menu déroulant
	$product->idCategory = $idCategory;
} 
// Si arrivée en modification ...
elseif ($idProduct !== null) {
	// Si idProduct invalide, rediriger.
	if(!$idProduct || $idProduct <= 0) {
		header('Location:error404.php');
		exit('Redirection: $idProduct invalide !');
	}
	// Tenter d'hydrater le produit
	$product->idProduct = $idProduct;
	// Si échec, rediriger
	if(!$product->hydrate()){
		header('Location:error404.php');
		exit('Redirection: $idProduct invalide !');
	}
}
// Si arrivée en validation ...
elseif($submit !== null) {
	// Récupérer et valider les données
	$product->idProduct = filter_input(INPUT_POST, 'idProduct', FILTER_VALIDATE_INT) ?: null;
	$product->idCategory = filter_input(INPUT_POST, 'idCategory', FILTER_VALIDATE_INT) ?: null;
	if(!$product->idCategory || $product->idCategory <= 0)
		$errors[] = Product::ERROR_INVALID_CATEGORY;
	$product->name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?: null;
	if(!$product->name || mb_strlen($product->name) > 50)
		$errors[] = Product::ERROR_INVALID_NAME;
	$product->ref = filter_input(INPUT_POST, 'ref', FILTER_SANITIZE_SPECIAL_CHARS) ?: null;
	if(!$product->ref || mb_strlen($product->ref) > 10)
		$errors[] = Product::ERROR_INVALID_REF;
	$product->price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT) ?: null;
	if(!$product->price || $product->price <= 0 || $product->price > 10000)
		$errors[] = Product::ERROR_INVALID_PRICE;
	// Si aucune erreur, persister le produit et rediriger
	if(!$errors){
		// Persister en tenant compte des éventuels doublons de référence
		try {
			$product->persist();			
		} catch (PDOException $e) {
			var_dump($e->getLine(), $e->getMessage());
			$errors[] = Product::ERROR_INVALID_DUPLICATE_REF;
		}
		// Si toujours aucune erreur, rediriger.
		if(!$errors){
			header('Location:listProducts.php');
			exit('Redirection: Persistance terminée !');
		}
	}

}

// Dans tous les cas, récupération du tableau des catégories (pr peupler menu déroulant)
$categories = Category::all();

// Transformer tableau de string en UNE string séparée par un <br/>


?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<title>ACME</title>
	<link rel="stylesheet" href="assets/css/acme.css" />
</head>

<body>
	<?php require 'inc/header.php' ?>
	<main>
		<div class="category">
			<a href="/listProducts.php">Produits</a> &gt; Editer
		</div>

		<!-- Transformer tableau de string en UNE string séparée par un <br/> (cf. doc)  -->
		<div class="error"><?= implode('<br/>', $errors);?></div>

		<form name="form1" action="/editProduct.php" method="POST">

			<input type="hidden" name="idProduct" value="<?= $product->idProduct ?>" />
			<div class="item">
				<label>Catégorie</label>
				<select name="idCategory">
					<?php 
					foreach($categories as $category){
						$selected = $category->idCategory === $product->idCategory ? "selected" : "";
					?>
						<option value="<?= $category->idCategory ?>" <?= $selected ?>>
							<?= $category->name ?> 
						</option>
					<?php 
					} 
					?>
				</select>
			</div>
			<div class="item">
				<label>Nom</label>
				<input name="name" value="<?= $product->name ?>" size="20" maxlength="50" />
			</div>
			<div class="item">
				<label>Référence</label>
				<input name="ref" value="<?= $product->ref ?>" size="10" maxlength="10" />
			</div>
			<div class="item">
				<label>Prix</label>
				<input type="number" name="price" value="<?= $product->price ?>" step=".01" />
			</div>
			<div class="item">
				<label></label>
				<a href="/listProducts.php"><input type="button" value="Annuler" /></a>
				<input type="submit" name="submit" value="Valider" />
			</div>
		</form>
	</main>
	<footer></footer>
</body>

</html>