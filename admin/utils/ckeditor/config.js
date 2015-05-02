/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	 config.language = 'ru';
	// config.uiColor = '#AADC6E';
	config.toolbar = 'MyToolbar';
 	config.toolbar_MyToolbar =
	[
		{ name: 'document', items : [ 'NewPage','-','Cut','Copy','Paste','-','Undo','Redo','-','Link','Unlink','-','HorizontalRule','SpecialChar','-','Bold','Italic','Underline','Strike','-','Table','NumberedList','BulletedList','-','ShowBlocks' ] },
		{ name: 'source', items : [ 'Source' ] }
	];
};