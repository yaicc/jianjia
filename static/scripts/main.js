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

});