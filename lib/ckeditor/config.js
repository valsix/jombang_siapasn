/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
   config.filebrowserBrowseUrl = 'lib/ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
   config.filebrowserImageBrowseUrl = 'lib/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images';
   config.filebrowserFlashBrowseUrl = 'lib/ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
   config.filebrowserUploadUrl = 'lib/ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
   config.filebrowserImageUploadUrl = 'lib/ckeditor/kcfinder/upload.php?opener=ckeditor&type=images';
   config.filebrowserFlashUploadUrl = 'lib/ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';
   config.tabSpaces = 4;
   //config.extraPlugins = 'tab';
   config.toolbar_standard = [
		{ name: 'stuff', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
		{ name: 'morestuff', items: ['NumberedList', 'BulletedList'] },

		{ name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Superscript', 'Subscript'] },
		{ name: 'colors', items: ['TextColor'] },
		{ name: 'paragraph2', items: ['JustifyLeft', 'JustiftyCenter', 'JustifyRight', 'Outdent', 'Indent'] },
		'/',
		{ name: 'document', items: ['Source'] }
	]
};
