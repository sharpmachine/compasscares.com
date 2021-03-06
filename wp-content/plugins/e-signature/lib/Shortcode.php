<?php
/**
 * Shortcode Class
 *
 * Provides the Client side signature form shortcode
 * @since 0.1.0
 */

class WP_E_Shortcode{

	public function __construct(){
		$this->view = new WP_E_View();
		$this->invite = new WP_E_Invite;
		$this->document = new WP_E_Document;
		$this->signature = new WP_E_Signature;
		$this->user = new WP_E_User;
		$this->setting = new WP_E_Setting;
			
	}

	/**
	 * Validate document signature submission
	 * @since 1.0
	 * @param null
	 * @return Boolean
	 */
	private function doc_signature_validates(){
		
		$recipient_fname = trim($_POST['recipient_first_name']);
		$invite_hash = $_POST['invite_hash'];
		$checksum = $_POST['checksum'];
		$assets_dir = ESIGN_ASSETS_DIR_URI;

		$validity = true; // assume true, only false assertions are made

		$invitation = $this->invite->getInviteBy('invite_hash', $invite_hash);	

		// use checksum to ensure doc hasn't changed
		$document = $this->document->getDocument($invitation->document_id);

		// The checksum is calculated by appended the document's content to its id then generating a sha1 checksum from that value
		$doc_checksum = sha1($invitation->document_id . $document->document_content);
		
		// Enforce a legal name
		if(trim($recipient_fname) == ""){
			$this->view->setAlert(array( "type"=>"error", "message" => __( "First & Last Name are required", 'esig') ));
			$validity = false;
		}

		// if hash isn't here... 
		if(empty($invite_hash)){
			$this->view->setAlert(array( "type"=>"error", "message" => sprintf( __( "Oh snap! Carnegie, you've stumbled upon a broken URL. We're on the case. Let us know if the problem continues to persist. <p align='center'><img src='%s/images/boss.svg'></p>", 'esig'), $assets_dir ) ));
			$validity = false;
		}

		// if checksums don't match...
		elseif($checksum != $doc_checksum){
			$this->view->setAlert(array( "type"=>"error", "message" => __( "The document has been modified since it was sent to you. Please request a new invitation to sign", 'esig') ));
			$valid = false;
		}

		return $validity;
	}

