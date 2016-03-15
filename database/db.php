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

class DB {

	public static function loginNameExists($loginName) {
		$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT COUNT(*) FROM *PREFIX*shibboleth_user WHERE login_name = ?');
		$result = $stmt->execute(array($loginName));
		
		if ($result !== false) {
			$count = $stmt->fetchAll(\PDO::FETCH_COLUMN);
			if(is_array($count)) {
				return intval($count[0]) === 1;//not all PHP/DBS combinations return result of type integer
			}
		}
		return false;
	}


	public static function getHomeDir($loginName) {
		$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT home_dir FROM *PREFIX*shibboleth_user WHERE login_name = ?');
		$result = $stmt->execute(array($loginName));
		
		if ($result !== false) {
			$homeDirectories = $stmt->fetchAll(\PDO::FETCH_COLUMN);
			if (is_array($homeDirectories) && count($homeDirectories) === 1)
				return $homeDirectories[0];
		}
		return false;
	}


	public static function getLoginNames($partialLoginName, $limit, $offset) {//was getUsers

		if ($limit === 0) {
			$limit = '0';
		}
		if ($offset === 0) {
			$offset = '0';
		}

		if (strlen($partialLoginName) === 0) {
			$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT login_name FROM *PREFIX*shibboleth_user', $limit, $offset); // LIMIT ? OFFSET ?');
			$result = $stmt->execute();
		} else {
			$partialLoginName = '%'.$partialLoginName.'%';
			$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT login_name FROM *PREFIX*shibboleth_user WHERE login_name LIKE ?',$limit, $offset); // LIMIT ? OFFSET ?');
			$result = $stmt->execute(array($partialLoginName));
		}

		if ($result !== false) {
			return $stmt->fetchAll(\PDO::FETCH_COLUMN);
		}
		return false;
	}

	public static function getDisplayName($loginName) {
		$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT display_name FROM *PREFIX*shibboleth_user WHERE login_name = ?');
		$result = $stmt->execute(array($loginName));

		if ($result !== false) {
			$displayNames = $stmt->fetchAll(\PDO::FETCH_COLUMN);
			
			if (count($displayNames) === 1) {
				return $displayNames[0];
			}
		}
		return false;
	}

	public static function getDisplayNames($partialDisplayName, $limit, $offset=0) {
		if ($limit === 0) {
			$limit = '0';
		}
		if ($offset === 0 || $offset === null) {
			$offset = '0';
		}

		if (strlen($partialDisplayName) === 0) {
			$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT `login_name`, `display_name` FROM *PREFIX*shibboleth_user', $limit, $offset);
			$result = $stmt->execute();
		}
		 else {
			$partialDisplayName = '%'.$partialDisplayName.'%';
			$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT login_name, display_name FROM *PREFIX*shibboleth_user WHERE display_name LIKE ?',$limit, $offset);
			$result = $stmt->execute(array($partialDisplayName));
		}

		if ($result !== false) {
			$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			$array = array();
			foreach ($rows as $row) {
				$loginName = $row['login_name'];
				$displayName = $row['display_name'];
				$array[$loginName] = $displayName;
			}
			return $array;
		}
		return false;
	}

	public static function addUser($loginName, $displayName, $homeDir) {
		$stmt = \OC::$server->getDatabaseConnection()->prepare('INSERT INTO *PREFIX*shibboleth_user values(?, ?, ?)');
		$result = $stmt->execute(array($loginName, $displayName, $homeDir));
		return $result !== false;
	}

	public static function updateDisplayName($loginName, $displayName) {
		$stmt = \OC::$server->getDatabaseConnection()->prepare('UPDATE *PREFIX*shibboleth_user SET display_name = ? WHERE login_name = ?');
		$result = $stmt->execute(array($displayName, $loginName));
		return $result !== false;
	}

	public static function deleteUser($loginName) {
		$stmt = \OC::$server->getDatabaseConnection()->prepare('DELETE FROM *PREFIX*shibboleth_user WHERE login_name = ?');
		$result = $stmt->execute(array($loginName));
		return $result !== false;
	}
}
