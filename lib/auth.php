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
    const SHIB_IDENTITY_PROVIDER = 'Shib-Identity-Provider';
    const MAIL = 'mail';
    const PID = 'eppn';
    const TOKEN = 'auEduPersonSharedToken';
    const DN = 'cn';
	//can be used to check if shibboleth authentication has taken place
	public static function getShibIdentityProvider() {
		if (isset($_SERVER[self::SHIB_IDENTITY_PROVIDER]) && $_SERVER[self::SHIB_IDENTITY_PROVIDER] !== '')
			return $_SERVER[self::SHIB_IDENTITY_PROVIDER];
		return false;
	}
	public static function getMail() {//used by login.php
    	if (isset($_SERVER[self::MAIL]) && $_SERVER[self::MAIL] !== '')
        	return $_SERVER[self::MAIL];
        return false;
    }
    public static function getPersistentId() {//used by login.php
        if (isset($_SERVER[self::PID]) && $_SERVER[self::PID] !== '')
            return $_SERVER[self::PID];
        return false;
    }
    public static function getDisplayName() {
        if (isset($_SERVER[self::DN]) && $_SERVER[self::DN] != '')
            return $_SERVER[self::DN];
        return false;
    }
}
