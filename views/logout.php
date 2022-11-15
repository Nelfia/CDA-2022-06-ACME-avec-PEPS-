<?php

declare(strict_types=1);

use classes\Autoload;

require 'classes/Autoload.php';

// Initialiser l'autoload
Autoload::init();

// Ouverture de la session utilisateur
session_start();

// Détruire l'idUser en session
if(isset($_SESSION['idUser'])) unset($_SESSION['idUser']);
// Rediriger
header('Location:signin.php');
