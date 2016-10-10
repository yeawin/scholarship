/**
 * 
 */
$(function(){
	$("input[name*=resource]").bind("click", function(){
		var name = $(this).attr("name");
		var name_array = name.split("_");
		var resource_id = name_array[1];
		if ($(this).is(':checked')) {
			$(this).parent().parent().parent().find("input[name*=action_" + resource_id + "]").prop("checked", true);
		} else {
			$(this).parent().parent().parent().find("input[name*=action_" + resource_id + "]").prop("checked", false);
		}
	})
});