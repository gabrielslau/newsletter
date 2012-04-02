/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.uiColor = '#f0f0f0';
	config.height = '350px';
	config.resize_enabled = false;
	//config.extraPlugins = 'MediaEmbed,uicolor';
	config.extraPlugins = 'uicolor';
	
	config.toolbar = 'FullBar';
    config.toolbar_FullBar =
    [
        ['NewPage','Preview'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Scayt'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
        ['Image','MediaEmbed','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
        '/',
        ['Styles','Format'],
        ['Bold','Italic','Strike'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['Link','Unlink','Anchor'],
        ['Maximize','-','About']
    ];
	
	config.toolbar = 'Full';
	config.toolbar_Full =
	[
		['Source','-','Save','NewPage','Preview','-','Templates'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		['BidiLtr', 'BidiRtl'],
		'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Image','MediaEmbed','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize', 'ShowBlocks','-','About']
	];

	
	config.toolbar = 'Geral';
	config.toolbar_Geral =
    [
        ['PasteText','PasteFromWord'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
        ['Image','Table','SpecialChar'],
        ['Bold','Italic','Strike'],
		['FontSize'],
    	['TextColor'],
        ['JustifyLeft','JustifyCenter','JustifyRight'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['Link','Unlink','Anchor'],
        ['Maximize','-','Source']
    ];
    /*config.toolbar_Geral =
    [
        ['PasteText','PasteFromWord'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
        ['Image','MediaEmbed','Table','SpecialChar','PageBreak'],
        ['Bold','Italic','Strike'],
		['FontSize'],
    	['TextColor'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['Link','Unlink','Anchor'],
        ['Maximize','-','Source']
    ];*/
	
	config.toolbar = 'Necessario3';
    config.toolbar_Necessario3 =
    [
        ['PasteText','PasteFromWord'],
        ['Undo','Redo','-','SelectAll','RemoveFormat'],
        ['Bold','Italic','Strike'],
        ['NumberedList','BulletedList','-'],
		['JustifyLeft','JustifyCenter','JustifyRight'],
        ['Table'],
		['Link'],
        ['Maximize']
    ];
	
	config.toolbar = 'Reduzido';
    config.toolbar_Reduzido =
    [
        ['PasteText','PasteFromWord'],
        ['Undo','Redo','-','SelectAll','RemoveFormat'],
        ['Bold','Italic','Strike'],
        ['NumberedList','BulletedList','-'],
		['Link']
    ];
	
	config.toolbar = 'Basico';
    config.toolbar_Basico =
    [
        ['PasteText'],
        ['Undo','Redo','-','SelectAll','RemoveFormat'],
        ['Bold','Italic']
    ];
};