<form id="user_shibboleth" action="#" method="post">
	<fieldset class="personalblock">
		<legend><strong><?php p($l->t('Shibboleth User Backend'));?></strong></legend>
		<label for="sessions_handler_url">Sessions HandlerURL:</label><input type="text" id="sessions_handler_url" name="sessions_handler_url" value="<?php p($_['sessions_handler_url']); ?>" title="<?php p($l->t('Value from shibboleth2.xml file.'));?>"><br/>
		<label for="session_initiator_location">SessionInitiator Location:</label><input type="text" id="session_initiator_location" name="session_initiator_location" value="<?php p($_['session_initiator_location']); ?>" title="<?php p($l->t('Value from shibboleth2.xml file.'));?>"><br/>
		<label for="federation_name"><?php p($l->t('Federation Name'));?>:</label><input type="text" id="federation_name" name="federation_name" value="<?php p($_['federation_name']); ?>" title="<?php p($l->t('Optional value shown on the login button.'));?>"><br/>
		<label for="external_user_quota"><?php p($l->t('Quota'));?>:</label><input type="text" id="external_user_quota" name="external_user_quota" value="<?php p($_['external_user_quota']); ?>" title="<?php p($l->t('Amount of disk space granted to external Shibboleth users.'));?>"><br/>
		<br>
		<legend><strong><?php p($l->t('Variables'));?></strong></legend>
		<label for="identity_provider"><?php p($l->t('Identity Provider'));?>:</label><input type="text" id="identity_provider" name="identity_provider" value="<?php p($_['identity_provider']); ?>" title="<?php p($l->t('Identity Provider'));?>"><br/>
		<br>
		<legend><strong><?php p($l->t('Attributes'));?></strong></legend>
		<label for="mail_attr"><?php p($l->t('Mail'));?>:</label><input type="text" id="mail_attr" name="mail_attr" value="<?php p($_['mail_attr']); ?>" title="<?php p($l->t('Mail'));?>"><br/>
		<label for="persistent_id_attr"><?php p($l->t('Persistent ID'));?>:</label><input type="text" id="persistent_id_attr" name="persistent_id_attr" value="<?php p($_['persistent_id_attr']); ?>" title="<?php p($l->t('Persistent ID'));?>"><br/>
		<label for="full_name_attr"><?php p($l->t('Full Name'));?>:</label><input type="text" id="full_name_attr" name="full_name_attr" value="<?php p($_['full_name_attr']); ?>" title="<?php p($l->t('Full Name'));?>"><br/>
		<br>
		<legend><strong><?php p($l->t('LDAP'));?></strong></legend>
		<label for="ldap_link_attribute"><?php p($l->t('Link Attribute'));?>:</label><input type="text" id="ldap_link_attribute" name="ldap_link_attribute" value="<?php p($_['ldap_link_attribute']); ?>" title="<?php p($l->t('Attribute used to map Shibboleth users to LDAP accounts.'));?>"><br/>
		<label for="ldap_uuid_attribute"><?php p($l->t('UUID Attribute'));?>:</label><input type="text" id="ldap_uuid_attribute" name="ldap_uuid_attribute" value="<?php p($_['ldap_uuid_attribute']); ?>" title="<?php p($l->t('Attribute used to create the UUID for a user'));?>"><br/>
		<input type="checkbox" id="enforce_domain_similarity_checkbox" title="<?php p($l->t('Reject users with differing email address domain and IdP domain.'));?>" /><label class="shib_check_box" for="enforce_domain_similarity_checkbox"><?php p($l->t('Enforce Domain Similarity'));?></label><br/>
		<input type="hidden" id="enforce_domain_similarity" value="<?php p($_['enforce_domain_similarity']); ?>" name="enforce_domain_similarity">
		<input type="checkbox" id="link_to_ldap_backend_checkbox" name="link_to_ldap_backend_checkbox" title="<?php p($l->t('Map Shibboleth users to LDAP accounts, based on the mail attribute.'));?>" /><label class="shib_check_box" for="link_to_ldap_backend_checkbox"><?php p($l->t('Link to LDAP Backend'));?></label><br/>
		<input type="hidden" id="link_to_ldap_backend" name="link_to_ldap_backend" value="<?php p($_['link_to_ldap_backend']); ?>" name="link_to_ldap_backend">
		<input type="submit" value="Save" />
	</fieldset>
</form>
