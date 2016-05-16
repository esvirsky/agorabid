var checktree;

function selectCategories(categories)
{
	for(var i=0; i<categories.length; i++)
	{
		$("#chkCategory" + categories[i].id).attr("checked", true);
		$("#chkCategory" + categories[i].id).parent().find("input[name='chkCategories[]']").each(function() { $("#" + this.id).attr("checked", true); });
	}
	
	checktree.update();
}

$(document).ready(function(){

	$("#frmCategoryManager").validate({
		showErrors: function(errorMap, errorList) 
		{ 
			if(this.numberOfInvalids() > 0) 
			{ 
				$("#divFormError").html("Please select at least one category"); 
				$("#divFormError").show(); 
			}
		}
	});

	checktree = $("#ulCategoryTree").checkTree({collapseAll: true, attrHalfChecked: ""});
	checktree.clear();
	$("#ulCategoryTree :checkbox:first").rules("add", "required");
	
});