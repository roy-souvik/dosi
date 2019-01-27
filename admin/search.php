<?php

ob_start();

require("admin_utils.php");

if($_SESSION['admin_userid']==''){

	header('location: index.php');

}

else{

	disphtml("main();");

}
ob_end_flush();

$uid=$_REQUEST['id'];
//echo $uid;

function main()
{
 if($_POST[hold_page] > 0)  $GLOBALS[start] = $_POST[hold_page];
	$sql="SELECT count(*) FROM file_details  WHERE invoice_number='$sp'AND approve='y'";
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
	
	$sp=$_REQUEST['sss'];
	$rs=mysql_query($color_sql);
	$invoice_number=$_POST['invoice_number'];
	$query="SELECT * FROM file_details  WHERE invoice_number='$sp'AND approve='y'";
	$ress=mysql_query($query);
	//$row=mysql_fetch_array($ress);
	
	  //echo $row;
	//$row=mysql_fetch_row($ress);
	



?>
<link rel="stylesheet" href="css/default.css" type="text/css">

<BR>	

<table width="98%" align="center" border="0" class="ThinTable" cellpadding="5" cellspacing="1">

  <tr class="text_main_header"> 

    <td>Administration Overview</td>

	</tr>

	<tr>

		

    <td class="text_normal_big">(Use the links on the left to perform 

      Administrative Tasks)</td>

	</tr>

	</table><br>
    <form name="frm" method="post" action="">
    <table cellpadding="0" cellspacing="0">
       <tr>
       		<td width="945" class="text_normal_big">
            ENTER INVOICE NO:&nbsp;
            <input  type="text" name="sss"><input type="submit" value="Search">
       		</td>
      </tr>
       <tr>
       		<td></td>
       </tr>
       <tr>
       		<td>
            
          		<table width="89%" align="center" border="0" cellpadding="5" cellspacing="2"  class="ThinTable">

<tr class="TDHEAD"> 
            <!--<td colspan="7" align="left"  class="text_main_header">Invoice Details</td>-->
  </tr>
 
  
		
		
 <!-- <tr> 
    <td valign="top">Invoice Number</td>
    <td valign="top"><?=$row['invoice_number']?></td>
   
  </tr>-->
  
  <tr class="text_main_header"> 
    <td valign="top">File details</td>
    <td valign="top">Purpose</td>
   
  </tr>
  
  
 <?php  while($row=mysql_fetch_array($ress))
          {
          $client_domain="http://creatives.marina-bay-expo.com";
  ?>
  
  
  <tr> 
    <td valign="top"><a href="<?=$client_domain?>/<?=$row['category']?>/<?=$row['file_name']?>" target="_blank"><?=$row['file_name']?></td>
    <td valign="top" class="text_normal_big"><?=$row['purpose']?></td>
  </tr>
  <?php } ?> 
</table>
			
            
            



            
   </form>         
            
            </td>
		</tr>       
       
    </table>
    
    <br>
    
   
        
 <?
}//End of main()
?>       
        
        
    </table>
    
    
    

	