	/**
	 * Sign Document Shortcode
	 * @since 0.1.0
	 */
	public function e_sign_document(){
		
		$assets_dir = ESIGN_ASSETS_DIR_URI;
			
		// GET - Display signed or unsigned signature form
		if(!isset($_POST['recipient_signature']) && empty($_POST['recipient_signature'])){
			
                 
			if($this->admin_can_view()){
				
				return $this->admin_preview();
			}
			
			// URL is expected to pass an invite hash and document checksum
			$invite_hash = isset($_GET['invite']) ? $_GET['invite'] : null;
			$checksum = isset($_GET['csum']) ? $_GET['csum'] : null ;
				
		     		
			if(empty($invite_hash) || empty($checksum)){
				
				if(get_transient('esig_current_url')){
				 $current_url=get_transient('esig_current_url');
				  delete_transient('esig_current_url');
                
				  wp_redirect($current_url);
				  exit;
				}
				
				$template_data = array( 
				"message" => sprintf( __("<p align='center' class='esig-404-page-template'><a href='http://www.approveme.me/wp-digital-e-signature/' title='Wordpress Digital E-Signature by Approve Me' target='_blank'><img src='%s/images/logo.png' alt='Sign Documents Online using WordPress E-Signature by Approve Me'></a></p><p align='center' class='esig-404-page-template'>Well this is embarrassing, but we can't seem to locate the document you're looking to sign online.<br>You may want to send an email to the website owner. <br>Thank you for using Wordpress Digital E-Signature By <a href='http://www.approveme.me/wp-digital-e-signature/' title='Free Document Signing by Approve Me'>Approve Me</a></p> <p align='center'><img src='" . $assets_dir . "/images/search.svg' alt='esignature by Approve Me' class='esig-404-search'><br><a class='esig-404-btn' href='http://www.approveme.me/wp-digital-e-signature?404'>Download WP E-Signature Free!</a></p>", 'esig'), $assets_dir ),
				);
				$this->displayDocumentToSign(null, '404', $template_data);
				return; // nothing to do here
			}
			
			// Grab invitation and recipient from invite hash
			$invitation = $this->invite->getInviteBy('invite_hash',$invite_hash);	
			$doc_id = $invitation->document_id;
			
			if($this->document->document_exists($doc_id)==0)  
			{   
				$template_data = array( 
				"message" => sprintf( __("<p align='center' class='esig-404-page-template'><a href='http://www.approveme.me/wp-digital-e-signature/' title='Wordpress Digital E-Signature by Approve Me' target='_blank'><img src='%s/images/logo.png' alt='Sign Documents Online using WordPress E-Signature by Approve Me'></a></p><p align='center' class='esig-404-page-template'>Well this is embarrassing, but we can't seem to locate the document you're looking to sign online.<br>You may want to send an email to the website owner. <br>Thank you for using Wordpress Digital E-Signature By <a href='http://www.approveme.me/wp-digital-e-signature/' title='Free Document Signing by Approve Me'>Approve Me</a></p> <p align='center'><img src='" . $assets_dir . "/images/search.svg' alt='esignature by Approve Me' class='esig-404-search'><br><a class='esig-404-btn' href='http://www.approveme.me/wp-digital-e-signature?404'>Download WP E-Signature Free!</a></p>", 'esig'), $assets_dir ),
				);
				$this->displayDocumentToSign(null, '404', $template_data);
				return; // nothing to do here
			}
		
			$recipient = $this->user->getUserdetails($invitation->user_id,$invitation->document_id);
			$template_data = array( 
				"invite_hash" => $invite_hash,
				"checksum" => $checksum,
				"recipient_first_name" =>$recipient->first_name,
				"ESIGN_ASSETS_URL" => ESIGN_ASSETS_DIR_URI ,
				"recipient_last_name" => $recipient->last_name,
				"recipient_id" => $recipient->user_id,
				"signature_classes" => "unsigned",
				"extra_attr" => "readonly",
			);
			
			// If the doc has already been signed by this user, add their signature and display read only
			if($this->signature->userHasSignedDocument($recipient->user_id, $doc_id)){
				/*$this->view->setAlert(array(
					"type" => "esig-updated", 
					"message" => "Looks like we have your signature on file already" 
				));*/
				$recipient_signature = stripslashes($this->signature->getDocumentSignature($recipient->user_id, $doc_id));

				$template_data["recipient_signature"] = $recipient_signature;
				$template_data["signature_classes"] = 'signed';
				$template_data["viewer_needs_to_sign"] = false;
				$template = "sign-preview";

			} else {
				//if already a transient
				 delete_transient('esig_current_url');
				$template_data["viewer_needs_to_sign"] = true;
				$template = "sign-document";

			}
			
			$this->document->recordView($invitation->document_id, $invitation->user_id, null);
			
			add_thickbox();
			$this->displayDocumentToSign($invitation->document_id, $template, $template_data);


		// POST - Handle signature submission
		} else {
			// for pdmi bug added this tra
			set_transient('esig_current_url',$_SERVER[REQUEST_URI]);
			if($this->doc_signature_validates()){
			   
				$invitation = $this->invite->getInviteBy('invite_hash', $_POST['invite_hash']);
				$doc_id = $invitation->document_id;

				// using the invitation grab the recipient user
				$recipient = $this->user->getUserdetails($invitation->user_id,$invitation->document_id);
				
				// User has already signed. Don't let them sign again
				if ($this->signature->userHasSignedDocument($invitation->user_id, $doc_id)) {
					
					$template_data = array(
						"invite_hash" => $_POST['invite_hash'],
						"recipient_signature" => $recipient_signature,
						"recipient_first_name" => $recipient->first_name,
				 	 	"recipient_last_name" => $recipient->last_name,
						"viewer_needs_to_sign" => false,
						"recipient_id" => '',
						"message" => __( "<p class=\"doc_title\" align=\"center\">You've already signed this document.</h2> <p align='center'></p>", 'esig' )
					);

					$this->displayDocumentToSign($invitation->document_id, "sign-preview", $template_data);
					return;
					
				}

				$signature_id = $this->signature->add($_POST['recipient_signature'], $recipient->user_id);

				// link this signature to this document in the document_signature join table
				$join_id = $this->signature->join($invitation->document_id, $signature_id);

				if(!$join_id){
					$this->view->setAlert(array("type"=>"error", "message"=>__( "there was an error attaching the signature to the document", 'esig')));
					error_log("Shortcode: e_sign_document: An error attaching the signature to the document");
					return;
				}
				
				// Update the recipient's first and last name
				if(!empty($_POST['recipient_first_name'])){ $f_name=$_POST['recipient_first_name'];}else { $f_name="";}
				if(!empty($_POST['recipient_last_name'])){ $l_name=$_POST['recipient_last_name'];}else { $l_name="";}
				$this->user->updateField($recipient->user_id, "first_name", trim($f_name));
				$this->user->updateField($recipient->user_id, "last_name", trim($l_name));

				$document = $this->document->getDocumentByID($doc_id);	
				
				// Fire post-sign action
				do_action('esig_signature_saved', array(
					'signature_id' => $signature_id,
					'recipient' => $recipient,
					'invitation' => $invitation,
					'post_fields' => $_POST,
				));
				
				$recipient_signature = stripslashes($_POST['recipient_signature']);
				$sender_signature = stripslashes($this->signature->getUserSignature($document->user_id));
				$sender = $this->user->getUserBy('user_id', $document->user_id);
                
                
                $success_msg = "<p class=\"success_title\" align=\"center\">Excellent work! You signed {$document->document_title} like a boss.</h2> <p align='center' class='s_logo'><img src='$assets_dir/images/boss.svg'></p>";
				
                $success_msg = apply_filters('esig-success-page-filter',$success_msg,array('document'=>$document));
                
                $template_data = array(
					"invite_hash" => $_POST['invite_hash'],
					"recipient_signature" => $recipient_signature,
					"recipient_first_name" => $recipient->first_name,
			 	 	"recipient_last_name" => $recipient->last_name,
					"viewer_needs_to_sign" => false,
					"notify" =>'yes',
					"message" => sprintf(__($success_msg, 'esig'))
				);

				$template = "sign-preview";
				$this->displayDocumentToSign($document->document_id, $template, $template_data);
				
				// setting extra transient for pdmi bug
				
			}
			else // ! Submission didn't validate
			{
				// display all errors 
				$this->view->renderAlerts();
			}
		}
	}
	


