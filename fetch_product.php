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
if(isset($_POST['name'])){
$name=$_POST['name'];
/* ============================ Getting Types of Sarees from the Database [Begin]===================================== */
$sql_product=mysql_query("SELECT * FROM products WHERE type='$name' AND is_active='Y' order by id ASC limit 6");
if(mysql_num_rows($sql_product)==0){
echo "<center><h2>No products in</h2></center>";
}
?>
<center><h2><?php echo $name; ?></h2></center>
<?php
 while($product=mysql_fetch_array($sql_product)){
 $msg_id=$product['id'];
 $type_name=$product['type'];
				if($product['price2']!=0){
					$price2=" - ".$product['price2']."/-";
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
						<?php echo substr($product['description'],0,30);?>
						<p style="margin-top: 18px;">Price: Rs <?php echo $product['price']."/- ".$price2; ?></p>
					</div>
				</div>
			<?php				
			}	
/* ============================ Getting Types of Sarees from the Database [End]===================================== */	
		
			if(mysql_num_rows(mysql_query("SELECT * FROM products WHERE type='$name' AND is_active='Y' order by id ASC"))>6){		
?>
			<div id="more<?php echo $msg_id; ?>" class="morebox button">
				<a class="more" name="<?php echo $type_name;?>" id="<?php echo $msg_id; ?>">Load more</a>
			</div>	
		<?php	
		} //end of IF condition for morebox
		
} //end of main IF condition
/* =================================================================================================== */
?>
