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
  
if($_POST) {
	$username = \OC_User::getDisplayName();
	$password = $_POST['password'];
	\OC_User::clearBackends();
	\OC_User::useBackend(new OC_User_Database());
	if (\OC_User::userExists($username)) {
		if (!empty($password) && \OC_User::setPassword($username, $password)) {
			\OC_JSON::success();
		} else {
			\OC_JSON::error();
		}
	} else {
		if (!empty($password) && \OC_User::createUser($username, $password)) {
			\OC_JSON::success();
		} else {
			\OC_JSON::error();
		}
	}
}

OCP\Util::addStyle('user_shibboleth', 'settings');
OCP\Util::addScript('user_shibboleth', 'settings');

// fill template
$tmpl = new OCP\Template( 'user_shibboleth', 'personal');

return $tmpl->fetchPage();
