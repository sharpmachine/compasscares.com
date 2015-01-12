<?php
/*
 * addonConntroller 
 * @since 1.1.4
 * @author Abu Shoaib
 * For use with static pages
 */

class WP_E_addonsController extends WP_E_appController 
{
    public function __construct()
    {
		parent::__construct();
		$this->queueScripts();
		$this->settings = new WP_E_Setting();
		$this->document =new WP_E_Document();  
		$this->user= new WP_E_User();
		$this->general = new WP_E_General();
        $this->model = new WP_E_Addon();
	}
    
    private function queueScripts(){
		//wp_enqueue_style('tabs', ESIGN_ASSETS_DIR_URI . DS . "css/jquery.tabs.css");
		wp_enqueue_script('jquery');
		wp_enqueue_script('addons-js', ESIGN_ASSETS_DIR_URI . DS . "/js/addons.js");
		
	}
    
    public function calling_class(){
		return get_class();
	}
    
    /***
    * Addons main page. 
    * Since 1.1.4
    */
    public function index()
	{
        $msg='' ;
        
        if(isset($_GET['esig_action']) && $_GET['esig_action']=='success')
        {
            
            $activated =$this->model->esig_all_plugin_activation();
               
            $msg=__('<strong>E-signature installed</strong> : Add-ons installed successfully.','esig'); 
            // trigger to check all installation complete . 
           // do_action('esig-activation-complete');
        }
        
        if(isset($_GET['esig_action']) && $_GET['esig_action']=='install')
        {
               $installed= $this->model->esig_addons_install($_GET['download_url'],$_GET['download_name']);
                
               if($installed)
               {
                   //going to activate the plugin .
                    $plugin_root_folder= trim($_GET['download_name'], ".zip");
                    $plugin_file = $this->model->esig_get_addons_file_path($plugin_root_folder);
                
                  wp_redirect('admin.php?page=esign-addons&esig_action=enable&plugin_url='.urlencode($plugin_file) .'&plugin_name='. $plugin_root_folder .'');
                  exit;
               }
               
              
                
        }
        
        if(isset($_GET['esig_action']) && $_GET['esig_action']=='installall')
        {
               
                $installed= $this->model->esig_addons_installall();
                
               if($installed)
               {
                    $msg=__('<strong>E-signature installed</strong> : All Add-ons installed successfully.','esig'); 
               }
               
              wp_redirect('admin.php?page=esign-addons&esig_action=success');
              exit;
            
        }
        
        // diabling esignature addons 
        if(isset($_GET['esig_action']) && $_GET['esig_action']=='disable')
        {
               $installed= $this->model->esig_addons_disable($_GET['plugin_url']);
                $plugin_name = isset($_GET['plugin_name'])?$_GET['plugin_name']:null;
                
               if($installed)
               {
                   $msg= sprintf(__('<strong>E-signature Deactivation</strong> : %s deactivated successfully.',$plugin_name, 'esig'),$plugin_name); 
               }
               
        }
        
        // enabling esignature addons 
        if(isset($_GET['esig_action']) && $_GET['esig_action']=='enable')
        {
               $installed= $this->model->esig_addons_enable($_GET['plugin_url']);
                 $plugin_name = isset($_GET['plugin_name'])?$_GET['plugin_name']:null;
               if($installed)
               {
                    $msg= sprintf(__('<strong>E-signature Deactivation</strong> : %s activated successfully.',$plugin_name, 'esig'),$plugin_name); 
               }
        }
        
        $this->view->setAlert(array('type'=>'alert e-sign-alert esig-updated', 'title'=>'', 'message'=>$msg));	
       
        $template_data=array(

					"addons_tab_class"=>'nav-tab-active',
					"Licenses"=>$this->general->checking_extension() ,
					
					); 
        if(!empty($msg))
        {
        $template_data["messages"] = $this->view->renderAlerts();
        }
		$template_data = apply_filters('esig-addons-tab-data', $template_data);
		$this->fetchView("addons",$template_data);
        
       
    }
}

?>