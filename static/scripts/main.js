$(function($) {

	/* post */
	$('#nodelist a').click(function(){
		$('#node').val($(this).data('value'));
		$('.dropdown-toggle').html($(this).text()+'<span class="caret"></span>');
	});
	$('#post_form').submit(function(){
		$('#post_form').submit();
		$(this).attr("disabled", true).text('提交中。。。');
	});
	/* end post */

	/* register */
	$('#login_form .form-control').blur(function(){
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
	/* end register*/

});