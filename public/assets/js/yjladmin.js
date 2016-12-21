
var is_show = true;

function msg(msg){

	var html = '<div class="am-alert am-alert-success yjl-alert" data-am-alert>';
		html+= '<button type="button" class="am-close">&times;</button>';
  		html+= '<p>'+msg+'</p></div>';

	$('body').append(html);
	return false;

}

function error(msg){

	var html = '<div class="am-alert am-alert-danger yjl-alert" data-am-alert id="yjl_alert" > ' ;
		html+= '<button type="button" class="am-close">&times;</button>';
  		html+= '<p>'+msg+'</p></div>';

  	
  	if( is_show == true){

  		is_show = false;
	  	$('body').append(html);
  		setTimeout( close , 3000);
  	}
	
	return false;
}

function close(){

	$('#yjl_alert').fadeOut();

	setTimeout(  remove_alert, 1000);
}

function remove_alert(){
	$('#yjl_alert').remove();
	$('#yjl_alert').parent().remove();
	is_show = true;
}