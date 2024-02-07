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

   // config.removeButtons = 'Cut,Copy,Paste,Undo,Redo,Anchor,Underline,Strike,Subscript,Superscript,Find,PasteFromWord,PasteText,Replace,Preview,NewPage,Save,Print,Templates,Form,Checkbox,Button,HiddenField,Link,Unlink,Table,Flash,Smiley,Iframe,SelectAll,TextField,Textarea,ImageButton,CopyFormatting,RemoveFormat,TextColor,Maximize,ShowBlocks,About,Scayt';
   config.removeButtons = 'Source,Save,NewPage,Preview,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Print,SpellChecker,Scayt,Undo,Redo,Find,Replace,SelectAll,RemoveFormat,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Strike,Subscript,Superscript,NumberedList,BulletedList,Outdent,Indent,Blockquote,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,CreateDiv,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Link,Unlink,Anchor,BidiLtr,BidiRtl,Styles,Format,Font,FontSize,TextColor,BGColor,Maximize,ShowBlocks,About,Iframe,CopyFormatting,Language';
   // config.removeButtons = 'Save,NewPage,Preview,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Print,SpellChecker,Scayt,Undo,Redo,Find,Replace,SelectAll,RemoveFormat,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Strike,Subscript,Superscript,NumberedList,BulletedList,Outdent,Indent,Blockquote,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,CreateDiv,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Link,Unlink,Anchor,BidiLtr,BidiRtl,Styles,Format,Font,FontSize,TextColor,BGColor,Maximize,ShowBlocks,About,Iframe,CopyFormatting,Language';
   config.removeDialogTabs = 'link:advanced,SpellChecker';

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
