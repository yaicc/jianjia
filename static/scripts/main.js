$(function($) {

	var flag = false;

	/* post */
	if ($('#editor').length > 0) {
		var editor = new Simditor({
	  		textarea: $('#editor'),
	  		toolbar: [ 'title', 'bold', 'italic', 'underline', 'strikethrough', 'color', 'ol', 'ul', 'blockquote', 'link', 'image', 'hr', 'indent', 'outdent', 'emoji' ],
	  		upload: {
	  			url: '/ajax/upload/',
	  			fileKey: 'upload_image',
	  			connectionCount: 3,
	  			leaveConfirm: '正在上传文件，如果离开上传会自动取消'
	  		},
	  		emoji: {
		        imagePath: '/static/simditor/images/emoji/'
		    }
	  	});
	}
	if ($('#editor_comment').length > 0) {
		var editor = new Simditor({
	  		textarea: $('#editor_comment'),
	  		toolbar: [ 'title', 'bold', 'color', 'blockquote', 'link', 'image', 'emoji' ],
	  		upload: {
	  			url: '/ajax/upload/',
	  			fileKey: 'upload_image',
	  			connectionCount: 3,
	  			leaveConfirm: '正在上传文件，如果离开上传会自动取消'
	  		},
	  		emoji: {
		        imagePath: '/static/simditor/images/emoji/'
		    }
	  	});
	}
	$('#nodelist a').click(function(){
		$('#node').val($(this).data('value'));
		$('.dropdown-toggle').html($(this).text()+'<span class="caret"></span>');
	});
	$('#post_submit').click(function(){
		if (flag) return false;
		if ($.trim($('#title').val()) == '') {
			alert('标题不能为空');
			return false;
		}
		if ($.trim(editor.getValue().replace(/<[^>]+>/g,"")) == '') {
			/* empty */
			alert('内容不能为空');
			return false;
		}
		flag = true;
		$(this).css("cursor", "not-allowed").text('提交中。。。');
	});
	$('#comment_submit').click(function(){
		if (flag) return false;
		if ($.trim(editor.getValue().replace(/<[^>]+>/g,"")) == '') {
			/* empty */
			if ($('#comment_alert strong').length == 0) $('#comment_alert').append($('<strong>警告！</strong> <span>你还没有发表你的观点哟！</span>'));
			$('#comment_alert').show();
			return false;
		}
		flag = true;
		$(this).css("cursor", "not-allowed").text('提交中。。。');
	});
	$('.comment-reply').click(function(){
		if ($(this).data('login') == 0) {
			/* 登录 */
			$(":input[name='email']").focus();
			return;
		}
		var dom = $(this).parent().parent().children('.div-blank');
		if ($('.reply_comment').length > 0) $('.reply_comment').remove();
		dom.append($('<form action="#comment-'+ $(this).data("cid") +'" method="post" class="reply_comment" id="reply_comment"></form>'));
		dom.children('.reply_comment').append($('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div>'));
		dom.children('.reply_comment').append($('<input type="hidden" name="reply" value="'+ $(this).data("cid") +'" />'));
		dom.children('.reply_comment').append($('<div class="form-group"><textarea class="form-control editor_comment" wrap="virtual" name="comment"></textarea></div>'));
		dom.children('.reply_comment').append($('<div class="form-group"><button type="submit" class="btn btn-success">提交回复</button></div>'));
		var reply_editor = new Simditor({
	  		textarea: $('.editor_comment'),
	  		toolbar: [ 'title', 'bold', 'color', 'blockquote', 'link', 'image', 'emoji' ],
	  		upload: {
	  			url: '/ajax/upload/',
	  			fileKey: 'upload_image',
	  			connectionCount: 3,
	  			leaveConfirm: '正在上传文件，如果离开上传会自动取消'
	  		},
	  		emoji: {
		        imagePath: '/static/simditor/images/emoji/'
		    }
	  	});
	  	reply_editor.focus();
	  	dom.children('.reply_comment').children('.alert').hide();
	  	dom.on("submit", "#reply_comment", function() {
	  		if ($.trim(reply_editor.getValue().replace(/<[^>]+>/g,"")) == '') {
				/* empty */
				if (dom.children('.reply_comment').children('.alert').children('strong').length == 0) {
					dom.children('.reply_comment').children('.alert').append($('<strong>警告！</strong> <span>你还没有发表你的观点哟！</span>'));
					dom.children('.reply_comment').children('.alert').show();
				}
				return false;
			}
	  	});
	});
	/* end post */

	/* register */
	$('#sign_form .form-control').blur(function(){
		var box_dom = $(this).parent().parent(), dom_id = $(this).attr('id'), dom_val = $(this).val();
		if (dom_val.length == 0) {
			control_message(box_dom, "不能为空");
		} else {
			switch(dom_id) {
				case 'email':
					if (!check_email(dom_val)) {
						control_message(box_dom, "Email地址无效");
					} else {
						$.get('/ajax/sign/', { type: 'email', value: dom_val }, function(data){
							if (data == 'false') control_message(box_dom, "Email地址已经被注册了");
							else control_message(box_dom, "恭喜你，该地址可以注册", "succeed");
						});
					}
					break;
				case 'password':
					if (!check_password(dom_val)) {
						control_message(box_dom, "密码应为6-20位字母和数字的组合");
					} else {
						control_message(box_dom, "", "succeed");
					}
					break;
				case 'confirm_password':
					if (dom_val !== $('#password').val()) {
						control_message(box_dom, "两次输入的密码不一致");
					} else {
						control_message(box_dom, "", "succeed");
					}
					break;
				case 'nickname':
					check_nickname(dom_val, function(data){
						control_message(box_dom, data.message, data.status);
					});
					break;
			}
		}
	});
	$('#sign_submit').click(function(){
		$('#sign_form .form-control').each(function(){
			$(this).blur();
		});
		if ($('.form-group').hasClass('has-error')) {
			return false;
		}
	});
	/* end register*/

});