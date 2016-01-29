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

const APP_NAME = 'user_shibboleth';

require_once OC_App::getAppPath(APP_NAME) . '/appinfo/bootstrap.php';

OCP\App::registerAdmin(APP_NAME, 'settings');
OCP\App::registerPersonal(APP_NAME, 'personal');

// register user backend
OC_User::useBackend(new OCA\user_shibboleth\UserShibboleth());

// add settings page to navigation
$entry = array(
	'id' => 'user_shibboleth_settings',
	'order'=>1,
	'href' => OCP\Util::linkTo(APP_NAME, 'settings.php'),
	'name' => 'Shibboleth Authentication'
);

//add login button
$link = OCA\user_shibboleth\LoginLib::getForwardingPageUrl();
$buttonText = 'Shibboleth';
$federationName = OCP\Config::getAppValue(APP_NAME, 'federation_name', '');
if ($federationName !== '') {
	$buttonText .= ' – ' . $federationName;
}
$shibbolethLogin = array(
	'href'  => $link,
	'name'  => $buttonText
);
OC_App::registerLogIn($shibbolethLogin);
