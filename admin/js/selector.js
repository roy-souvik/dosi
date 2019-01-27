$(document).ready(function() {
	//Moving selected item(s) to right select box provided
	$('#moveright').click(function() { 
		//If none of the items are selected, inform the user using an alert
		if(!isSelected("#fromSelectBox")){return;} 
		//If atleast one of the item is selected, initially the selected option would be 'removed' and then it is appended to 'toSelectBox' (select box)
		$('#fromSelectBox option:selected').remove().appendTo('#toSelectBox'); 
		return false;
	});
	
	//Moving selected item(s) to left select box provided
	$('#moveleft').click(function() {
		//If no items are present in 'toSelectBox' (or) if none of the items are selected inform the user using an alert
		if(!noOptions("#toSelectBox") || !isSelected("#toSelectBox")){return;} 
		//If atleast one of the item is selected, initially the selected option would be 'removed' and then it is appended to 'fromSelectBox' (select box)
		$('#toSelectBox option:selected').remove().appendTo('#fromSelectBox');
		return false;
	});
	
	//Moving selected item(s) to upwards
	$('#moveup').click(function(){
		//If no items are present in 'toSelectBox' (or) if none of the items are selected inform the user using an alert
		if(!noOptions("#toSelectBox") || !isSelected("#toSelectBox")){return;}
		//If atleast one of the item is selected, through loop - the selected option would be moved upwards
		$('#toSelectBox option:selected').each(function(){$(this).insertBefore($(this).prev());});
		return false;
	});
	
	//Moving selected item(s) to upwards
	$('#movedown').click(function(){
		//If no items are present in 'toSelectBox' (or) if none of the items are selected inform the user using an alert
		if(!noOptions("#toSelectBox") || !isSelected("#toSelectBox")){return;}
		//If atleast one of the item is selected, through loop - the selected option would be moved downwards
		var eleValue = $('#toSelectBox option:selected:last').next();
		$("#toSelectBox option:selected").each(function(){
			$(this).insertAfter(eleValue);
			eleValue = $(eleValue).next();
		});
		return false;
	});
	
	//Moving selected item(s) to topmost
	$('#topmost').click(function(){
		//If no items are present in 'toSelectBox' (or) if none of the items are selected inform the user using an alert
		if(!noOptions("#toSelectBox") || !isSelected("#toSelectBox")){return;}
		//If the selected item(s) index is greater than first item (option) index then move that item to the first position
		if($('#toSelectBox option:selected').attr('index') > $('#toSelectBox option:first').attr('index')){
			$('#toSelectBox option:selected').insertBefore($('#toSelectBox option:first'));
		}
		return false;
	});
	
	$('#bottommost').click(function(){
		//If no items are present in 'toSelectBox' (or) if none of the items are selected inform the user using an alert	
		if(!noOptions("#toSelectBox") || !isSelected("#toSelectBox")){return;}
		//If the selected item(s) index is less than last item (option) index then move that item to the last position
		if($('#toSelectBox option:selected').attr('index') < $('#toSelectBox option:last').attr('index')){
			$('#toSelectBox option:selected').insertAfter($('#toSelectBox option:last'));
		}
		return false;
	});
});

//Below function is to validate the select box, if none of the item(s) is selected then it alerts saying 'Please select atleast one option' if user selects an item then it returns true
function isSelected(thisObj){
	if (!$(thisObj+" option:selected").length){
		alert("Please select atleast one option");
		return 0;
	}
	return 1;
}

//Below function is to validate the select box, if none of the item(s) where present in the select box provided then it alerts saying 'There are no options to select/move' if select box has more than one item it returns true
function noOptions(thisObj){
	if(!$(thisObj+" option").length){
		alert("There are no options to select/move");
		return 0;
	}
	return 1;
}

//Below function is to de-select all items if any of the item(s) are selected
function clearAll(thisObj){
	$('#'+thisObj).each(function(){$(this).find('option:selected').removeAttr("selected");});
}//function close

//Below function is to select all items
function selectAll(thisObj){
	if(!noOptions("#"+thisObj)){return;}
	$('#'+thisObj+' option').each(function(){$(this).attr("selected","selected");});
}//function close

//Below function is to arrange all items in Ascending Order [this is used from plug-in which we have attached]
function ascOrderFunction(){
	$("#toSelectBox").sortOptions();
}//function close

//Below function is to arrange all items in Descending Order [this is used from plug-in which we have attached]
function desOrderFunction(){
	$("#toSelectBox").sortOptions(false);
}//function close
