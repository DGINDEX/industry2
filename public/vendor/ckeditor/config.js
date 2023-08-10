/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = 'ja';
	// config.uiColor = '#AADC6E';

        config.toolbar = 'Full';
        config.toolbar_Full =
        [
         { name: 'document', items : [ 'Source', 'Preview' ] }, //'Save','NewPage','DocProps','Templates','print'を削除
         { name: 'clipboard', items : [ 'Cut','Copy','Paste','-','Undo','Redo' ] }, //,'PasteText','PasteFromWord'を削除
         //{ name: 'editing', items : [ 'Find','Replace','-','SelectAll' ] }, //'SpellChecker', 'Scayt'を削除
         //{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton','HiddenField' ] },
         '/',
         { name: 'colors', items : [ 'TextColor','BGColor' ] },
         { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','-','RemoveFormat' ] }, //'Subscript','Superscript'を削除
         { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','','BidiLtr','BidiRtl' ] },//Blockquote,createDiv削除
//         { name: 'links', items : [ 'Link','Unlink' ] },//Anchorを削除
//         { name: 'insert', items : [ 'Image' ] }, //Flash, Smiley, Iframe,table,HorizontalRule,SpecialChar,PageBreakを削除
         { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] }
         //{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
        ];

//        CKEDITOR.basePath = '/js/ckeditor/';
//        CKEDITOR.plugins.basePath = '/js/ckeditor/plugins/';
//        window.alert(CKEDITOR.basePath);
//        config.filebrowserImageBrowseUrl = '/laravel-filemanager?type=Images';
//        config.filebrowserImageUploadUrl = '/laravel-filemanager/upload?type=Images&_token=' + $('meta[name="csrf-token"]').attr('content');
//        config.filebrowserBrowseUrl      = '/laravel-filemanager?type=Files';
//        config.filebrowserUploadUrl      = '/laravel-filemanager/upload?type=Files&_token=' + $('meta[name="csrf-token"]').attr('content');

        //エディターの幅・高さ
//        config.width = '55%';
        config.height = '20em';
        //Enterで段落タグを挿入
        config.enterMode = CKEDITOR.ENTER_BR;
        //画像プロパティのプレビュー用文言
        CKEDITOR.config.image_previewText = 'ここにプレビューを表示します';
        //無効なタグを有効にする設定
        config.allowedContent = true;
        //CSS読込
//        var base_url = window.location.origin;
        //config.contentsCss = base_url + '/css/admin_entry.css';
        //実態参照に変換する文字
        config.entities_additional = '#9312,#9313,#9314,#9315,#9316,#9317,#9318,' +
                                     '#9319,#9320,#9321,#9322,#9323,#9324,#9325,' +
                                     '#9326,#9327,#9328,#9329,#9330,#9331';
        config.coreStyles_italic = { element: 'i', overrides: 'em' };
};