jQuery(document).ready(function($) {
	
	function check_checked(obj) {
		 var bol = $("#plans_check input[type=checkbox]:checked").length >= 3;     
	    $("#plans_check input[type=checkbox]").not(":checked").attr("disabled",bol);
	    
	}
	
	$("#plans_check input[type=checkbox]").click(function() {
	    check_checked($(this).attr('id'));
	});
	
	check_checked();
	
	var validator = $(".wrap form").validate();

});
