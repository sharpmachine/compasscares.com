<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel='stylesheet' id='esig-overall-style-css'  href='<?php echo  plugin_dir_url( __FILE__ ) ; ?>/style.css' type='text/css' media='all' />
	<link rel='stylesheet' id='dashicons-css'  href='<?php echo  site_url(); ?>/wp-includes/css/dashicons.min.css?ver=3.9.1' type='text/css' media='all' />
	<link rel="shortcut icon" href="<?php echo(ESIGN_ASSETS_DIR_URI) ?>/ico/favicon.ico">
	<title><?php _e('WP E-Signature by Approve Me - Sign Documents Using WordPress - ', 'esig'); ?></title>
	<?php 
	
	
	 $esig = WP_E_Sig();
	 $api=$esig->shortcode;
	 
	 $api->esig_head();
	 
	?>
</head>

<body <?php body_class(array('esig-template-page ')); ?> oncontextmenu="return false;">


   <div id="page_loader" style="display:none;">
   <div id="d1"></div>
	<div id="d2"></div>
	<div id="d3"></div>
	<div id="d4"></div>
	<div id="d5"></div>
   </div>

	<div class="signer-header" role="navigation">
		<div class="container">

			<div class="navbar-header">
				<a href="<?php bloginfo('url'); ?>" target="_blank" class="navbar-brand" style="color:#fff;"> 
				<?php 
		echo stripslashes($api->setting->get_generic("company_logo"));
 		?> </a>
			</div>

			<div class="nav navbar-nav navbar-right doclogo-right">
                 <?php 
                  // $company_logo = apply_filters('esign_logo_and_branding','');
                  // if(!empty($company_logo)){
                 ?>
                 <?php //echo($company_logo) ; ?>
                 
                 <?php 
                 // } else {
                 ?>
				
                <span class="hint--bottom  hint--rounded hint--bounce" data-hint="Click here to learn more about the security and protection of the document you are signing.">
                <a class="disabled" href="https://www.approveme.me/security-ueta-e-sign-protection/" target="_blank"><img src="<?php echo(ESIGN_ASSETS_DIR_URI) ?>/images/verified-approveme.svg" alt="" width="140px"></a>
                </span>
             
			    <?php    
                // }
                 ?> 
               </div>

		</div>
	</div>



	<div class="container first-page doc_page">
		
		<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				the_content();
				
			endwhile;
		?>	

	</div>
	
	<!--
	<div class="container doc_page">
	
	</div>
	-->


	
	<div id="esig-footer" class="footer-agree">
		<div class="container">
			
			<div class="navbar-header agree-container">
				<span class="agree-text"> <?php _e('I agree to be legally bound by this agreement and WP E-Signature', 'esig'); ?> <a href="#" data-toggle="modal" data-target=".esig-terms-modal-lg" id="esig-terms" class="doc-terms"><?php _e('Terms of Use.','esig'); ?></a></span>
			</div>

			<div class="nav navbar-nav navbar-right footer-btn">
			    <?php
				
				 $defalut_page_id=$api->setting->get_generic('default_display_page') ; 
				 $page_id = get_the_ID();
				 if($defalut_page_id==$page_id) 
						{
						  $doc_id=$api->document->document_id_by_csum($_GET['csum']) ; 
						  $print_option=$api->setting->get_generic('esig_print_option'.$doc_id);
						  if(empty($print_option))  
									$print_option=$api->setting->get_generic('esig_print_option');
						 
						   if($print_option==4) { ?>
						   
						   <a href="javascript:window.print()" class="agree-button" id="esig-print-button" title=""><?php _e('Print Document', 'esig'); ?></a>
						 
						 <?php }			
						}
						else 
						{
						  	
						  $stand_table = $api->document->table_prefix  . 'documents_stand_alone_docs';
			
							
							$page_id = get_the_ID();
							
							$doc_id=$wpdb->get_var("SELECT document_id FROM " . $stand_table . " WHERE page_id=$page_id");
									
							$print_option=$api->setting->get_generic('esig_print_option'.$doc_id);
						  if(empty($print_option))  
									$print_option=$api->setting->get_generic('esig_print_option');
						 
						   if($print_option==4) { ?>
						   
						   <a href="javascript:window.print()" class="agree-button" id="esig-print-button" title=""><?php _e('Print Document', 'esig'); ?></a>
						 
						 <?php }		
						}
				?>
				
				<a href="#" class="agree-button disabled" id="esig-agree-button"  title="Agree and submit your signature."><span id="esign_click_submit"><?php _e('Agree & Sign', 'esig') ?></span></a>

			</div>
		</div>
	</div>
	
   
	<?php  $api->esig_footer();  ?>
	
	</body>
</html>