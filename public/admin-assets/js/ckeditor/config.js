/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
var roxyFileman = '/admin-assets/js/ckeditor/fileman/index.html';

CKEDITOR.editorConfig = function( config ) {
    config.filebrowserBrowseUrl = roxyFileman;
    config.filebrowserImageBrowseUrl = roxyFileman+'?type=image';
    config.removeDialogTabs = 'link:upload;image:upload';
    config.height = 350;
    //config.disallowedContent = 'span; *{font*}; *{style*}; *{class*}; table[border*,class*]{*}; td[style]{*}';
    //config.allowedContent = true;
    //config.extraAllowedContent = 'span(*)';
    //config.pasteFilter = 'semantic-text';
    //editor.pasteFilter.disallow( 'span; *{font*}; *{style*}; *{class*}; table[border*,class*]{*}; td[style]{*}' );
    //config.pasteFilter = 'semantic-content';
	config.disallowedContent = 'span; *{font*}; *{style*}; *{class*}; table[border*,class*]{*}; td[style]{*}';
	config.allowedContent=true;
	config.pasteFromWordRemoveFontStyles = true;
	config.pasteFromWordRemoveStyles = true;

    config.toolbar = [
        { name: 'document',    items: [ 'Source', '-',  'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
        { name: 'clipboard',   items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-','Undo', 'Redo' ] },
        { name: 'editing',     items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
        { name: 'insert',      items: [ 'CreatePlaceholder', 'Image', 'Flash', 'Table', 'HorizontalRule', 'PageBreak', 'Iframe', 'InsertPre' ] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
        { name: 'paragraph',   items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'BidiLtr', 'BidiRtl' ] },
        { name: 'links',       items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'tools',       items: [ 'UIColor', 'Maximize', 'ShowBlocks' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        '/',
        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] }
    ];

};
