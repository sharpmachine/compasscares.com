<?php include($this->rootDir . DS . 'partials/_tab-nav.php'); ?>
<?php echo $data['message']; ?>

<div id="esig-settings-container">
    <div id="esig-settings-col2">
<h3><?php _e('E-Signature Admin Settings', 'esig' );?></h3>
<form name="settings_form" class="settings-form" method="post" action="<?php echo $data['post_action']; ?>">	
<table class="form-table esig-settings-form">
	<tbody>
    	<tr>
			<th><label for="first_name" id="first_name_label"><?php _e('Admin First Name<span class="description"> (required)</span>', 'esig' );?></label></th>
			<td><input type="text" name="first_name" id="first_name" value="<?php if (array_key_exists('first_name', $data)) { echo $data['first_name']; } ?>" class="regular-text" /></td>
		</tr>
		
		<tr>
			<th><label for="last_name" id="last_name_label"><?php _e('Admin Last Name<span class="description"> (required)</span>', 'esig' );?></label></th>
			<td><input type="text" name="last_name" id="last_name" value="<?php if (array_key_exists('last_name', $data)) { echo $data['last_name']; } ?>" class="regular-text" /></td>
		</tr>

		<tr>
			<th><label for="adminemail" id="user_email_label"><?php _e('Admin Email<span class="description"> (required)</span>', 'esig' );?></label></th>
			<td><input type="text" name="user_email" id="user_email" size="30" value="<?php if (array_key_exists('user_email', $data)) { echo $data['user_email']; } ?>" class="regular-text" />
			<br />
        	<span class="description"><?php _e('Enter the E-Signature admin email address that you would like all document communications sent.', 'esig' );?></span></td>
		</tr>

		<tr>
			<th><label for="user_title"><?php _e('Organization\'s Name<span class="description"> (required)</span>', 'esig' );?></label></th>
				<td><input type="text" name="user_title" id="user_title" size="30" value="<?php if (array_key_exists('user_title', $data)) { echo $data['user_title']; } ?>" class="regular-text" /></td>
		</tr>
		
		<tr>
    		<th><?php _e('Draw signature with a mouse (for future documents)<span class="description"> (required)</span>', 'esig' );?></th>
    		<td>
				<div class="signature-wrapper-displayonly">
					<canvas class="sign-here pad <?php echo $data['signature_classes']; ?>" width="425" height="100" ></canvas>
				</div>
				
				<span id="admin-signature" style="display:none">
					<div class="signature-wrapper">
						
						<span class="instructions"<?php _e('>Draw your signature with <strong>your mouse, tablet or smartphone</strong>', 'esig' );?></span>
						<a href="#clear" class="clearButton" style="margin-bottom:25px;"><?php _e('Clear', 'esig' );?></a>
						<canvas class="sign-here pad <?php echo $data['signature_classes']; ?>" width="425" height="100" ></canvas>
						<input type="hidden" name="output" class="output" value='<?php if (array_key_exists('output', $data)) { echo $data['output']; } ?>'>
						<button class="button saveButton" data-nonce="<?php if(array_key_exists('nonce', $data)) { echo $data['nonce']; } ?>"><?php _e('Insert Signature' );?><span class="loader"></span></button>
				
					</div>
				</span>
    		</td>
    	</tr>



		<tr>	
  			<th><label for="esig_super_admin" id="esig_super_admin">Super-Admin</label>
							
			</th>
			
			<td>
			
				<a href="#" class="tooltip">
					<img src="<?php echo $data['ESIGN_ASSETS_DIR_URI']; ?>/images/help.png" height="20px" align="left" />
					<span>
					A Super Admin is the main document sender/admin user that has executive document sending privileges.  To add additional document senders you will need our Premium "Unlimited Document Senders" Add-On.
					</span>
				</a>
				
				<?php echo $data['esig_administrator']; ?>
				<br>

				<div id="esig-confirm-dialog" style="display:none;">
					<div class="esig-confirm-dialog-content">
						Handing over Super-Admin ownership is serious business. 
