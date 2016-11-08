<?php
namespace NM\AdvancedWPLink;
class Editor {

	public function __construct(){
		add_action( 'admin_init', [$this,'admin_editor_style']);
		add_action( 'admin_head', [$this,'admin_head_js']);
		add_action( 'admin_enqueue_scripts', [$this,'scripts'], 999);

		add_filter( 'mce_external_plugins', [$this,'inlinelink_pre_45']);
	}

	public function admin_editor_style(){
		add_editor_style(awl_dir.'/assets/css/admin-editor-styles.css');
	}

	public function admin_head_js(){
		?>
		<script id="awl_vars">
			<?php $awl_options = get_option('nm-awl_options'); ?>
			var awl_rel = '<?php echo $awl_options['rel']; ?>';
			var awl_title = '<?php echo $awl_options['title']; ?>';
	    	var awl_linkstyles = <?php echo ($awl_options['wplink_styling']==''?'[]':stripslashes($awl_options['wplink_styling'])); ?>;
		</script>
		<?php
	}

	public function scripts(){

		wp_enqueue_style('awl_admin_styles_css', awl_dir.'/assets/css/admin-styles.css', false, awl_version);
		
		// Disable wplink
    	wp_deregister_script('wplink');

    	// Register a new script file to be linked
    	wp_register_script('wplink', awl_dir.'/assets/js/wplink.min.js', ['jquery', 'wpdialogs'], false, awl_version);
    	wp_localize_script('wplink', 'wpLinkL10n', [
    		'title' => __('Insert/edit link','awl'),
    		'update' => __('Update','awl'),
    		'save' => __('Add Link','awl'),
    		'noTitle' => __('(no title)','awl'),
    		'linktitle' => __('Link-Title','awl'),
    		'linktitle_desc' => __('The html title attribute (optional)','awl'),
    		'noMatchesFound' =>
    		__('No matches found.','awl')
    	]);
	}

	public function inlinelink_pre_45($plugins){
		if($this->inline_is_disabled()){
			$plugins['wplinkpre45'] = awl_dir.'/assets/js/wplinkpre45.js';
		}
		return $plugins;
	}

	public function inline_is_disabled(){
		$vtlawl_options = get_option('nm-awl_options');
		if($vtlawl_options['inline_link']=='enabled'){
			return false;
		}
		return true;
	}
}
?>