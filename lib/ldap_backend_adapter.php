<?php
/**
 * ownCloud - user_shibboleth
 * 
 * Copyright (C) 2013 Andreas Ergenzinger andreas.ergenzinger@uni-konstanz.de
 *
 * This library is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this library. If not, see <http://www.gnu.org/licenses/>.
 */

namespace OCA\user_shibboleth;

require_once(__DIR__ . '/../../../lib/base.php');

/**
 * This class offers convenient access to the primary LDAP server used by the
 * LDAP user and group backend.
 */
class LdapBackendAdapter {

	private $enabled;
	private $connected = false;
	private $connection;
	private $ldap;
	private $access;


	function __construct() {
		$this->enabled = (\OCP\Config::getAppValue('user_shibboleth', 'link_to_ldap_backend', '0') === '1') &&
                        \OCP\App::isEnabled('user_shibboleth')  && \OCP\App::isEnabled('user_ldap');
	}

	private function connect() {
		if (!$this->connected) {
			$this->ldap = new \OCA\user_ldap\lib\LDAP();
			$this->connection = new \OCA\user_ldap\lib\Connection($this->ldap);
			$ocConfig = \OC::$server->getConfig();
			$userManager = new \OCA\user_ldap\lib\user\Manager($ocConfig,
					new \OCA\user_ldap\lib\FilesystemHelper(),
					new \OCA\user_ldap\lib\LogWrapper(),
					\OC::$server->getAvatarManager(),
					new \OCP\Image());
			$this->access = new \OCA\user_ldap\lib\Access($this->connection, $this->ldap, $userManager);
			$this->connected = true;
		}
	}

	public function getUuid($attr) {
		//check backend status
		if (!$this->enabled) {
			return false;
		}
		
		//retrieve UUID from LDAP server
		$this->connect();
		$linkattr = \OCP\Config::getAppValue('user_shibboleth', 'ldap_link_attribute', 'mail');
		$uuidattr = \OCP\Config::getAppValue('user_shibboleth', 'ldap_uuid_attribute', 'dn');
		$filter = $linkattr . '=' . $attr;
        $result = $this->access->searchUsers($filter, $uuidattr);
        if (count($result) === 1) {
        	return $this->access->dn2username($result[0]);
        }
        return false;
	}

	public function initializeUser($attr) {
		//check backend status
		if (!$this->enabled) {
			return false;
		}
	
		//retrieve UUID from LDAP server
		$this->connect();
		$linkattr = \OCP\Config::getAppValue('user_shibboleth', 'ldap_link_attribute', 'mail');
		$uuidattr = \OCP\Config::getAppValue('user_shibboleth', 'ldap_uuid_attribute', 'dn');
		$filter = $linkattr . '=' . $attr;
		$result = $this->access->searchUsers($filter, $uuidattr);
		if (count($result) === 1) {
			return $this->access->dn2ocname($result[0]);
		}
		return false;
	}
}

