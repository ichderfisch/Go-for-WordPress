(function ($) {

	function insertShortcode() {
		var editor = tinymce.activeEditor,
			code = '[sgf',
			limit;
		if ($('#wgo-sgf-static').is(':checked')) {
			code += ' static=1'
		}
		if ($('#wgo-sgf-width').val() !== '') {
			code += ' width=' + $('#wgo-sgf-width').val();
		}
		if ($('#wgo-sgf-maxwidth').val() !== '') {
			code += ' maxwidth=' + $('#wgo-sgf-maxwidth').val();
		}
		limit = $('#wgo-sgf-limittop').val() + ',' +
			$('#wgo-sgf-limitright').val() + ',' +
			$('#wgo-sgf-limitbottom').val() + ',' +
			$('#wgo-sgf-limitleft').val();
		if (/^\d{1,2},\d{1,2},\d{1,2},\d{1,2}$/.test(limit)) {
			code += ' limit=' + limit;
		}
		if ($('#wgo-sgf-float').val() !== '') {
			code += ' float=' + $('#wgo-sgf-float').val();
		}
		code += '][/sgf]';
		editor.insertContent(code);
		// Jump directly into the shortcode
		editor.selection.setCursorLocation(editor.selection.getRng().startContainer, code.indexOf('[/sgf]'));
	}

	tinymce.PluginManager.add('wgo_sgf', function (editor, url) {
		console.dir(url);
		editor.addButton('wgo_sgf', {
			text: 'SGF',
			icon: false,
			onclick: function () {
				var popup = editor.windowManager.open({
					title: 'Insert SGF',
					width: 320,
					html:
					'<table id="wgo-sgf" cellspacing="16" style="width: 100%;">' +
					'	<tr><td>' +
					'		<label>Static</label>' +
					'	</td><td>' +
					'		<input id="wgo-sgf-static" type="checkbox">' +
					'	</td></tr>' +
					'	<tr><td>' +
					'		<label>Width</label>' +
					'	</td><td>' +
					'		<input id="wgo-sgf-width" type="text">' +
					'	</td></tr>' +
					'	<tr><td>' +
					'		<label>Max. width</label>' +
					'	</td><td>' +
					'		<input id="wgo-sgf-maxwidth" type="text">' +
					'	</td></tr>' +
					'	<tr><td>' +
					'		<label>Offset</label>' +
					'	</td><td>' +
					'		<input id="wgo-sgf-limitleft" type="number" placeholder="left" style="width: 80px;">' +
					'		<input id="wgo-sgf-limitright" type="number" placeholder="right" style="width: 80px;"><br>' +
					'		<input id="wgo-sgf-limittop" type="number" placeholder="top" style="width: 80px;">' +
					'		<input id="wgo-sgf-limitbottom" type="number" placeholder="bottom" style="width: 80px;">' +
					'	</td></tr>' +
					'	<tr><td>' +
					'		<label>Float</label>' +
					'	</td><td>' +
					'		<select id="wgo-sgf-float">' +
					'			<option value=""></option>' +
					'			<option value="left">left</option>' +
					'			<option value="right">right</option>' +
					'		</select>' +
					'	</td></tr>' +
					'</table>',
					buttons: [
						{
							text: 'Ok',
							subtype: 'primary',
							onclick: function () {
								insertShortcode();
								popup.close();
							}
						},
						{
							text: 'Cancel',
							onclick: function () {
								popup.close();
							}
						}
					]
				});
			}
		});
	});
}(jQuery));