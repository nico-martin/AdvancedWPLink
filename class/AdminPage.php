<?php
namespace NM\AdvancedWPLink;
class AdminPage {

	public function __construct(){
		add_action( 'admin_menu', [$this,'admin_menu']);
		add_filter( 'plugin_action_links', [$this,'register_plugin_links'], 10, 2 );
		add_filter( 'plugin_row_meta', [$this,'register_plugin_links'], 10, 2 );
		add_action( 'admin_enqueue_scripts', [$this,'admin_scripts']);
	}

	public function admin_menu(){
		add_options_page(
	        awl_name,
	        awl_name,
	        'manage_options',
	        awl_folder.'-settings',
	        [$this,'options_page']
	    );
	}

	public function register_plugin_links($links, $file){
		if(strpos($file, awl_folder) !== false) {
	        $links[] = '<a href="options-general.php?page='.awl_folder.'-settings">'.__('Settings', 'awl').'</a>';
	    }
	    return $links;
	}

	public function admin_scripts($hook){
		if($hook!='settings_page_'.awl_folder.'-settings'){
			return;
		}
		wp_enqueue_script(awl_folder.'_script', awl_dir.'/assets/js/admin-page.min.js');
    	wp_enqueue_style(awl_folder.'_styles', awl_dir.'/assets/css/admin-styles.css', false, awl_version);
	}

	public function options_page(){
		$display_add_options = $message = $error = $result = '';

	    if(isset($_POST['awl_options_submit']) && check_admin_referer(awl_folder,'awl_nonce_name')){ 
	            
	        /* Update settings */
	        $awl_options['inline_link'] = isset($_POST['awl_inline_link'])?$_POST['awl_inline_link']:'enabled';         
	        $awl_options['rel'] = isset($_POST['awl_rel'])?$_POST['awl_rel']:'disabled';         
	        $awl_options['title'] = isset($_POST['awl_title'])?$_POST['awl_title']:'disabled';         
	        $awl_options['wplink_styling'] = isset($_POST['awl_wplink_styling'])?$_POST['awl_wplink_styling']:'[]';         

	        if(empty($error)) {
	            update_option('nm-awl_options', $awl_options);
	            $message .= __('Settings saved.', 'awl');    
	        }else{
	            $error .= " " . __('Settings are not saved.','awl');
	        }
	    }

	    $options = get_option('nm-awl_options');
	    ?>
	    <div class="wrap">
	        
	        <h2><?php echo awl_name; ?> <?php _e('Settings','awl'); ?></h2>
	        
	        <div class="updated fade" <?php if( empty( $message ) ) echo "style=\"display:none\""; ?>>
	            <p><strong><?php echo $message; ?></strong></p>
	        </div>
	        
	        <div class="error" <?php if ( empty( $error ) ) echo "style=\"display:none\""; ?>>
	            <p><strong><?php echo $error; ?></strong></p>
	        </div>
	        
	        <form id="awl_settings_form" method="post" action="">  
	            <table id="awl_settings_form_table" class="form-table">
	                <tr>
	                    <th>
	                        <?php _e('Inline Linking Tool','awl'); ?>
	                    </th><td>
	                    	<label><input type="checkbox" <?php checked('disabled',$options['inline_link']); ?> name="awl_inline_link" value="disabled"> <?php _e('Remove the inline linking tool from your WordPress Editor.','awl'); ?>
	                    </td>
	                </tr><tr>
	                    <th>
	                        <?php _e('rel="nofollow" Option','awl'); ?>
	                    </th><td>
	                    	<label><input type="checkbox" <?php checked('enabled',$options['rel']); ?> name="awl_rel" value="enabled"> <?php _e('Add a "rel=nofollow" option to the link modal inside the Editor.','awl'); ?>
	                    </td>
	                </tr><tr>
	                    <th>
	                        <?php _e('Linktitle','awl'); ?>
	                    </th><td>
	                    	<label><input type="checkbox" <?php checked('enabled',$options['title']); ?> name="awl_title" value="enabled"> <?php _e('Add a "title" input field to the link modal inside the Editor. This way you can add a title attribute to your link.','awl'); ?>
	                    </td>
	                </tr><tr>
	                    <th>
	                        <?php _e('wplink Stylings','awl'); ?>
	                        <small><?php _e('To assign two classes you can use a space between two classes/words','awl'); ?></small>
	                    </th><td>
	                    	<div class="table-wrap">
		                        <table id="awl_styling_options">
		                            <tr>
		                                <th><?php _e('Name','awl'); ?></th>
		                                <th><?php _e('Selector (class)','awl'); ?></th>
		                                <th></th>
		                            </tr>
		                            <?php $elements = json_decode(stripslashes($options['wplink_styling']),true); 
		                            echo '<tr id="awl_defaultelement" style="display:none;">';
		                            echo '<td><input onchange="awl_change_element(this);" type="text" name="name" value=""/></td>';
		                            echo '<td><input onchange="awl_change_element(this);" type="text" name="selector" value=""/></td>';
		                            echo '<td><a class="awl_remove" title="remove" onclick="awl_remove_element(this);"><span class="dashicons dashicons-no-alt"></span></a></td>';
		                            echo '</tr>';
		                            $i=1;
		                            if(!empty($elements)){
		                                foreach($elements as $key=>$vals){
		                                    echo '<tr id="element_'.$i.'" class="element">';
		                                    echo '<td><input onchange="awl_change_element(this);" type="text" name="name" value="'.$vals['name'].'"/></td>';
		                                    echo '<td><input onchange="awl_change_element(this);" type="text" name="selector" value="'.$vals['selector'].'"/></td>';
		                                    echo '<td><a class="awl_remove" title="remove" onclick="awl_remove_element(this);"><span class="dashicons dashicons-no-alt"></span></a></td>';
		                                    echo '</tr>';
		                                    $i++;
		                                }
		                            } ?>
		                        </table>
		                    </div>
	                        <a class="button" onclick="awl_add_element();"><?php _e('Add Element','awl'); ?></a>
	                        <input type="hidden" name="awl_wplink_styling" value="" />
	                    </td>
	                </tr>
	            </table>
	            <p class="submit">
	                <input type="submit" id="settings-form-submit" class="button-primary" value="<?php _e( 'Save Changes', 'awl' ) ?>" />
	                <input type="hidden" name="awl_options_submit" value="submit" />
	                <?php wp_nonce_field(awl_folder,'awl_nonce_name'); ?>
	            </p>
	        </form>
	    </div>
	    <p id="awl_author"><?php printf(__('“%1$s” by %2$s','awl'),awl_name,'<a href="https://twitter.com/nic_o_martin" target="_blank">Nico Martin</a>'); ?></p>
	    <?php
	}
}

new AdminPage();
