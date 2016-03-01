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
		$query = \OCP\DB::prepare('SELECT COUNT(*) FROM *PREFIX*shibboleth_user WHERE login_name = ?');
		$result = $query->execute(array($loginName));

		if (!\OCP\DB::isError($result)) {
			$count = $result->fetchAll(\PDO::FETCH_COLUMN);
			return $count[0] == 1;//not all PHP/DBS combinations return result of type integer
		}
		return false;
	}


	public static function getHomeDir($loginName) {
		$query = \OCP\DB::prepare('SELECT home_dir FROM *PREFIX*shibboleth_user WHERE login_name = ?');
                $result = $query->execute(array($loginName));

                if (!\OCP\DB::isError($result)) {
                        $homeDirectories = $result->fetchAll(\PDO::FETCH_COLUMN);
                        if (count($homeDirectories) === 1)
                                return $homeDirectories[0];
                }
                return false;
	}


	public static function getLoginNames($partialLoginName, $limit, $offset) {//was getUsers
		//prepare and run query
		if ($limit === 0) {
			$limit = '0';
		}
		if ($offset === 0) {
			$offset = '0';
		}

		if (strlen($partialLoginName) === 0) {
			$query = \OCP\DB::prepare('SELECT login_name FROM *PREFIX*shibboleth_user', $limit, $offset); // LIMIT ? OFFSET ?');
			$result = $query->execute();
		} else {
			$partialLoginName = '%'.$partialLoginName.'%';
			$query = \OCP\DB::prepare('SELECT login_name FROM *PREFIX*shibboleth_user WHERE login_name LIKE ?',$limit, $offset); // LIMIT ? OFFSET ?');
			$result = $query->execute(array($partialLoginName));
		}

		//return result
		if (\OCP\DB::isError($result)) {
			return false;
		} else {
			return $result->fetchAll(\PDO::FETCH_COLUMN);
		}
	}

	public static function getDisplayName($loginName) {
		$query = \OCP\DB::prepare('SELECT display_name FROM *PREFIX*shibboleth_user WHERE login_name = ?');
		$result = $query->execute(array($loginName));

		if (!\OCP\DB::isError($result)) {
			$displayNames = $result->fetchAll(\PDO::FETCH_COLUMN);
			if (count($displayNames) === 1)
				return $displayNames[0];
		}
		return false;
	}

	public static function getDisplayNames($partialDisplayName, $limit, $offset=0) {
//		\OCP\Util::writeLog(APP_NAME, 'using query with limit & like ' . $partialDisplayName, \OCP\Util::ERROR);
		//prepare and run query
		if ($limit === 0) {
//			$limit = 0;
			$limit = '0';
		}
		if ($offset === 0||$offset==null) {
			$offset = '0';
		}

		if (strlen($partialDisplayName) === 0) {
//			$query = \OC_DB::prepare('SELECT `uid` FROM `*PREFIX*users` WHERE LOWER(`uid`) LIKE LOWER(?)', $limit, $offset);

			$query = \OCP\DB::prepare('SELECT `login_name`, `display_name` FROM *PREFIX*shibboleth_user', $limit, $offset);
//			$query = \OCP\DB::prepare('SELECT login_name, display_name FROM *PREFIX*shibboleth_user');
			$result = $query->execute();
		}
		 else {
			$partialDisplayName = '%'.$partialDisplayName.'%';
//			$query = \OCP\DB::prepare('SELECT login_name, display_name FROM *PREFIX*shibboleth_user WHERE display_name LIKE ? LIMIT ? OFFSET ?');
			$query = \OCP\DB::prepare('SELECT login_name, display_name FROM *PREFIX*shibboleth_user WHERE display_name LIKE ?',$limit, $offset);

			$result = $query->execute(array($partialDisplayName));
		}

		//return result
		if (!\OCP\DB::isError($result)) {
			$rows = $result->fetchAll(\PDO::FETCH_ASSOC);
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
		if (self::loginNameExists($loginName)) {
			\OCP\Util::writeLog(APP_NAME, "re-adding user: $loginName", \OCP\Util::INFO);
			$query = \OC::$server->getDatabaseConnection()->prepare('UPDATE *PREFIX*shibboleth_user SET deleted_on = "" WHERE login_name = ?');
			$result = $query->execute(array($loginName));
		} else {
			\OCP\Util::writeLog(APP_NAME, "adding user: $loginName", \OCP\Util::INFO);
			$query = \OC::$server->getDatabaseConnection()->prepare('INSERT INTO *PREFIX*shibboleth_user values(?, ?, ?, ?, datetime("now"), "")');
			$result = $query->execute(array($loginName, $displayName, $homeDir, $pid));
		}

		if ($result === false)
			return false;
		return true;
	}

	public static function updateDisplayName($loginName, $displayName) {
		\OCP\Util::writeLog(APP_NAME, "renaming user: $loginName -> $displayName", \OCP\Util::INFO);
		$query = \OC::$server->getDatabaseConnection()->prepare('UPDATE *PREFIX*shibboleth_user SET display_name = ? WHERE login_name = ?');
		$result = $query->execute(array($displayName, $loginName));
		if ($result === false)
			return false;
		return true;
	}

	public static function deleteUser($loginName) {
		\OCP\Util::writeLog(APP_NAME, "deleting user: $loginName", \OCP\Util::INFO);
		$query = \OC::$server->getDatabaseConnection()->prepare('UPDATE *PREFIX*shibboleth_user SET deleted_on = datetime("now") WHERE login_name = ?');
		$result = $query->execute(array($loginName));
		if ($result === false)
			return false;
		return true;
	}

}
