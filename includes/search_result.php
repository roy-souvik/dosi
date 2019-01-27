<?php error_reporting(0); ?>
<?php include("config.php"); ?>
<?php include("dbcon.php"); ?>


<?php

	$sresult=$_GET['sresult'];
	$querys=mysql_query("SELECT * FROM `latest_result` WHERE `category` ='".$sresult."' AND `is_Active` = 'Y'");
	
	while($rec_data=mysql_fetch_array($querys))
	{ ?>
    <a href="#">
    <?php
	echo $dates=$rec_data['date'].' : '.$title=$rec_data['title'].'<br>'.'<br>';
	?>
    </a>
    <?php
	}
?>
    