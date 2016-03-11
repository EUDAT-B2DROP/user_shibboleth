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

OC_Util::checkAdminUser();
OCP\Util::addStyle(APP_NAME, 'settings');
OCP\Util::addScript(APP_NAME, 'settings');

$params = array('identity_provider', 'mail_attr', 'persistent_id_attr', 'full_name_attr', 'sessions_handler_url', 'session_initiator_location', 'federation_name', 'enforce_domain_similarity', 'link_to_ldap_backend', 'ldap_link_attribute', 'ldap_uuid_attribute', 'external_user_quota');
  
if($_POST) {
	foreach($params as $param) {
		if (isset($_POST[$param])) {
			OCP\Config::setAppValue(APP_NAME, $param, $_POST[$param]);
		}
	}
}

// fill template
$tmpl = new OCP\Template( 'user_shibboleth', 'settings');
$tmpl->assign('sessions_handler_url', OCP\Config::getAppValue('user_shibboleth', 'sessions_handler_url', '/Shibboleth.sso'));
$tmpl->assign('session_initiator_location', OCP\Config::getAppValue('user_shibboleth', 'session_initiator_location', '/Login'));
$tmpl->assign('federation_name', OCP\Config::getAppValue('user_shibboleth', 'federation_name', ''));
$tmpl->assign('enforce_domain_similarity', OCP\Config::getAppValue('user_shibboleth', 'enforce_domain_similarity', '1'));
$tmpl->assign('link_to_ldap_backend', OCP\Config::getAppValue('user_shibboleth', 'link_to_ldap_backend', '0'));
$tmpl->assign('ldap_link_attribute', OCP\Config::getAppValue('user_shibboleth', 'ldap_link_attribute', 'mail'));
$tmpl->assign('ldap_uuid_attribute', OCP\Config::getAppValue('user_shibboleth', 'ldap_uuid_attribute', 'dn'));
$tmpl->assign('external_user_quota', OCP\Config::getAppValue('user_shibboleth', 'external_user_quota', ''));
$tmpl->assign('identity_provider', OCP\Config::getAppValue('user_shibboleth', 'identity_provider', 'Shib-Identity-Provider'));
$tmpl->assign('mail_attr', OCP\Config::getAppValue('user_shibboleth', 'mail_attr', 'mail'));
$tmpl->assign('persistent_id_attr', OCP\Config::getAppValue('user_shibboleth', 'persistent_id_attr', 'eppn'));
$tmpl->assign('full_name_attr', OCP\Config::getAppValue('user_shibboleth', 'full_name_attr', 'cn'));

return $tmpl->fetchPage();
