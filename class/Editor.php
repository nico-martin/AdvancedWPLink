<?php
namespace NM\AdvancedWPLink;
class Editor {

	public function __construct(){
		add_action( 'admin_init', 			array($this,'admin_editor_style'));
		add_action( 'admin_head',			array($this,'admin_head_js'));

		// it is over 999999! higher than aioseo
		add_action( 'wp_enqueue_editor', array($this,'scripts'), 1000000);

		add_filter( 'mce_external_plugins',	array($this,'inlinelink_pre_45'));
	}

	public function admin_editor_style(){

		global $awl_settings;
		add_editor_style($awl_settings['dir'].'/assets/css/admin-editor-styles.css');
	}

	public function admin_head_js(){
		?><script id="awl_vars">
			<?php $awl_options = get_option('nm-awl_options'); ?>
			var awl_rel = '<?php echo $awl_options['rel']; ?>';
			var awl_title = '<?php echo $awl_options['title']; ?>';
			var awl_linkstyles = <?php echo ($awl_options['wplink_styling']==''?'[]':stripslashes($awl_options['wplink_styling'])); ?>;
		</script><?php
	}

	public function scripts(){

		global $awl_settings;
		wp_enqueue_style('awl_admin_styles_css', $awl_settings['dir'].'/assets/css/admin-styles.css', false, $awl_settings['version']);

		// Disable wplink
		wp_deregister_script('wplink');

		// Register a new script file to be linked
		wp_register_script('wplink', $awl_settings['dir'].'/assets/js/wplink.min.js', array('jquery', 'wpdialogs'), false, $awl_settings['version']);
		wp_localize_script('wplink', 'wpLinkL10n', array(
			'title' => __('Insert/edit link','awl'),
			'update' => __('Update','awl'),
			'save' => __('Add Link','awl'),
			'noTitle' => __('(no title)','awl'),
			'linktitle' => __('Link-Title','awl'),
			'linktitle_desc' => __('The html title attribute (optional)','awl'),
			'noMatchesFound' =>__('No matches found.','awl')
		));
	}

	public function inlinelink_pre_45($plugins){

		global $awl_settings;
		if($this->inline_is_disabled()){
			$plugins['wplinkpre45'] = $awl_settings['dir'].'/assets/js/wplinkpre45.min.js';
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

new Editor();
