<?php include('header.php'); ?>
<script type="text/javascript" src="js/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" /> 
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.10.2.custom.css"/>
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
<body>
<div id="container">
    <div class="header">
    	<div class="logo"><img src="images/dosi_textiles.png" /></div>
        <div class="god-pic"><img src="images/god-picture.jpg" /></div>
        <div class="menu-bar">
        <ul>
			<li><a href="index.php" title="Home">Home</a></li>
			<li><a href="about_us.php" title="About us">About us</a></li>
			<li><a class="menu_active" href="products.php" title="Products">Products</a></li>
			<li><a href="contact_us.php" title="Contact us">Contact us</a></li>
        </ul>
        </div>
    </div>
	
    <div class="body_cont">
        <div class="body_content">

			<h2>Products</h2>
            <p>Our collection include the most trusted and preferred products in terms of quality, price and the ancillary services.</p>
            <h2>Our products include</h2>
        <!-- ============================ Getting Types of Sarees from the Database [Begin]===================================== -->    
            <div class="products_list">
				<ul>
					<li><h3 style="text-align:center;">Categories</h3></li>
				<?php 
					$types=mysql_query("SELECT type FROM saree_type WHERE is_active='Y'"); 
					while($row=mysql_fetch_array($types)){
				?>
					<li>
						<a class="saree_type" value="<?php echo $row['type'];?>">
							<?php echo $row['type'];?></a>
					</li>
				<?php 
				}				
				?>
				</ul>
		<!-- ============================ Getting Types of Sarees from the Database [End]===================================== -->
		
		<!-- ============================ The Slider to search products within the selected range [Begin]===================================== --> 			
		<div id="container2" style="width:95%;">	
			<center><h4 style="color:#FFFFFF">Select Products By Price Range</h4></center>
			<div style="margin-left:10px;" id="slider"></div>
			<div style="color: #FFFFFF;margin-left: 50px;margin-top: 15px;padding-bottom: 13px;" id="slider_value"></div>
	   </div>
		<!-- ============================ The Slider to search products within the selected range [end]===================================== --> 
            </div>
<?php
$sql_type1=mysql_query("SELECT id,type FROM `products` WHERE is_active='Y' ORDER BY id ASC LIMIT 0,1");
$type1=mysql_fetch_array($sql_type1);           
?>
            <div class="products_right">
             <center><h2><?php echo $type1['type'];?></h2></center> 
			<!-- ============================ Displaying the products [Begin]===================================== -->  
			<?php	
				$sql_product=mysql_query("SELECT * FROM products WHERE type='".$type1['type']."'AND is_active='Y' ORDER BY id ASC limit 6");
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
			
			<div class="outerdiv">
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
			?>
					<script type="text/javascript" src="js/nav.js"></script>	
			<!-- ============================ Displaying the products [End]===================================== --> 		
<?php					
if(mysql_num_rows(mysql_query("SELECT * FROM products WHERE type='".$type1['type']."' order by id ASC"))>6){	
?>
			<div id="more<?php echo $msg_id; ?>" class="morebox button">
				<a class="more" name="<?php echo $type_name;?>" id="<?php echo $msg_id; ?>">Load more</a>
			</div>		
<?php
}//end of IF condition for morebox
?>			
			
	</div><!--	End of products_right div -->
			
			
            <div style="clear:both;"></div>
    
        </div>
    
 <div class="line"></div>
    </div> 
<?php include('footer.php'); ?>
