<?php

ob_start();
error_reporting(0);
require("admin_utils.php");

if($_SESSION['admin_userid']==''){

	header('location: index.php');

}

else{

	disphtml("main();");

}
ob_end_flush();

function main()
{
 if($_POST[hold_page] > 0)  $GLOBALS[start] = $_POST[hold_page];
	$sql="SELECT count(*) FROM user WHERE activation='0'";
	$res=mysql_query($sql);
	$row = mysql_fetch_row($res);
	//$oFCKeditor = new FCKeditor('page_content');
	//$oFCKeditor->BasePath = '../FCKeditor/' ;
	$count =  $row[0];
	if ($_POST[search_mode]=="ALPHA")
	{
	$color_sql="SELECT * FROM user where title like '$_POST[txt_alpha]%'"  . " ORDER BY title LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM user where title like '$_POST[txt_alpha]%'"  . " ORDER BY title "));
	$count=$row[0];
	}
	if ($_POST[search_mode]=="SEARCH")
	{
	$color_sql="SELECT * FROM user where title like '".stripslashes($_POST[txt_search])."%'" . " ORDER BY title LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM user where title like '".stripslashes($_POST[txt_search])."%'" . " ORDER BY title"));
	$count=$row[0];
	}
	if ($_POST[search_mode]=="")
	{
	$color_sql="SELECT *  FROM user ORDER BY id ASC LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM user ORDER BY id"));
	$count=$row[0];
	}
	//echo "sql=".$country_sql;
	$rs=mysql_query($color_sql);
	
	$uid=$_POST['id'];
	$query="SELECT * FROM user WHERE activation='0'";
	$ress=mysql_query($query);
	//$row=mysql_fetch_row($ress);
	




?>
<link rel="stylesheet" href="css/default.css" type="text/css">

<BR>	

<table width="98%" align="center" border="0" class="ThinTable" cellpadding="5" cellspacing="1">

  <tr class="text_main_header"> 

    <td>WELCOME  TO   ADMIN  PANEL</td>

	</tr>

	
</table>
        
 <?
}//End of main()
?>       
        
        
    </table>
    
    
    

	
