<?php
include('includes/config.php'); 
include('includes/dbcon.php'); 
?>
<link type="text/css" rel="stylesheet" href="css/style.css" />
<script language="javascript">
$(document).ready(function() {
$("a[rel=example_group]").fancybox({
'transitionIn' : 'elastic',
'transitionOut' : 'elastic',
'titlePosition' : 'over',
'titleFormat' : function(title, currentArray, currentIndex, currentOpts) {
return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
}
});
});
</script>
<?php
if(isset($_POST['price1']) && isset($_POST['price2'])){
//$search_term=mysql_real_escape_string(htmlentities($_POST['search_term']));
//echo  $_POST['price1']."---".$_POST['price2'];
if(!empty($_POST['price1'])){
	//echo "SELECT * FROM products WHERE `price`>=".$_POST['price1']." AND price <=".$_POST['price2']." AND is_active='Y'";
	$search=mysql_query("SELECT * FROM products WHERE `price`>=".$_POST['price1']." AND price <=".$_POST['price2']." AND is_active='Y'");
	
	if(mysql_num_rows($search)==0){
		echo "<center><h2>No products within the selected range</h2></center>";
}

	//echo "SELECT * FROM products WHERE `price`<='$search_term'";
	
	$result_count=mysql_num_rows($search);
/*	
	$suffix = ($result_count!=1) ? 's' : '';
	
	echo"Your search for <strong>".$search_term."</strong> Returned <b>".$result_count."</b> results<br/><br/>";
*/	
	while($product=mysql_fetch_array($search)){

	 				if($product['price2']!=0){
					$price2=" - ".$product['price2'];
				}
				else{
					$price2="";
				}
			?>
			<div class="outerdiv"><div id="hoverdiv"></div>
					<div class="phtGalLftdiv">
					<a rel="example_group" href="ProductsImage/normal/<?php echo $product['image']; ?>" title="<?php echo $product['description'];?>">
					  <img src="ProductsImage/thumbs/<?php echo $product['image']; ?>"/>
					</a>  
						<?php echo $product['description'];?>
						<p>Price: Rs <?php echo $product['price'].$price2; ?></p>
					</div>
				</div>
<?php				
	
	}
	
 }

}

?>