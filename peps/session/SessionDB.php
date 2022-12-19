<?php

declare(strict_types=1);

namespace peps\session;

use Exception;
use peps\core\Cfg;
use peps\core\DBAL;
use SessionHandlerInterface;

/**
 * Gestion des sessions en DB.
 * Design Pattern Singleton.
 * NECESSITE une table (nommée 'Session' par défaut, constante 'SESSION_TABLE' dans Cfg) avec 3 colonnes :
 *   'sid' (CHAR(N), PK) : longueur N définie par la constante SID_LENGTH dans Cfg)
 *   'data' (TEXT)
 *   'dateSession' (DATETIME)
 * 3 modes possibles (@see SessionMode) : PERSISTENT, HYBRID, ABSOLUTE.
 */
final class SessionDB implements SessionHandlerInterface
{
	/**
	 * Instance Singleton.
	 *
	 * @var self|null
	 */
	private static ?self $instance = null;
	/**
	 * Vrai si session périmée.
	 */
	private bool $expired = false;
	/**
	 * Durée maxi de la session (secondes).
	 */
	private int $timeout;

	/**
	 * Constructeur privé.
	 */
	private function __construct() {}

	/**
	 * Initialise et démarre la session.
	 *
	 * @param integer $timeout Durée maxi de la session (secondes).
	 * @param SessionMode $mode Mode de la session (PERSISTENT | HYBRID | ABSOLUTE).
	 */
	public static function init(int $timeout, SessionMode $mode): void {
		// Si déjà initialisée, ne rien faire.
		if(self::$instance)
			return;
		// Créer l'instance Singleton.
		self::$instance = new self();
		// Définir le timeout.
		self::$instance->timeout = $timeout;
		// Définir la durée de vie du cookie en fonction du mode.
		$cookieLifetime = match($mode) {
			SessionMode::PERSISTENT => 86400 * 365 * 20, // 20 ans
			SessionMode::HYBRID => 0, // Cookie de session (et timeout géré par le serveur).
			SessionMode::ABSOLUTE => $timeout // Cookie à durée limitée par le timeout.
		};
		ini_set('session.cookie_lifetime', $cookieLifetime);
		// Définir le timeout de GC pour supprimer les sessions expirées.
		ini_set('session.gc_maxlifetime', $timeout);
		// Ne pas démarrer automatiquement les sessions.
		ini_set('session.auto_start', 0);
		// Définir la longueur du SID.
		ini_set('session.sid_length', Cfg::get('SID_LENGTH'));
		// Utiliser les cookies.
		ini_set('session.use_cookies', 1);
		// Utiliser seulement les cookies.
		ini_set('session.use_only_cookies', 1);
		// Ne pas passer l'ID de session en GET.
		ini_set('session.use_trans_sid', 0);
		// Mitiger les attaques XSS (Cross Site Scripting = injections) en interdisant l'accès aux cookies via JS.
		ini_set('session.cookie_httponly', 1);
		// Mitiger les attaques SFA (Session Fixation Attack) en refusant les cookies non générés par PHP.
		ini_set('session.use_strict_mode', 1);
		// Mitiger les attaques CSRF (Cross Site Request Forgery).
		ini_set('session.cookie_samesite', 'Strict');
		// Définir l'instance Singleton comme gestionnaire des sessions.
		session_set_save_handler(self::$instance);
		// Démarrer la session.
		var_dump("SessionDB.init() : ABOUT TO START");
		session_start(); // open(), read()
		var_dump("SessionDB.init() : STARTED");
		// Si read() a détecté une session expirée, la détruire et en démarrer une nouvelle.
		if(self::$instance->expired) {
			var_dump("SessionDB.init() : EXPIRED, ABOUT TO DESTROY");
			session_destroy(); // destroy() qui supprime le cookie dans le navigateur.
			var_dump("SessionDB.init() : DESTROYED");
			self::$instance->expired = false;
			var_dump("SessionDB.init() : ABOUT TO RESTART");
			session_start();
		}
	}

	/**
	 * Inutile ici.
	 *
	 * @param string $path Chemin du fichier de sauvegarde de la session.
	 * @param string $name Nom de la session (PHPSESSID par défaut).
	 * @return boolean Pour usage interne PHP, ici systématiquement true.
	 */
	public function open(string $path, string $name): bool {
		// var_dump("SessionDB.open({$path}, {$name})");
		return true;
	}