Once you assign <span id="esig_selected_admin"> </span>as super admin you will no longer be able 
to send and preview documents unless you have the Additional E-Signature Roles Add-On. 
					</div>
				</div>

        	</td>	
		</tr>
        <?php 
         
            $wp_user_id = get_current_user_id();
            
            $settings = new WP_E_Setting();
            
            $admin_user_id=$settings->get_generic('esig_superadmin_user');
		
		    if($wp_user_id == $admin_user_id || $admin_user_id==null){
        
        ?>

		<tr>	
  			<th><label for="default_display_page" id="default_display_page">E-Signature Page</label>
							
			</th>
			
			<td>
			
				<a href="#" class="tooltip">
					<img src="<?php echo $data['ESIGN_ASSETS_DIR_URI']; ?>/images/help.png" height="20px" align="left" />
					<span>
					WP E-Signature requires one page of your website to host the document signing application.  If a user accesses this page directly they will see an error message. Each document is protected with a randomly generated user specific url that is emailed to each signer.
					</span>
				</a>
				
				<?php echo $data['post_select']; ?><br>
        	</td>	
		</tr>
        
        <tr>
		    <th></th>
			<td><label for="">
    		<a href="#" class="tooltip">
					<img src="<?php echo $data['ESIGN_ASSETS_DIR_URI']; ?>/images/help.png" height="20px" width="20px" align="left" />
					<span>
					Hide the "E-Signature" page from main navigation menu.
					</span>
					</a>
                    <?php $esig_default_page_hide=$data['esig_default_page_hide'] ; if($esig_default_page_hide==1){ $eisg_page = "checked"; } else { $eisg_page = ""; } ?>
					<input name="esig_hide_page" id="esig_hide_page" type="checkbox" value="1" <?php echo $eisg_page ;  ?>> Hide E-Signature default page from main navigation menu</label>
        
			</td>
    	</tr>

		<tr>
		    <th>Dashboard Settings</th>
			<td><label for="">
    		<a href="#" class="tooltip">
					<img src="<?php echo $data['ESIGN_ASSETS_DIR_URI']; ?>/images/help.png" height="20px" width="20px" align="left" />
					<span>
					Hide the "E-Signature" menu from all users that do not have direct access to WP E-Signature.
					</span>
					</a>
					<input name="hide_esign" id="esign_hide" type="checkbox" value="1" <?php echo $data['esign_hide_data']; ?>> Hide E-Signature from dashboard for users without access</label>
        
			</td>
    	</tr>
		
		<tr>
    		<th>SSL Security</th>
    		<td>
			
			
			<label for=""><a href="#" class="tooltip">
					<img src="<?php echo $data['ESIGN_ASSETS_DIR_URI']; ?>/images/help.png" height="20px" align="left" />
					<span>
					This feature forces SSL (HTTPS) on all WP E-Signature signing pages (a valid SSL Certificate is required).
					</span>

				</a> <input name="force_ssl_enabled" id="esign_ssl" type="checkbox" value="1" <?php echo $data['ssl_checked']; ?>> Force secure signing. <!--<a href="">Purchase an SSL certificate</a>  from WP E-Signature starting at $7.95 / yr.</label>-->
        <span class="description">Force SSL (HTTPS) on the signing pages (an SSL Certificate is required).</span> </td>

				</a>
			</td>

    	</tr>
		<?php }  // super admin access end here ?>
		<tr>
    		<th></th>
    		<td><label for=""><i>By clicking "Save Settings" you are agreeing to the Approve Me & WP E-Signature <a href="admin.php?page=esign-terms-general">Terms of Service</a> and <a href="admin.php?page=esign-privacy-general">Privacy Policy</a>.</i></td>
    	</tr>
	</tbody>
</table>

	<p>
		<input type="submit" name="submit"  class="button-appme button" value="Save Settings" />
	</p>
</form>

 
	</div>
    
    <?php echo $data['extra_contents']; ?>
    <!-- Report bug form include here -->
    <?php 
    
    $common =new WP_E_Common();
    
    $common->esig_report_bug_form();
      
    ?>