	/**
	 * Notify Document Owner/Admin via email when a document is signed.
	 * @since 1.0.1
	 */	
	public function notify_owner($document, $recipient, $audit_hash,$attachments=false){
		
		$owner = $this->user->getUserByWPID($document->user_id);

		// Prepare emails
		$recipient_email = $recipient->user_email;
		$user_email = $this->user->getUserEmail($document->user_id);
		
		$pageID = $this->setting->get_generic('default_display_page');
		$view_url = add_query_arg(array('esigpreview'=>1,'document_id'=>$document->document_id,'hash'=>$audit_hash), get_permalink($pageID));
		
        // adding apply filters 
        $esig_logo = apply_filters('esig_invitation_logo_filter','');
        if(empty($esig_logo)){
             $esig_logo = sprintf( __( '<a href="http://www.approveme.me/?ref=1" target="_blank"><span class="wp-e-signature-logo"></span></a> ', 'esig'), ESIGN_ASSETS_DIR_URI )  ; 
        }
        
        $esig_header_tagline = apply_filters('esig_invitation_header_tagline_filter','');
        if(empty($esig_header_tagline)){
             $esig_header_tagline = __( 'Sign Legally Binding Documents using a WordPress website', 'esig')  ; 
        }
        
        $esig_footer_head = apply_filters('esig_invitation_footer_head_filter','');
        if(empty($esig_footer_head)){
             $esig_footer_head = __( 'What is WP E-Signature?', 'esig')  ; 
        }
        
        $esig_footer_text = apply_filters('esig_invitation_footer_text_filter','');
        if(empty($esig_footer_text)){
            $esig_footer_text = __( 'WP E-Signature by Approve Me is the
                                fastest way to sign and send documents
                                using WordPress. Save a tree (and a
                                stamp).  Instead of printing, signing
                                and uploading your contract, the
                                document signing process is completed
                                using your WordPress website. You have
                                full control over your data - it never
                                leaves your server. <br>
                                <b>No monthly fees</b> - <b>Easy to use
                                  WordPress plugin.</b><a style="color:#368bc6;text-decoration:none" href="http://www.approveme.me/wp-digital-e-signature/?ref=1" target="_blank"> Learn more</a> ', 'esig');
        }
        
         $background_color_bg= apply_filters('esig-invite-button-background-color','');                           
         $background_color = !empty( $background_color_bg) ?  $background_color_bg : '#0083c5' ; 
                                        
        
		$template_data = array(
            'esig_logo'=> $esig_logo , 
            'esig_header_tagline'=>$esig_header_tagline,
            'esig_footer_head'=>$esig_footer_head,
            'esig_footer_text'=>$esig_footer_text,
			'document_title' =>  $document->document_title,
			'document_id' =>$audit_hash,
			'document_checksum' => $document->document_checksum,
			'owner_first_name' => $owner->first_name,
			'owner_last_name' => $owner->last_name,
			'owner_email' => $owner->user_email,
			'signer_name' => $recipient->first_name,
			'signer_email' => $recipient->user_email,
			'view_url' => $view_url,
			'assets_dir' => ESIGN_ASSETS_DIR_URI,
            'background_color' => $background_color,
		);
		
		$signed_message = $this->view->renderPartial('document_signed', $template_data, false, 'notifications/admin');
		
		$subject = "{$document->document_title} - Signed by {$recipient->first_name} ({$recipient->user_email})";

		$headers = array(
			"From: {$owner->first_name} {$owner->last_name} <{$owner->user_email}>",
			"Reply-To: {$owner->first_name} {$owner->last_name} <{$owner->user_email}>"
		);
		
		// send Email
		add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
         if($attachments){
		            $mailsent = wp_mail($owner->user_email, $subject, $signed_message, $headers,$attachments);
           }else {
                 $mailsent = wp_mail($owner->user_email, $subject, $signed_message, $headers);
           }
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		if(!$mailsent) {
		  $headers  = 'MIME-Version: 1.0' . "\r\n";
		  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		  $headers .= 'From: '. $owner->first_name . " " . $owner->last_name . '<'. $owner->user_email  .'>' . "\r\n";  
          if($attachments){
		            $mailsent = wp_mail($owner->user_email, $subject, $signed_message, $headers,$attachments);
           }else {
                 $mailsent = wp_mail($owner->user_email, $subject, $signed_message, $headers);
           }
		}
	  do_action('esig_notify_owner_sent',array('document'=>$document));
	}


	/**
	 * Notify signer via email when they sign a document.
	 * @since 1.0.1
	 */	
	public function notify_signer($document, $recipient, $post, $audit_hash,$attachments=false){
		
		$owner = $this->user->getUserByWPID($document->user_id);
		
		// Prepare emails
		$recipient_email = $recipient->user_email;
		
		$pageID = $this->setting->get_generic('default_display_page');
		$view_url = add_query_arg(array('invite'=>$post['invite_hash'], 'csum'=>$post['checksum'],'hash'=>$audit_hash), get_permalink($pageID));

		
        // adding apply filters 
        $esig_logo = apply_filters('esig_invitation_logo_filter','');
        if(empty($esig_logo)){
             $esig_logo = sprintf( __( '<a href="http://www.approveme.me/?ref=1" target="_blank"><span class="wp-e-signature-logo"></span></a> ', 'esig'), ESIGN_ASSETS_DIR_URI)  ; 
        }
        
        $esig_header_tagline = apply_filters('esig_invitation_header_tagline_filter','');
        if(empty($esig_header_tagline)){
             $esig_header_tagline = __( 'Sign Legally Binding Documents using a WordPress website', 'esig')  ; 
        }
        
        $esig_footer_head = apply_filters('esig_invitation_footer_head_filter','');
        if(empty($esig_footer_head)){
             $esig_footer_head = __( 'What is WP E-Signature?', 'esig')  ; 
        }
        
        $esig_footer_text = apply_filters('esig_invitation_footer_text_filter','');
        if(empty($esig_footer_text)){
            $esig_footer_text = __( 'WP E-Signature by Approve Me is the
                                fastest way to sign and send documents
                                using WordPress. Save a tree (and a
                                stamp).  Instead of printing, signing
                                and uploading your contract, the
                                document signing process is completed
                                using your WordPress website. You have
                                full control over your data - it never
                                leaves your server. <br>
                                <b>No monthly fees</b> - <b>Easy to use
                                  WordPress plugin.</b><a style="color:#368bc6;text-decoration:none" href="http://www.approveme.me/wp-digital-e-signature/?ref=1" target="_blank"> Learn more</a> ', 'esig');
        }
        
         $background_color_bg= apply_filters('esig-invite-button-background-color','');                           
         $background_color = !empty( $background_color_bg) ?  $background_color_bg : '#0083c5' ; 
          
		$template_data = array(
            'esig_logo'=> $esig_logo , 
            'esig_header_tagline'=>$esig_header_tagline,
            'esig_footer_head'=>$esig_footer_head,
            'esig_footer_text'=>$esig_footer_text,
			'document_title' => $document->document_title,
			'document_id' => $audit_hash,
			'document_checksum' => $document->document_checksum,
			'owner_first_name' => $owner->first_name,
			'owner_last_name' => $owner->last_name,
			'owner_email' => $owner->user_email,
			'signer_name' => $recipient->first_name,
			'signer_email' => $recipient->user_email,
			'view_url' => $view_url,
			'assets_dir' => ESIGN_ASSETS_DIR_URI,
            'background_color' => $background_color,
		);

		$signed_message = $this->view->renderPartial('document_signed', $template_data, false, 'notifications');
		
		$subject = "{$document->document_title} has been signed";

		$headers = array(
			"From: {$owner->first_name} {$owner->last_name} <{$owner->user_email}>",
			"Reply-To: {$owner->first_name} {$owner->last_name} <{$owner->user_email}>"
		);
		 
		// send Email
		add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
        if($attachments){
         
		$mailsent = wp_mail($recipient->user_email, $subject, $signed_message, $headers,$attachments);
		}else {
       
        $mailsent = wp_mail($recipient->user_email, $subject, $signed_message, $headers);
        }
        remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		if(!$mailsent) {
		  $headers  = 'MIME-Version: 1.0' . "\r\n";
		  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		  $headers .= 'From: '. $owner->first_name . " " . $owner->last_name . '<'. $owner->user_email  .'>' . "\r\n";  
		    if($attachments){
		            $mailsent = wp_mail($recipient->user_email, $subject, $signed_message, $headers,$attachments);
		    } else {
                 $mailsent = wp_mail($recipient->user_email, $subject, $signed_message, $headers);
            }
		} 
		// do action when email sent
		 
	}

	
	
	
	/**
	 * Displays a page where admins can view their document and see signatures
	 *
	 */
	public function admin_preview(){
		if(isset($_GET['document_id'])){
			$doc_id = $_GET['document_id'];
			
			$template_data = array( 
				"invite_hash" => '',
				"viewer_needs_to_sign"=>'',
				"recipient_id" => '',
				
			);
			
			$this->displayDocumentToSign($_GET['document_id'], "sign-preview", $template_data);
		}
	}

	/**
	 * Necessary callback method for wp_mail_content_type filter
	 *
	 * @since 0.1.0
	 */
	public function set_html_content_type(){
		return 'text/html';
	}

	// Should not be used to display secure information. Just html
	public function get_footer_ajax(){
		$args = array();
		//$template_data=array();
				$document_id=isset($_GET['document_id'])?$_GET['document_id']:$_GET['document_id'] ; 
		        $print_option=$this->print_option_display($document_id);
			
			if($print_option=="display") 
							$print_button='<a href="javascript:window.print()" class="agree-button" id="" title="">'.__('Print Document','esig').'</a>';
		
			$print_button=isset($print_button) ? $print_button : '' ; 
		// Default template data
			$template_data = array(
								'print_button' => $print_button 
			);
		
		  
		$template_data = apply_filters('esig-document-footer-data',$template_data,$args);
		
		
		
		// If is admin
		if(isset($_GET['preview']) && $_GET['preview']=="1"){
		   
			$this->view->renderPartial('_footer_admin',$template_data, true);
			
		// If is user
		} else {

			$invite_hash = isset($_GET['inviteCode']) ? $_GET['inviteCode'] : null;
			
			// Grab invitation and recipient from invite hash
			$invitation = $this->invite->getInviteBy('invite_hash', $invite_hash);	
			$recipient = $this->user->getUserdetails($invitation->user_id,$invitation->document_id);
				 
			// Viewer signed
			if($this->user->hasSignedDocument( $recipient->user_id, $invitation->document_id )){
			   
				$this->view->renderPartial('_footer_recipient_signed',$template_data, true);
			}
		}
		die();
	}


	/**
	 * Necessary callback method for wp_mail_content_type filter
	 *
	 * @since 0.1.0
	 */
	public function displayDocumentToSign($document_id, $template, $data = array()){

		$recipient_sig_html = "";
		$owner_sig_html = "";
		$audit_hash="" ; 
		
		if(isset($data['notify'])=='yes') {
		
			$document = $this->document->getDocument($document_id);	
			$doc_status = $this->document->getSignatureStatus($document_id);
		
			
 
			// If no more signatures are needed
			if(is_array($doc_status['signatures_needed']) && (count($doc_status['signatures_needed']) == 0)){
				
				// Update the document's status to signed
				$invitation = $this->invite->getInviteBy('invite_hash', $_POST['invite_hash']);
				$recipient = $this->user->getUserdetails($invitation->user_id,$document_id);
				$this->document->updateStatus($invitation->document_id, "signed");
			
				$this->document->recordEvent($document->document_id, 'all_signed', null, null);
				// this action is called when all signing request signed . 
				do_action('esig_all_signature_request_signed', array(
				'document' => $document,
				'recipients' => $recipients,
				'invitations' => $invitations,
				));
				// getting attachment 
				$attachments = apply_filters('esig_email_attachment',array('document' => $document));
				$audit_hash = $this->auditReport($document_id, $document , true); 
               
                if(is_array($attachments) || empty($attachments)){
                   
                  $attachments=false ; 
                }
				// Email all signers
               
				    foreach($doc_status['invites'] as $invite){
					    $this->notify_signer($document, $recipient, $_POST,$audit_hash,$attachments);
				    }
				
                
				$this->notify_owner($document, $recipient, $audit_hash,$attachments); // Notify admin
			
			// Otherwise, if the admin wants to be notified of each signature
			} else if($document->notify){
				$audit_hash = $this->auditReport($document_id, $document , true); 
				$this->notify_owner($document, $recipient, $audit_hash); // Notify admin
			}
			// do action after sending email 
			do_action('esig_email_sent',array('document'=>$document));
		}
		
		if($document_id){
        
		    if(isset($_GET['invite'])){
				set_transient('esig_invite',$_GET['invite']);
			}
            
			set_transient('esig_document_id',$document_id);
			
			$document = $this->document->getDocumentByID($document_id);
			$document_report = $this->auditReport($document_id, $document);
				
			// Grab sender and sender signature
			if(!empty($document->document_content))
			            $unfiltered_content = do_shortcode($this->signature->decrypt(ENCRYPTION_KEY, $document->document_content));
			
			$content=apply_filters('the_content', $unfiltered_content);
			
			$owner = $this->user->getUserBy('user_id', $document->user_id);
			
			//Get all other recipient signatures
			$sig_data = $this->document->getSignatureStatus($document_id);

			//If signer is viewing put their box in a different chunk
			foreach($sig_data['invites'] as $invite){
				$user_name =$this->setting->get_generic("esign_user_meta_id_". $invite->user_id ."_name_document_id_".$document_id) ? $this->setting->get_generic("esign_user_meta_id_". $invite->user_id ."_name_document_id_".$document_id) : $invite->first_name ; 
				$user_data = array(
					'user_name' => $user_name,
					'user_id' => $invite->user_id,
					'input_name' => 'recipient_signatures[]',
				);
				foreach($sig_data['signatures'] as $signature){
					if($signature->user_id == $invite->user_id){
						$sd = new DateTime($signature->sign_date);
						$sign_date= $sd->format('n/j/Y');
						$user_data['signature'] = $this->signature->getSignature($signature);
						$user_data['css_classes'] = 'signed';
						$user_data['by_line'] = 'Signed by';
						$user_data['sign_date'] = "Signed on: $sign_date";
					}
				}
				
				// If this is the viewer's signature box, don't add their sig box here
				if(isset($data['viewer_needs_to_sign']) &&  $data['viewer_needs_to_sign'] && isset($data['recipient_id']) == $invite->user_id){
					// Don't add
					if($document->document_type =="normal" ){
						    
                            $current_user_invite_hash=isset($_GET['invite'])?$_GET['invite']:null ; 
                            if($invite->invite_hash != $current_user_invite_hash)
                            {
                                $user_data['esig-tooltip'] = 'title="This signature section is assigned to '. $user_name .'"';
							    $recipient_sig_html .= $this->view->renderPartial('_signature_display', $user_data);
                            }
					}
				// All other signatures
				}  
				else {
					$recipient_sig_html .= $this->view->renderPartial('_signature_display', $user_data);
				}
				
			}
			
			
			
			$dt = new DateTime($document->date_created);
			$date4sort= $dt->format('m/d/Y');
			
			if(isset($_GET['hash'])){ $audit_hash="Audit Signature ID#" . $_GET['hash'] ; }
			else 
			{
			
			if($this->document->getSignedresult($document->document_id)){
			
			$audit_hash=$this->auditReport($document_id, $document,true) ;
			
			if($audit_hash!="") $audit_hash="Audit Signature ID#" . $audit_hash ; 
			
				}
				
			}
		
			// applying filter for document logo 
            $document_logo = apply_filters('esig_document_logo_filter','');
            
			// Default template data
			$template_data = array(
				"message" => $this->view->renderAlerts(),
				"document_title" => $document->document_title,
                "document_logo" => $document_logo,
				"document_date" => $date4sort,
				"document_id" => $document->document_checksum,
				"document_content" => $content,
				"action_url" =>$_SERVER["REQUEST_URI"],
				"sender_first_name" => $owner->first_name,
				"sender_last_name" => $owner->last_name,
				"owner_email" => $owner->user_email,
				"recipient_signatures" => $recipient_sig_html,
				"audit_report" => $document_report,
				"auditsignatureid" => $audit_hash,
				'blog_name' => get_bloginfo('name'),
				'blog_url' => get_bloginfo('url'),
			);
		}
		$template_data = apply_filters('esig-shortcode-display-owner-signature', $template_data , array('document' =>$document));
		// If additional data is sent, append it
		if(!empty($data)){
			foreach($data as $field => $datum){
				$template_data[$field] = $datum;
			}
		}
		
		// Apply filter
		$template_data = apply_filters('esig-shortcode-display-template-data', $template_data);
		
		// Render
		$this->view->render("documents", $template, $template_data, false);
		
		// Fire e-signature loaded action
		if(count($_POST) > 0)
				do_action('esig_signature_loaded', array('document_id' => $document_id,));
		
		//exit();
	}
	
	/***
	 *  Audit report used to display document view created report in signed
	 *  document footer . 
	 *  Since 1.0.0 
	 * */
	
	public function auditReport($id, &$document_data = null, $return_type=false){
		
		
		if(!$document_data){
			$document_data = $this->document->getDocument($id);	
		}
		
		// Get document report data
		
		$timeline = $this->document->auditReport($id, $document_data);
		
		// Set timezone
		date_default_timezone_set('UTC');
		
		

			$html = sprintf( __( '<div class="document-meta">
					<span class="doc_title">Audit Trail</span><br/>
					Document name: %1s<br/>
					Unique document ID: %2s<br/>
					Status: %3s
				</div>
				<ul class="auditReport">', 'esig'), $document_data->document_title, $document_data->document_checksum, $document_data->signature_status);

		// Sort
		ksort($timeline);
		
		$days = array();
		$audittrail="";
		
		$previous_day="";
		$html .= "<table class=\"day\">\n";
		foreach($timeline as $k => $val){
			//$date = date('l M jS h:iA e', $k);
			$val['timestamp'] = $k;
			$date4sort = date('Y:m:d', $k); 
			if($previous_day!=$date4sort){
				list($yyyy,$mm,$dd) = preg_split('/[: -]/',$date4sort);
				$day_timestamp = strtotime("$mm/$dd/$yyyy");
			
				$html .= "<th colspan=\"2\" class=\"day_label\">" . date("n/j/Y", $k) . "</th>\n";
			}
			
			// Creates Audit Trail Serial # Hash on Documents //
			$previous_day = $date4sort ; 
			
			$li = "<td class=\"time\">" . date("H:i:s e", $val['timestamp']) . "</td>";
			$li .= "<td class=\"log\">" . $val['log'] . "</td>";
			$html .= "<tr>$li</tr>";
			
			if((strpos($val['log'],"closed") > 0) && ($audittrail == ""))
				{
					
				$audittrail = $html;
				
				}
		}
			
			
		$html .= "</table>\n";
		$html = $html . "</ul>";
		 
		//$hash=wp_hash($audittrail);
		if($return_type)
				return $this->document->get_audit_signature_id($id, $document_data);
			
		
		//if($audittrail!=""){$html.="<br/>Audit Signature ID# ". $hash ."rupom"; };
		return $html;
		
	}	

	
	/**
	 * Checks if we're on an admin preview page
	 *
	 * @since 1.0.1
	 * @return Boolean
	 */
	public function admin_can_view(){
		
		// Editors and above can preview documents
		// TODO: Should authors be able to preview their own docs?
		//current_user_can('edit_pages') &&
         
        
		if(isset($_GET['esigpreview']) && $_GET['esigpreview']=="1"){
            if (!is_user_logged_in()){
                $redirect = home_url() . '/wp-login.php?redirect_to=' . urlencode( $_SERVER['REQUEST_URI'] );
                wp_redirect( $redirect );
                 exit;
            }else {
			return true;
            }
		} else {
       
			return false;
		}
		
	}
	
	/***
	 * Checks if Document id 
	 *
	 * @since 1.0.1
	 * @return Boolean
	 **/
	
	public function document_id_preview(){
		
		// Editors and above can preview documents
		// TODO: Should authors be able to preview their own docs?
		if(current_user_can('edit_pages') && isset($_GET['document_id'])){
			 
			return $_GET['document_id'];
			
		} else {
			return "test";
		}
		
	}
	
	/**
	 * Checks if print display
	 *
	 * @since 1.0.1
	 * @return string
	 */
	public function print_option_display($doc_id){
		
		if($this->setting->get_generic('esig_print_option'.$doc_id)){
		      $print_option=$this->setting->get_generic('esig_print_option'.$doc_id);
			  }
			  else 
			  {
			  $print_option=$this->setting->get_generic('esig_print_option');
            
			  }
			
		if(empty($print_option))
					$print_option=2;
				
		if($print_option==0){
				return $display="display" ; 
		}
		elseif($print_option==1)
		{
		      if($this->document->getSignedresult($doc_id))
							return $display="display" ;  
		}
		elseif($print_option==2){
				return $display="none" ; 
		}	
		elseif($print_option==4){
					
				if($this->document->getStatus($doc_id)=='awaiting'){
								return $display="display" ; 
								}
								else {
								return $display="none" ; 
								}
		}
		else{
				return $display="display" ; }
	}
	
	/*
	*  E-signature custom footer scripts
	*  Since 1.0.12
	*/
	
	public function esig_footer_scripts(){
	
		if (wp_is_mobile()){
		    $esig_mobile='1' ;
		}
		else {
		  $esig_mobile='0' ;
		}
	  $document_id=get_transient('esig_document_id');
	  $invite =get_transient('esig_invite');
	 // style 
	  echo "<link rel='stylesheet' id='bootstrap-css'  href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css?ver=3.9.1' type='text/css' media='all' />
			<link rel='stylesheet' id='bootstrap-theme-css'  href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css?ver=3.9.1' type='text/css' media='all' />";
	  
            if (wp_is_mobile()){
	  echo "<link rel='stylesheet' id='esig-style-css'  href='". plugins_url() ."/e-signature/assets/css/style_mobile.css?ver=1.0.9' type='text/css' media='screen' />
			<link rel='stylesheet' id='esig-theme-style-css'  href='". plugins_url() ."/e-signature/page-template/default/style_mobile.css?ver=3.9.1' type='text/css' media='all' />";
			}else {
	  echo "<link rel='stylesheet' id='esig-style-css'  href='". plugins_url() ."/e-signature/assets/css/style.css?ver=1.0.9' type='text/css' media='screen' />
			<link rel='stylesheet' id='esig-theme-style-css'  href='". plugins_url() ."/e-signature/page-template/default/style.css?ver=3.9.1' type='text/css' media='all' />";
			}
	  echo "<link rel='stylesheet' id='esig-theme-style-print-css'  href='". plugins_url() ."/e-signature/page-template/default/print_style.css?ver=1.0.9' type='text/css' media='print' />
			<link rel='stylesheet' id='thickbox-css'  href='". includes_url() ."/js/thickbox/thickbox.css?ver=3.9.1' type='text/css' media='all' />";
	// scripts 
		echo "<script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js'></script>";
		echo "<script type='text/javascript' src='". includes_url() ."/js/json2.min.js?ver=2011-02-23'></script>";
		echo "<script type='text/javascript' src='". plugins_url() ."/e-signature/assets/js/jquery.signaturepad.min.js'></script>";
        
		echo "<script type='text/javascript'>";
		$preview =isset($_GET['esigpreview'])? $_GET['esigpreview'] : null;
		echo '/* <![CDATA[ */
				var esigAjax = {"ajaxurl":"'. admin_url() .'/admin-ajax.php","preview":"'. $preview .'","document_id":"'. $document_id .'","invite":"'. $invite .'","esig_mobile":"'. $esig_mobile .'"};
			/* ]]> */ 
			</script>';
		
		echo "<script type='text/javascript' src='". plugins_url() ."/e-signature/assets/js/prefixfree.min.js'></script>";
		echo "<script type='text/javascript' src='". includes_url() ."/js/jquery/ui/jquery.ui.core.min.js?ver=1.10.4'></script>";
		echo "<script type='text/javascript' src='". includes_url() ."/js/jquery/ui/jquery.ui.widget.min.js?ver=1.10.4'></script>";
		echo "<script type='text/javascript' src='". includes_url() ."/js/jquery/ui/jquery.ui.position.min.js?ver=1.10.4'></script>";
		echo "<script type='text/javascript' src='". includes_url() ."/js/jquery/ui/jquery.ui.tooltip.min.js?ver=1.10.4'></script>";
		echo "<script type='text/javascript' src='". plugins_url() ."/e-signature/assets/js/tooltip.js?ver=3.9.1'></script>";
		echo "<script type='text/javascript' src='//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js'></script>";
		echo "<script type='text/javascript'>";
		echo '/* <![CDATA[ */
				var thickboxL10n = {"next":"Next >","prev":"< Prev","image":"Image","of":"of","close":"Close","noiframes":"This feature requires inline frames. You have iframes disabled or your browser does not support them.","loadingAnimation":"'. site_url() .'/wp-includes/js/thickbox/loadingAnimation.gif"};
				/* ]]> */
			</script>';
		echo "<script type='text/javascript' src='". includes_url() ."/js/thickbox/thickbox.js?ver=3.1-20121105'></script>";
        echo "<script type='text/javascript' src='". plugins_url() ."/e-signature/assets/js/signdoc.js?ver=1.0'></script>";
	}
	
	/*
	*  E-signature custom header scripts
	*  Since 1.0.12
	*/
	
	public function esig_header_scripts()
		{
		
			$document_id=get_transient('esig_document_id');
			echo "<script type='text/javascript' src='". includes_url() ."/js/jquery/jquery.js?ver=1.11.0'></script>";
			echo "<script type='text/javascript' src='". includes_url() ."/js/jquery/jquery-migrate.min.js?ver=1.2.1'></script>";
		}
	/*
	*  E-signature custom header 
	*  Since 1.0.12
	*/
	public function esig_head()
		{
			 $this->esig_header_scripts();
			 do_action('esig_head');
		}
		
	
	/*
	*  E-signature custom footer 
	*  Since 1.0.12
	*/
	
	public function esig_footer()
		{
				$this->esig_footer_scripts();
				do_action('esig_footer');
				// delete transient after loading footer
				delete_transient('esig_document_id');
				delete_transient('esig_invite');
		}
	
}