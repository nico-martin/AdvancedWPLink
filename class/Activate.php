<?php
namespace NM\AdvancedWPLink;
class Activate {

	public function __construct(){
        
        global $awl_settings;
        add_action( 'plugins_loaded',   array($this, 'activate'));
        add_action( 'admin_init',       array($this, 'check_version'));
    }

    public function activate($network_wide){

        update_option( 'test', 'te');

    	if(is_multisite() && $network_wide) {
			global $wpdb;
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ($blog_ids as $blog_id){
                switch_to_blog($blog_id);
                $this->update_options();
                restore_current_blog();
            }
        }else{
	       $this->update_options();
		}

        add_action('wpmu_new_blog', array($this,'create_blog'));
    }

    public function update_options(){

        global $awl_settings;
        if(get_option('nm-awl_version')==''){

            $settings = array(
    			'inline_link' => 'disabled',
    			'rel' => 'enabled',
    			'title' => 'disabled',
    			'wplink_styling' => '[{"name":"Button","selector":"button"},{"name":"Button Primary","selector":"button-primary"}]'
    		);

    		if(get_option('nm-awl_options')!=''){
    			$current_settings = get_option('nm-awl_options');
    		}elseif(get_option('vtlawl_options')!=''){
    			$current_settings = get_option('vtlawl_options');
    		}else{
    			$current_settings = array();
    		}

    		$settings = wp_parse_args(get_option('vtlawl_options'),$settings);
        		
        	add_option('nm-awl_options', $settings);
        	add_option('nm-awl_version', $awl_settings['version']);
        }
    }

    public function create_blog($blog_id, $user_id, $domain, $path, $site_id, $meta){

        global $awl_settings;
    	if(is_plugin_active_for_network($awl_settings['file'])){
            switch_to_blog($blog_id);
            $this->update_options();
            restore_current_blog();
        }
    }

    public function check_version() {

        global $awl_settings;
        // Check that this plugin is compatible with the current version of WordPress
        if(version_compare( $GLOBALS['wp_version'], $awl_settings['wp_version'], '<' )) {

            if(is_plugin_active($awl_settings['plugin'])){

                deactivate_plugins($awl_settings['plugin']);
                add_action('admin_notices', array($this, 'disabled_notice'));
                
                if(isset( $_GET['activate'])) {
                    unset( $_GET['activate']);
                }
            }
        }
    }
    
    public function disabled_notice() {

        global $awl_settings;
        echo '<div class="notice notice-error is-dismissible">
            <p>' .sprintf( __('The plugin “%1$s” requires WordPress %2$s or higher!', 'awl'), $awl_settings['name'], $awl_settings['wp_version']).'</p>
        </div>';
    }
}

new Activate();