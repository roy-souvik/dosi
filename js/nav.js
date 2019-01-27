$('.saree_type').click(function() {

var flag=$(this).attr('value');

$('.products_right').text('Loading......');

$.post('fetch_product.php',{ name:flag },function(data) {

	  $('.products_right').hide().html(data).fadeIn('normal');
});

//alert(flag);

});

/* ------------- SLIDER Begin--------------- */

var min_value=1;
var max_value=11000;

$('#slider').slider({ 

min:min_value,
max:max_value,
//step:5,
range:true,
values:[20,400],
//orientation:'vertical',
slide:function(event,ui) {
//$('#slider_value').html('Upto Rs ' + ui.value +'/-'); 

$('#slider_value').html('Rs. ' + ui.values[0]+ '/- to Rs.' + ui.values[1]+'/-');
},

stop:function(event,ui){
//var show=(ui.values[1])-(ui.values[0]);
//alert(ui.value);
//alert(show);

var price1=ui.values[0];
var price2=ui.values[1];

$('.products_right').html('<center><h2>Loading......</h2></center>');
$.post('search.php',{ price1:price1,price2:price2 },function(data) {
  
  $('.products_right').hide().html(data).fadeIn('normal');
  });


}

});//end of slider

/* ---------------- Slider End ----------- */

/* ------------- MORE BOX Begin--------------- */
$(function() {
//More Button
$('.more').live("click",function() 
{
var ID = $(this).attr("id");
var name= $(this).attr("name");
if(ID)
{
$("#more"+ID).html('<img src="images/moreajax.gif" />');

$.ajax({
type: "POST",
url: "ajax_more.php",
data: "lastmsg="+ ID+"& name="+name, 
cache: false,
success: function(html){
$(".products_right").append(html);
$("#more"+ID).remove();
}
});
}
else
{
$(".morebox").html('The End');
}

return false;

});
});
/* ------------- MORE BOX End--------------- */