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

	public static function loginNameExists($loginName, $showDeleted = false) {
		$sql = 'SELECT COUNT(*) FROM *PREFIX*shibboleth_user WHERE login_name = ?';
		if(!$showDeleted) {
			$sql .= ' AND deleted_on IS NULL';
		}
		$stmt = \OC::$server->getDatabaseConnection()->prepare($sql);
		$result = $stmt->execute(array($loginName));
		
		if (!($result === false)) {
			$count = $stmt->fetchAll(\PDO::FETCH_COLUMN);
			if(is_array($count)) {
				return intval($count[0]) === 1;
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

	public static function getLoginNames($partialLoginName, $limit, $offset) {

		if ($limit === 0) {
			$limit = '0';
		}
		if ($offset === 0) {
			$offset = '0';
		}

		if (strlen($partialLoginName) === 0) {
			$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT login_name FROM *PREFIX*shibboleth_user WHERE deleted_on IS NULL', $limit, $offset); // LIMIT ? OFFSET ?');
			$result = $stmt->execute();
		} else {
			$partialLoginName = '%'.$partialLoginName.'%';
			$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT login_name FROM *PREFIX*shibboleth_user WHERE login_name LIKE ? AND deleted_on IS NULL',$limit, $offset); // LIMIT ? OFFSET ?');
			$result = $stmt->execute(array($partialLoginName));
		}

		if ($result !== false) {
			return $stmt->fetchAll(\PDO::FETCH_COLUMN);
		}
		return false;
	}

	public static function getDisplayName($loginName) {
		$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT display_name FROM *PREFIX*shibboleth_user WHERE login_name = ? AND deleted_on IS NULL');
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
			$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT `login_name`, `display_name` FROM *PREFIX*shibboleth_user WHERE deleted_on IS NULL', $limit, $offset);
			$result = $stmt->execute();
		}
		 else {
			$partialDisplayName = '%'.$partialDisplayName.'%';
			$stmt = \OC::$server->getDatabaseConnection()->prepare('SELECT login_name, display_name FROM *PREFIX*shibboleth_user WHERE display_name LIKE ? AND deleted_on IS NULL',$limit, $offset);
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

	public static function addUser($loginName, $displayName, $homeDir, $pid) {
		if (self::loginNameExists($loginName, true)) {
			\OCP\Util::writeLog(APP_NAME, "re-adding user: $loginName", \OCP\Util::INFO);
			$stmt = \OC::$server->getDatabaseConnection()->prepare('UPDATE *PREFIX*shibboleth_user SET deleted_on = NULL WHERE login_name = ?');
			$result = $stmt->execute(array($loginName));
		} else {
			\OCP\Util::writeLog(APP_NAME, "adding user: $loginName", \OCP\Util::INFO);
			$stmt = \OC::$server->getDatabaseConnection()->prepare('INSERT INTO *PREFIX*shibboleth_user values(?, ?, ?, ?, datetime("now"), NULL)');
			$result = $stmt->execute(array($loginName, $displayName, $homeDir, $pid));
		}
		return !($result === false);
	}

	public static function updateDisplayName($loginName, $displayName) {
		\OCP\Util::writeLog(APP_NAME, "renaming user: $loginName -> $displayName", \OCP\Util::INFO);
		$stmt = \OC::$server->getDatabaseConnection()->prepare('UPDATE *PREFIX*shibboleth_user SET display_name = ? WHERE login_name = ? AND deleted_on IS NULL');
		$result = $stmt->execute(array($displayName, $loginName));
		return !($result === false);
	}

	public static function deleteUser($loginName) {
		\OCP\Util::writeLog(APP_NAME, "deleting user: $loginName", \OCP\Util::INFO);
		$stmt = \OC::$server->getDatabaseConnection()->prepare('UPDATE *PREFIX*shibboleth_user SET deleted_on = datetime("now") WHERE login_name = ?');
		$result = $stmt->execute(array($loginName));
		return !self::loginNameExists($loginName);
	}
}
