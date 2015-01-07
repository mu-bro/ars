<script type="text/javascript" src="catalog/view/javascript/jquery/bootstrap/bootstrap-transition.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/bootstrap/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/florentin/stylesheet/lngedit.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/florentin/stylesheet/bootstrap.min.css" />
<!-- Редактор текстовых переменных -->
<script type="text/javascript">
$(function(){

	$('#button-edit-language-start').click(function(){
		editLanguageStart();
	});

	var title = $('head title').html();

// 	alert( title );

	var pattern = new RegExp(/[&]lt[;]span class[=][[a-z0-9_\/]* lng_key[=][[a-z0-9_\/]* zone[=][[a-z0-9_\/]* lng_file[=][[a-z0-9_\/]*[&]gt[;](.*?)[&]lt[;][\/]span[&]gt[;]/gi);
	var title = pattern.exec(title);

	if( title != null ) {
		$('head title').text( title[1] );
	}

	var attributes_replace = ['value', 'onfocus', 'onblur', 'onclick', 'placeholder'];

	$('*').each(function(){
		for( attribute in attributes_replace ){
			attributeType = attributes_replace[ attribute ];
			if( $(this).attr( attributeType ) != undefined && $(this).attr( attributeType ) != '' ){
				var value = $(this).attr( attributeType );
				var newValue = value;
				newValue = newValue.replace(/[<]span[a-z_ =\/-]*[>]/g, '').replace(/[<][\/]span[>]/g, '');
				if( newValue != value ){

					var pattern = new RegExp(/<span class[=][[a-z0-9_\/]* lng_key[=][[a-z0-9_\/]* zone[=][[a-z0-9_\/]* lng_file[=][[a-z0-9_\/]*>(.*?)<[\/]span>/gi);

					var key  = value.match(/lng_key[=]([a-z0-9_\/]*)[ >]/g)[0].toString().replace(' ', '').replace('>', '').replace('lng_key=', '');
					var file = value.match(/lng_file[=]([a-z0-9_\/]*)[ >]/g)[0].toString().replace(' ', '').replace('>', '').replace('lng_file=', '');
					var text = pattern.exec( value );

					if( text != null && text && text[1] ) {
						text = text[1];
						$(this).attr( attributeType , newValue);
						$(this).attr('lng_key', key);
						$(this).attr('lng_file', file);
						$(this).addClass('lng_etitable');
					}
				}
			}
		}
	});

	$('body').ajaxComplete(function(){
		var attributes_replace = ['value', 'onfocus', 'onblur', 'onclick', 'placeholder'];
		$('*').each(function(){
			for( attribute in attributes_replace ){
				attributeType = attributes_replace[ attribute ];
				if( $(this).attr( attributeType ) != undefined && $(this).attr( attributeType ) != '' ){
					var value = $(this).attr( attributeType );
					var newValue = value;
					newValue = newValue.replace(/[<]span[a-z_ =\/-]*[>]/g, '').replace(/[<][\/]span[>]/g, '');
					if( newValue != value ){

						var pattern = new RegExp(/<span class[=][[a-z0-9_\/]* lng_key[=][[a-z0-9_\/]* zone[=][[a-z0-9_\/]* lng_file[=][[a-z0-9_\/]*>(.*?)<[\/]span>/gi);

						var key  = value.match(/lng_key[=]([a-z0-9_\/]*)[ >]/g)[0].toString().replace(' ', '').replace('>', '').replace('lng_key=', '');
						var file = value.match(/lng_file[=]([a-z0-9_\/]*)[ >]/g)[0].toString().replace(' ', '').replace('>', '').replace('lng_file=', '');
						var text = pattern.exec( value );

						if( text != null && text && text[1] ) {
							text = text[1];
							$(this).attr( attributeType , newValue);
							$(this).attr('lng_key', key);
							$(this).attr('lng_file', file);
							$(this).addClass('lng_etitable');
						}
					}
				}
			}
		});
	});

});
function editLanguageStop(){
	$('.lng_etitable').css({
		display:'inline-block',
		background:'',
		color:''
	});
	$('body *').css('cursor', '');
	$('.lng_etitable').unbind('click');
	$('.lng_etitable').attr('id', '');
	$('#button-edit-language-start').unbind('click');
	$('#button-edit-language-start').click(function(){
		editLanguageStart();
	});
}
function editLanguageStart(){

	$('#button-edit-language-start').unbind('click');
	$('#button-edit-language-start').click(function(){
		editLanguageStop();
	});

	$('body *').css('cursor', 'crosshair');
	$('body *').mousemove(function(){ return false; });
	$('body *').mouseover(function(){ return false; });
	$('.lng_etitable').css({
		display:'inline-block',
		background:'#FFEF52',
		color:'#333'
	});

	$('.lng_etitable').bind('click', function(){
		editLanguageStop();

		$(this).attr('id', 'edite-enable-var');

		var file = $(this).attr('lng_file');
		var zone = $(this).attr('zone');
		var key = $(this).attr('lng_key');

		$.ajax({
			url:'index.php?route=common/lngedit/getLanguageVars',
			type:'post',
			data: '&file=' + file + '&key=' + key,
			dataType:'json',
			success: function(json) {
				html  = '<table>';
				for( key in json ) {
					if( json[ key ].active == 'active' ) {
						var active = ' active="active"';
					} else {
						var active = ' ';
					}

					html += '<tr>';
					html += '    <td valign="middle"><img src="image/flags/' + json[ key ].image + '" />&nbsp;</td>';
					html += '    <td valign="middle"><input ' + active + ' id="lng_text_' + json[ key ].code + '" type="text" name="lng_text[' + json[ key ].code + ']" value="' + json[ key ].value + '" class="span6" /></td>';
					html += '</tr>';
				}
				html += '</table>';
				$('#language-table').html( html );
			}
		});

		$('#editLanguageForm').modal({
			backdrop:true,
			keyboard:true,
			show:true
		});

		$("#form-edit-language-variable #lng_file").val( file );
		$("#form-edit-language-variable #lng_key").val( key );

		return false;
	});
}
function saveLangVar(){
	var file = $('#form-edit-language-variable #lng_file').val();
	var key  = $('#form-edit-language-variable #lng_key').val();
	var text = $('#form-edit-language-variable input[active="active"]').val();
	$('.lng_etitable[lng_key="' + key + '"][lng_file="' + file + '"]').val( text ).html( text ).css('background-color', '#FFEF52').animate({'background-color':'transparent'}, 2000, function(){ $(this).css('background', 'transparent') });

	$.ajax({
		url:'index.php?route=common/lngedit',
		type:'post',
		data: $('#form-edit-language-variable').serialize(),
		success: function(data) {
			if( data == "Ok" ) {
				$('#modal-close').trigger('click');
			} else {
				alert( data );
			}
		}
	});
}

</script>

<div style="width:100%;height:25px;width:100%;background:#FFFFD0;;padding-bottom:7px;padding-top:5px;text-align:center;border-bottom:3px solid #333;position:fixed;top:0px;left:0px;z-index:10000;" id="edit-language-panel">
	<style>
		body{
			padding-top:50px;
		}
	</style>
	<div style="width:960px;margin:0px auto;">
		<div style="float:left;">
			<span id="edit-language-panel-title">Заголовок страницы: <b><?php echo $title; ?></b>&nbsp;</span>
		</div>
		<div style="float:right;">
			<button type="button" id="button-edit-language-start" class="btn btn-mini">Редактировать&nbsp;<i class="icon-edit"></i></button>&nbsp;
			<button type="button" onclick="window.location = 'index.php?route=common/home&lng_editable=N'" class="btn btn-mini">Выход&nbsp;<i class="icon-off"></i></button>
		</div>
	</div>
</div>
<div class="modal hide fade" style="z-index:100000;" id="editLanguageForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button style="border:none;padding:3px;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Редактирование текстовой переменной</h3>
	</div>
	<div class="modal-body">
		<form action="" id="form-edit-language-variable">
			<p>Введите новые значения переменных в поля ниже</p>
			<div id="language-table"></div>
			<input id="lng_file" type="hidden" name="lng_file" value="" />
			<p>Изменить переменную с именем:</p>
			<input id="lng_key"  type="text" name="lng_key" value="" class="span4" />
		</form>
	</div>
	<div class="modal-footer">
		<a href="#" id="modal-close" class="btn" data-dismiss="modal" aria-hidden="true">Отмена</a>
		<a href="#" onclick="saveLangVar(); return false;" class="btn btn-primary">Сохранить</a>
	</div>
</div>