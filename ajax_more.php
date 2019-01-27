<?php
include('includes/config.php'); 
include('includes/dbcon.php');
?>
<link type="text/css" rel="stylesheet" href="css/style.css" />
<?php
if(isset($_POST['lastmsg']))
{
$lastmsg=$_POST['lastmsg'];
$name=$_POST['name'];

$sql_product=mysql_query("select * from products where type='$name' AND id>'$lastmsg' AND is_active='Y' order by id ASC limit 6");
$count=mysql_num_rows($sql_product);
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
			
		if(mysql_num_rows(mysql_query("SELECT * FROM products WHERE type='$name' AND is_active='Y' order by id ASC"))>6){		
?>
<div id="more<?php echo $msg_id; ?>" class="morebox button">
	<a class="more" name="<?php echo $type_name;?>" id="<?php echo $msg_id; ?>">Load more</a>
</div>	

<?php
	}//end of IF condition
}
?>