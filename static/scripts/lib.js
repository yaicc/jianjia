/*
 * JS library
*/

function check_email(string) {
	if (string.length > 50) {
		return false;
	}
	if (/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(string)) {
		return true;
	} else {
		return false;
	}
}

function check_password(string) {
	var len = string.length;
	if (len < 6 || len > 20) {
		return false;
	}
	if (/^\w+$/.test(string)) {
		return true;
	} else {
		return false;
	}
}

function check_nickname(string, callback) {
	var len = mblength(string);
	if (len < 4 || len > 14) {
		callback({message: '昵称应为2-7个汉字字符长度', status: 'error'});
	} else if (/[\!\@\#\$\^&\*\(\)\-\=\+`\:\;\'\"\、\：\；\‘\“\’\”\,\，\.\。\/\\\?\？\s]+/.test(string)) {
		callback({message: '昵称应为字母、数字和汉字的组合', status: 'error'});
	} else {
		$.get('/ajax/sign/', { type: 'nickname', value: string }, function(data){
			if (data == 'false') callback({message: '昵称已经被占用了', status: 'error'});
			else callback({message: '恭喜你，该昵称可以注册', status: 'succeed'});
		});
	}
}

function control_message(dom, message, status) {
	status = arguments[2] || 'error';
	if (status == 'error') {
		dom.addClass('has-error');
		if (dom.hasClass('has-success')) dom.removeClass('has-success');
	} else if(status == 'succeed') {
		dom.addClass('has-success');
		if (dom.hasClass('has-error')) dom.removeClass('has-error');
	}
	if (dom.children(".control-message").length) {
		dom.children(".control-message").html(message);
	} else {
		dom.append($('<span class="col-sm-4 control-message control-label">'+ message +'</span>'));
	}
}

function mblength(string) {
	var len = 0;
	for (var i = 0; i < string.length; i++) {
		charCode = string.charCodeAt(i);
		if (charCode >= 0 && charCode <= 128) {
			len += 1;
		} else 
			len += 2;
	}
	return len;
}