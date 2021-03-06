(function($){
	
	// check for existing signature
	var output = $('input[name="output"]');
	output = output[0];
	var sig = output.value;
	var popup_content_id = 'admin-signature'; //Id of the pop-up content
	
	var edit_opts = {
		drawOnly: true,
		validateFields : false,
		penColour: '#000000',
		lineWidth: '0',
		lineColour: 'rgba(255,255,255,0)',
		displayOnly:false, //useful for when re-signing
		bgColour : 'transparent'
	};

	var display_opts = {
		penColour: '#000000',
		displayOnly: true,
		bgColour : 'transparent',
	};
	
	var signaturePadEdit = $('.signature-wrapper').signaturePad(edit_opts);
	var signaturePadDisplay = $('.signature-wrapper-displayonly').signaturePad(display_opts);
	 
	if(sig != ""){
		 signaturePadDisplay.regenerate(sig);
		 signaturePadEdit.regenerate(sig);
	}


	
	$('.signature-wrapper-displayonly').click(function(){
	        
		tb_show("+ Add signature", '#TB_inline?width=450&height=168&inlineId=' + popup_content_id);
	});
	
	// Save signature
	$('.signature-wrapper .saveButton').click(function(){
		var output = $('input[name="output"]');
		if(!output.val()){
			return;
		}
		nonce = $(this).attr("data-nonce");
		var elem = this;
		signaturePadDisplay.regenerate(output.val());
		tb_remove();
		$(elem).removeClass('loading');
		$('.signature-wrapper-displayonly .sign-here').removeClass('unsigned').addClass('signed');
		/*$.ajax({
			type : "post",
			dataType : "json",
			url : esigAjax.ajaxurl,
			data : {
				action: "wp_e_signature_ajax",
				method: "update_ajax",
				className: "WP_E_settingsController",
				sig : output.val(), 
				nonce: $(elem).data("nonce")
			},
			success: function(data, status, jqXHR){
				signaturePadDisplay.regenerate(output.val());
				tb_remove();
				$(elem).removeClass('loading');
				$('.signature-wrapper-displayonly .sign-here').removeClass('unsigned').addClass('signed');
			},
			error: function(jqXHR, status, error){
				alert("Status: " + status);
				alert('signature ajax error:' + error);
               alert("xhr: " + xhr.readyState);
			},
			statusCode: {
               404: function() {
                   alert("page not found");
               }
            },
			beforeSend: function(){
				$(elem).addClass('loading');
			}
		}); */
		
	});
	
	// Modal dialog box for the super admin select . .

		var $overwrite = $("#esig-confirm-dialog");
		$overwrite.dialog({
			'dialogClass'   : 'wp-dialog esig-confirm-dialog',
			'title'         : 'Whoah there',
			'modal'         : true,
			'autoOpen'      : false,
			'buttons'       : {
				"Save": function() {
					$(this).dialog('close');
                   $('.button-appme').trigger('click');
				},
				"Cancel": function() {
                    var old_val = $('select option:selected').data('used'); 
                    
                    $('#esig_admin_user_id').val(old_val);
					$('#esig_admin_user_id').trigger('chosen:updated');
					$(this).dialog('close');
				}
			}
		});

        // On-change event for #stand_alone_page select menu.
		$('#esig_admin_user_id').change(function(){
			var selected = $('option:selected', this);
			     var new_val = $('#esig_admin_user_id option:selected').text();
                 $('#esig_selected_admin').html(new_val);
				$overwrite.dialog('open'); // Popup a dialog
			   
		});


	$('.settings-form').on("submit", function(e){

		var form = $(this);

		form.find(".error").remove(); //remove previous alerts

		var alerts = [];
		var valid = true;

		// validate text fields
		form.find("input[name='first_name'], input[name='last_name'], input[name='user_email'], input[name='user_title'], select[name='default_display_page'], input[name='output']").each(function(index){
			$(this).parent().find(".required-asterisk").remove(); //remove previous alerts
			$(this).removeClass("required-alert");

			if($(this).val() == ""){
				$(this).addClass("required-alert");
				$(this).parent().find("label").prepend("<span class='required-asterisk' style='color:red'>*</span>");
				valid = false;
			}
		});

		if(!valid){
			var alertmsg = '<div class="error"><p><strong>E-signature </strong> : The required fields must be filled in before saving them.</p></div>';
			$('form[name="settings_form"]').prepend(alertmsg);
			return false;
		}else{
			return true;
		}
	});
	
	
	$('#upload_company_logo').click(function() {
		tb_show('', 'media-upload.php?referer=e-signature&type=image&TB_iframe=true&post_id=0');
		return false;
	});

	window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		$('#company_logo').val(imgurl);
		tb_remove();
		$('#company_logo_image_wrap').hide();
	}

	
	
})(jQuery);
