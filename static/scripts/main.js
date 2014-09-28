$(function($) {

	/* post */
	$('#nodelist a').click(function(){
		$('#node').val($(this).data('value'));
	});
	$('#post_submit').click(function(){
		$(this).attr("disabled", true).text('提交中。。。');
		$('#post_form').submit();
	});
	/* end post */

});