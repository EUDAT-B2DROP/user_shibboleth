$(document).ready(function() {
	var hiddenElementIds = ['#enforce_domain_similarity', '#link_to_ldap_backend'];
	$.each(hiddenElementIds, function(index, id) {
		var checkboxId = $(id + '_checkbox');
		if ($(id).attr('value') == '1') {
			$(checkboxId).attr('checked', 'checked');
		} else {
			$(checkboxId).removeAttr('checked');
		}
	});
	
	$("#user_shibboleth fieldset input[type=checkbox]").on("change", function(event) {
		var hiddenElementId = '#' + (this.getAttribute('id')).slice(0, -9);
		if ($(this).attr('checked')) {
			$(hiddenElementId).attr('value', '1');
		} else {
			$(hiddenElementId).attr('value', '0');
		}
	});
	
	$("#user_shibboleth_personal_submit").click(function() {
		$.post('', {
			password:$("#user_shibboleth_personal_password").val()
			},
			function() {
				alert("Password set successfully");
			}
		).fail(function() {
			alert("Error: Unable to set password");
		});
		return false;
	});

});
