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
    const EPPN = 'eppn';
    const TOKEN = 'auEduPersonSharedToken';
    const DN = 'displayName';
	//can be used to check if shibboleth authentication has taken place
	public static function getShibIdentityProvider() {
		if (isset($_SERVER[Auth::SHIB_IDENTITY_PROVIDER]) &&
			$_SERVER[Auth::SHIB_IDENTITY_PROVIDER] !== '') {
			return $_SERVER[Auth::SHIB_IDENTITY_PROVIDER];
		}
		return false;
	}
	
	public static function getMail() {//used by login.php
    	if (isset($_SERVER[Auth::MAIL]) && $_SERVER[Auth::MAIL] !== '')
        	return $_SERVER[Auth::MAIL];
        return false;
    }
	
    public static function getPersistentId() {//used by login.php
        if (isset($_SERVER[Auth::TOKEN]) && $_SERVER[Auth::TOKEN] !== '')
            return $_SERVER[Auth::TOKEN];
        return false;
    }
    
    public static function getDisplayName() {
        if (isset($_SERVER[Auth::DN]) && $_SERVER[Auth::DN] != '')
            return $_SERVER[Auth::DN];
        return false;
    }

}