	/**
	 * Lit et retourne les données de session.
	 *
	 * @param string $id SID.
	 * @return string|false Données de session sérialisées (PHP) ou false si lecture impossible.
	 * @throws DBALException
	 */
	public function read(string $id): string|false {
		var_dump("SessionDB.read({$id})");
		// Récupérer le nom de la table.
		$tableName = Cfg::get('SESSION_TABLE');
		// Créer la requête.
		$q = "SELECT * FROM {$tableName} WHERE sid = :sid";
		$params = [':sid' => $id];
		// Exécuter la requête.
		$objSession = DBAL::get()->xeq($q, $params)->findOne();
		// Si session présente mais expirée, définir $expired à true et retourner une chaîne vide.
		if($objSession && strtotime($objSession->dateSession) + $this->timeout < time()) {
			var_dump("SessionDB.read({$id}) : EXPIRED");
			$this->expired = true;
			return '';
		}
		// Si session présente, retourner les données.
		if($objSession) {
			var_dump("SessionDB.read({$id}) : FOUND");
			return $objSession->data;
		}
		// Sinon, retourner une chaîne vide.
		var_dump("SessionDB.read({$id}) : NOT FOUND");
		return '';
	}

	/**
	 * Ecrit les données de session.
	 *
	 * @param string $id SID.
	 * @param string $data Données de session.
	 * @return boolean Pour usage interne PHP, ici systématiquement true.
	 * @throws DBALException
	 */
	public function write(string $id, string $data): bool {
		var_dump("SessionDB.write({$id}) : {$data}");
		// Récupérer une instance de DBAL.
		$dbal = DBAL::get();
		// Si la session n'a pas expiré...
		if(!$this->expired) {
			// Récupérer le nom de la table.
			$tableName = Cfg::get('SESSION_TABLE');
			// Définir les paramètres de la requête.
			$params = [':sid' => $id, ':data' => $data, ':dateSession' => date('Y-m-d H:i:s')];
			try {
				// Tenter une requête INSERT.
				$q = "INSERT INTO {$tableName} VALUES (:sid, :data, :dateSession)";
				$dbal->xeq($q, $params);
				var_dump("SessionDB.write({$id}) : INSERT {$data}");
			} catch (Exception) {
				// Si erreur (doublon de SID), exécuter une requête UPDATE.
				$q = "UPDATE {$tableName} SET data = :data, dateSession = :dateSession WHERE sid = :sid";
				$dbal->xeq($q, $params);
				var_dump("SessionDB.write({$id}) : UPDATE {$data}");
			}			
		}
		// Retourner systématiquement true.
		return true;
	}

	/**
	 * Inutile ici.
	 *
	 * @return boolean Pour usage interne PHP, ici systématiquement true.
	 */
	public function close(): bool {
		var_dump("SessionDB.close()");
		return true;
	}

	/**
	 * Détruit la session (cookie uniquement, l'enregistrement en DB sera supprimé par GC).
	 *
	 * @param string $id SID.
	 * @return boolean Pour usage interne PHP, ici systématiquement true.
	 */
	public function destroy(string $id): bool {
		var_dump("SessionDB.destroy({$id})");
		// Récupérer le nom de la session.
		$sessionName = session_name();
		// Supprimer le cookie du navigateur.
		setcookie($sessionName, '', 1, '/');
		// Supprimer la clé du tableau des cookies du serveur.
		unset($_COOKIE[$sessionName]);
		// Retourner systématiquement true.
		return true;
	}

	/**
	 * Garbage Collector, supprime les sessions expirées en DB.
	 *
	 * @param int $max_lifetime Durée de vie maxi d'une session (secondes).
	 * @return integer|false True si la suppression a réussi, false sinon.
	 * @throws DBALException
	 */
	public function gc(int $max_lifetime): int|false {
		var_dump("SessionDB.gc({$max_lifetime})");
		// Récupérer le nom de la table.
		$tableName = Cfg::get('SESSION_TABLE');
		// Créer la requête.
		$q = "DELETE FROM {$tableName} WHERE dateSession < :dateMin";
		$params = [':dateMin' => date('Y-m-d H:i:s', time() - $max_lifetime)];
		// Retourner le nombre d'enregistrements supprimés.
		return DBAL::get()->xeq($q, $params)->getNb();
	}
}
