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

class Auth {
	private static function getAttribute($name) {
		$attributeName = \OCP\Config::getAppValue('user_shibboleth', $name, '');
		if (isset($attributeName) && $attributeName !== '' && isset($_SERVER[$attributeName]) && $_SERVER[$attributeName] !== '')
			return $_SERVER[$attributeName];
		return false;
	}

	public static function getShibIdentityProvider() {
		return Auth::getAttribute('identity_provider');
	}

	public static function getMail() {//used by login.php
		return Auth::getAttribute('mail_attr');
	}

	public static function getPersistentId() {//used by login.php
		return Auth::getAttribute('persistent_id_attr');
	}

	public static function getDisplayName() {//used by login.php
		return Auth::getAttribute('full_name_attr');
	}
}
