# ACME avec PEPS

Fabrication d'un Framework PHP nommé __PEPS__.

## Premier commit :
  * Duplication du site web TD : __ACME__
  * Modification de la classe __Autoload__
  * Création de la classe __AutoloadException__ qui étend __Exception__
  * Création de la classe __Cfg__ de PEPS
  * Création de la classe __CfgException__ de PEPS qui étend __Exception__
  * Création de la classe __CfgApp__ qui étend __Cfg__
  * Création de la classe __CfgLocal__ qui étend __CfgApp__
  * Création de la potentielle classe __CfgFree__ qui étend __CfgApp__
  * Tests

## Second commit :
 * Création, fabrication et sécurisation des données du fichier contenant les __routes__ (cfg/routes.json)
 * Création du __Router__ de PEPS
 * Création de la classe __RouterException__ qui étend __Exception__
