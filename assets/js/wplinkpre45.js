jQuery(function () {
	tinymce.PluginManager.add('wplinkpre45', function (editor, url) {
		if (editor) {
			editor.addCommand('WP_Link', function () {
				window.wpLink.open(editor.id);
			});
		}
	});
});