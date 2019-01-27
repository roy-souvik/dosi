<?
ob_start();
require("admin_utils.php");
if($_SESSION['admin_userid']==''){
	header('Location: index.php');
}
include("../FCKeditor/fckeditor.php");

	if($_POST['mode']=='add' || $_POST['mode']=='edit') 		disphtml("show_add_edit($_POST[product_id]);");
	elseif($_POST['mode']=='update')							update_record($_POST['product_id']);
	elseif($_POST['mode']=='active_deactive')						active_deactive($_POST['product_id']);
	elseif($_POST['mode']=='delete_rec')						delete_record($_POST['product_id']);
	else    													disphtml("main();");
	
ob_end_flush();
function main()
{
	if($_POST[hold_page] > 0)  $GLOBALS[start] = $_POST[hold_page];
	$sql="SELECT count(*) FROM admin_products";
	$res=mysql_query($sql);
	$row = mysql_fetch_row($res);
	$oFCKeditor = new FCKeditor('page_content');
	$oFCKeditor->BasePath = '../FCKeditor/' ;
	$count =  $row[0];
	if ($_POST[search_mode]=="ALPHA")
	{
	$color_sql="SELECT * FROM admin_products where title like '$_POST[txt_alpha]%'"  . " ORDER BY title LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM admin_products where title like '$_POST[txt_alpha]%'"  . " ORDER BY title "));
	$count=$row[0];
	}
	if ($_POST[search_mode]=="SEARCH")
	{
	$color_sql="SELECT * FROM admin_products where title like '".stripslashes($_POST[txt_search])."%'" . " ORDER BY title LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM admin_products where title like '".stripslashes($_POST[txt_search])."%'" . " ORDER BY title"));
	$count=$row[0];
	}
	if ($_POST[search_mode]=="")
	{
	$color_sql="SELECT *  FROM admin_products ORDER BY title LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM admin_products ORDER BY title"));
	$count=$row[0];
	}
	//echo "sql=".$country_sql;
	$rs=mysql_query($color_sql);
?>
<link rel="stylesheet" href="css/default.css" type="text/css">
	<form name = "frmSearch" method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<input type="hidden" name="search_mode" value="<?=$_POST['search_mode']?>">
	<input type="hidden" name="txt_alpha" value="<?=$_POST['txt_alpha']?>"><BR>
	
  <table width="99%" align="center" border="0" class="ThinTable" cellpadding="5" cellspacing="1">
    <tr class="TDHEAD"> 
      <td colspan="6">Search Panel</td>
    </tr>
	
	<tr class="content">
<td colspan="3"></td>
      <td class="text_normal">Search By</td>
      <td>:</td><td><select name="search_type" class="combo">
          <option value="">Select One</option>
          <option value="title">title</option>
        </select>
	&nbsp;&nbsp;&nbsp;&nbsp;
        <input name="txt_search" type="text" class="textbox" value="<?=stripslashes($_REQUEST['txt_search']);?>">
	&nbsp;&nbsp;
        <input type="button" class="button" onClick="search_text()" value="Search">
	&nbsp;&nbsp;&nbsp;
        <input name="btnShowAll" type="button" class="button" value="Show All" onClick="javascript:show_all();">
	</td></tr>
	
	<tr><td colspan="6" align="center"><? DisplayAlphabet(); ?></td></tr>

	</table><br>
	</form>
<script language="JavaScript">
function show_all()
{
	document.frmSearch.search_mode.value = "";	
	document.frmSearch.txt_search.value="";
	document.frmSearch.txt_alpha.value="";
	document.frmSearch.search_type.value="";
	document.frmSearch.submit();	
}
	
function search_text()
{
	//alert(document.frmSearch.search_type.value );
	if(document.frmSearch.search_type.value=="")
	{
		alert("Please Select A Search Type");
		return false;
	}
	if(document.frmSearch.txt_search.value.search(/\S/)==-1)
	{
		alert("Please Enter Search Criteria");
		return false;
	}
	document.frmSearch.search_mode.value = "SEARCH";
	document.frmSearch.submit();
}

function search_alpha(alpha)
{
	document.frmSearch.search_mode.value = "ALPHA";
	document.frmSearch.txt_search.value = '';
	document.frmSearch.txt_alpha.value = alpha;
	document.frmSearch.submit();
}	
</script>	

<script language="javascript">
function Add()
{
	document.frm_opts.mode.value="add";
	document.frm_opts.product_id.value="";
	document.frm_opts.submit();
}

function Edit(ID,record_no)
{
	document.frm_opts.mode.value='edit';
	document.frm_opts.product_id.value=ID;
	document.frm_opts.hold_page.value = record_no*1;
	document.frm_opts.submit();
}
function ChangeStatus(ID,record_no)
{
	document.frm_opts.mode.value='change_status';
	document.frm_opts.product_id.value=ID;
	document.frm_opts.hold_page.value = record_no*1;
	document.frm_opts.submit();
}
function Delete(ID,record_no)
{
	var UserResp = window.confirm("Are you sure to remove this?");
	if( UserResp == true )
	{
		document.frm_opts.mode.value='delete_rec';
		document.frm_opts.product_id.value=ID;
		document.frm_opts.hold_page.value = record_no*1;
		document.frm_opts.submit();
	}
}
function active_deactive(product_id,record_no)
		{
			document.frm_opts.mode.value='active_deactive';
			document.frm_opts.product_id.value=product_id;
			document.frm_opts.hold_page.value = record_no*1;
			document.frm_opts.submit();
		}
</script>
<? if(isset($_POST['msg'])){
	$msg=$_POST['msg'];
	 echo "".$GLOBALS['err_msg']."";
	
	}?>
<table width="99%" align="center" border="0" cellpadding="5" cellspacing="1">
		<input type="hidden" name="mode" value="<?=$_POST['mode']?>">
		<input type="hidden" name="id" value="" >
        <input type="hidden" name="status" >
		<tr> 
			<td width="87%" align="center" class="ErrorText"><?=$GLOBALS['err_msg']?> </td>
			
    <td width="7%" align="right"><a href="javascript:document.frm_opts.submit();" title=" Refresh the page"><img border="0" src="images/icon_reload.gif"></a></td>
			
    <td align="right" width="6%"><a href="javascript:add_color();" title=" Add Color"><img src="images/plus_icon.gif" border="0"></a></td>
		</tr>
	</table>
	
	
<table width="99%" align="center" border="0" cellpadding="5" cellspacing="2"  class="ThinTable">

  <tr class="TDHEAD"> 
            <td colspan="7" align="left"  class="text_main_header"> 
              Projects Information</td>
          </tr>
  <? 
	if($count == 0)
	{ 
	?>
  <tr> 
    <td align="center" colspan="11">No records found</td>
  </tr>
  <?
	}
	else
	{	
	?>
  <tr class="text_normal"> 
    <td width="9%" bgcolor="#CFC5BC"><strong>SL</strong></td>
      
    <td width="25%" bgcolor="#CFC5BC"><strong> Title</strong></td>
    <td width="25%" bgcolor="#CFC5BC"><strong>Description</strong></td>
      <td width="26%" bgcolor="#CFC5BC"><strong>Image</strong></td>
	  <td width="26%" bgcolor="#CFC5BC"><strong>Status</strong></td>
    <!----td width="15%" align="center" >&nbsp;</td-->
    <td width="13%" align="center" bgcolor="#CFC5BC"><strong>Edit</strong></td>
    <td width="13%" align="center" bgcolor="#CFC5BC"><strong>Delete</strong></td>
  </tr>
  <?
		$cnt=$GLOBALS[start]+1;
		while($rec=mysql_fetch_array($rs))
		{
			
		?>
  <tr onMouseOver="this.bgColor='<?=SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''" class="text_small"> 
    <td valign="top"><?=$cnt++ ?></td>
    <td valign="top"><?=$rec['title']?></td>
    <td valign="top"><?=$rec['description']?></td>
      <td valign="top" ><img name="aaa" src="../projectImage/<?=$rec['image']?>" width="100" height="100" alt=""></td>
    <td valign="top"><a href="javascript:active_deactive(<?=$rec[product_id];?>,<?=$GLOBALS[start]?>)" title="Activate Deactivate Status"> 
      <?
			if($rec['is_Active']=='Y')
			{				
	 ?>
      			<img src="images/locked_icon.gif"  border="0"> 
      <? 	} 
	  		else
			{ ?>
      			<img src="images/unlock_icon.gif"  border="0"> 
      <? 
	  		} 
	  ?>



      </a></td>
    <td align="center"><a href="javascript:Edit(<?=$rec[product_id];?>,<?=$GLOBALS[start]?>);" title="Edit Logo Details"><img src="images/edit_icon.gif" border="0"></a></td>
    <td align="center"><a href="javascript:Delete(<?=$rec[product_id];?>,<?=$GLOBALS[start]?>)" title="Delete Distributer Details"><img name="xx" src="images/delete_icon.gif" border="0"></a></td> 
  </tr>
  
  <? 
		} // end of while loop
	} // end of page count
	?>
</table>
	<? 
		if($count>0 && $count > $GLOBALS[show])	
		{
	?>
	<table width="90%" align="center" border="0" cellpadding="5" cellspacing="2">
		<tr>
			<td><? pagination($count,"frm_opts");?></td>
		</tr>
	
	</table>
	<?
		}
	?>
	<br>
	<form name="frm_opts" action="<?=$_SERVER['PHP_SELF'];?>" method="post" >
		<input type="hidden" name="mode">
		<input type="hidden" name="pageNo" value="<?=$_POST[pageNo]?>">
		<input type="hidden" name="url" value="manage_user.php">

		<input type="hidden" name="product_id" value="">
		<input type="hidden" name="search_type" value="<?=$_POST[search_type]?>">
		<input type="hidden" name="search_mode" value="<?=$_POST['search_mode']?>">
		<input type="hidden" name="txt_alpha" value="<?=$_POST['txt_alpha']?>">
		<input type="hidden" name="txt_search" value="<?=$_POST['txt_search']?>">
		<input type="hidden" name="hold_page" value="">
	</form>
	<script language="JavaScript">
	function add_color()
		{
			document.frm_opts.mode.value='add';
			document.frm_opts.submit();
		}
	</script>
<?
}//End of main()


function show_add_edit($product_id = '')
{ 
	$oFCKeditor = new FCKeditor('page_content');
	$oFCKeditor->BasePath = '../FCKeditor/' ;
	if($product_id!="")
	{	
		$current_mode = "Edit";
		$sql = "SELECT * FROM admin_products WHERE product_id=".$product_id;		
		
		$rs  = mysql_query($sql);
		$rec = mysql_fetch_array($rs);
		
		$title         			= $rec['title'];
		$description         		= $rec['description'];
		$image							= $rec['image'];
	}
	else{
		$current_mode = "add";
		
	} 
?>
<script language="JavaScript">
function check()
{				
		
	if(document.frmedit.title.value==''){
			alert("Please enter Title.");
			document.frmedit.title.focus();
			return false;
		}
	if(document.frmedit.description.value==''){
			alert("Please enter News Description.");
			document.frmedit.description.focus();
			return false;
		}
		if(document.frmedit.projectImage.value==''){
			alert("Please enter News Image.");
			document.frmedit.projectImage.focus();
			return false;
		}
 return true;   		
}
</script>
<form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmedit" onSubmit="return check();" >
  <input type="hidden" name="mode" value="update">
	<input type="hidden" name="product_id" value="<?=$product_id?>" >
  <table width="90%" align="center" border="0" cellpadding="5" cellspacing="2" >
		<tr align="center">
			<td class="ErrorText"><?=$GLOBALS['err_msg']?><?=$_GET['msg']?></td>
		</tr>
	</table>
	
  <table width="99%" border="0" cellspacing="0" cellpadding="0" align="center" class="ThinTable">
  <tr>
    <td align="center"><table width="100%" align="center"  cellpadding="4" cellspacing="4" >
          <tr class="TDHEAD"> 
            <td colspan="3" align="left" style="padding-left:10px;"  class="text_main_header"> 
              <?=$current_mode?>
              Projects Information</td>
          </tr>
          <tr> 
            <td colspan="3" align="right" style="padding-left:10px;"><span class="text_Msg"></span></td>
          </tr>
          <tr> 
            <td class="text_small" align="left"> Title</td>
            <td class="text_small">:</td>
            <td align="left"><input type="text" name="title" size="80"  value="<?=$rec['title']?>"></td>
          </tr>
          <tr> 
            <td class="text_small" align="left"> Description</td>
            <td class="text_small" >:</td>
            <td align="left">
			<? 
						    $oFCKeditor->Height='350';
					        $oFCKeditor->Width='580';
							$oFCKeditor->Value= stripslashes($rec['description']);
							$oFCKeditor->Create() ;
						
					  ?>
		    </td>
          </tr>
		<?
		if($_POST['mode']=='edit')
		{
		?>  
          <tr>
            <td class="text_small" align="left">Existing Image</td>
            <td class="text_small" >:</td>
            <td align="left"><img name="aaa" src="../projectImage/<?=$rec['image']?>" width="100" height="100" alt=""></td>
          </tr>
		  <?
		  }
		  ?>
          <tr> 
            <td width="16%" class="text_small" align="left"> Image</td>
            <td width="2%" class="text_small" >:</td>
            <td width="82%" align="left"><input type="file" name="projectImage"> 
            </td>
          </tr>
          <tr> 
            <td height="32" >&nbsp;</td>
            <td >&nbsp;</td>
            <td class="point_txt"><input name="submit" type="submit" class="inplogin" value="<?=$_REQUEST['mode']=='add'?'Add':'Update'?>"> 
              &nbsp; 
              <input name="button" type="button" class="inplogin" onClick="javascript:window.location='admin_products.php';"  value="Cancel"> 
            </td>
          </tr>
        </table></td>
  </tr>
</table>

</form>
<?
}

function delete_record($id)
{

	$sql_query="DELETE FROM admin_products WHERE product_id=".$id;
	mysql_query($sql_query) or die(mysql_error()." Error in  deletion.");
	
	$sql_query="DELETE FROM admin_products WHERE product_id='".$product_id."'";
	mysql_query($sql_query) or die(mysql_error()." Error in  deletion.");

	$GLOBALS['err_msg']="Information deleted successfully";
	disphtml("main();");
}


 //function update_record($product_id = ''){  
 function update_record($row_id = '')
 {  
  	//echo "halo";	
	$uploadFlag=0;
	
	if($_FILES['projectImage']['name']!= "")
	{
	//echo "hi";
	
				$Img_Upload_Path  = realpath( '../' )."/projectImage/";
				$Img_Name = explode(".",$_FILES['projectImage']['name']);
				$New_Name = time().$Img_Name[0];
				$Ext = $Img_Name[1];
			   	$New_File_Name = $New_Name.".".$Ext;
				$Img_Upload_Path =  $Img_Upload_Path.$New_File_Name;
				if (!move_uploaded_file($_FILES['projectImage']['tmp_name'], $Img_Upload_Path)){
					$GLOBALS['err']=  "Sorry! Photo Upload Failed."; die("Sorry!  Image Upload Failed.");
				} 
				else{
					$GLOBALS['err']= "Photo Uploaded Successfully"; 
					$uploadFlag=1;

		}		}


if($uploadFlag==1){
				if($row_id ){
					 $sql_image = mysql_query("select * from admin_products where product_id=$row_id");
					$res_image = mysql_fetch_array($sql_image);
					$image = realpath( '../' )."/projectImage/".$res_image['image'];
					@unlink($image);
					  $sql_update_image = "UPDATE admin_products ";
					$sql_update_image .= "SET image='$New_File_Name'";
					$sql_update_image .= " where product_id  ='".$row_id ."'";
					mysql_query($sql_update_image) or die($sql_update_image);
					$GLOBALS['err'] = "Picture Updated Successfully";
				}
				else {
	 				$GLOBALS['err']= " Please Upload .gif or .jpeg,.jpg file ."; 
				}
	}
	if($row_id==''){
	  
			 $sql="INSERT INTO admin_products
				  SET 
				  image					='$New_File_Name',				 
				  title					='".$_POST['title']."',
				  description			='".$_POST['page_content']."'";
				  

			mysql_query($sql) or die(mysql_error()."Error in insert");
			//header('Location:news_master.php?msg=succ');
			$GLOBALS['err_msg'] = "Information Inserted Successfully";
	   } 
	   
	else{
		$sql_update="UPDATE admin_products   
			   		 SET  
					 title					='".$_POST['title']."',
					 description			='".$_POST['page_content']."'
					 WHERE product_id=".$row_id;
			 	
		mysql_query($sql_update) or die("ERROR IN UPDATE");		   
		$GLOBALS['err_msg'] = "Information Updated Successfully";
	}
	$GLOBALS['mode'] = "";
	disphtml("main();");
}	

function active_deactive($product_id)
{
	$product_id  = trim($_POST['product_id']);
	$sql = "UPDATE admin_products SET is_active = if(is_active = 'N','Y','N') WHERE product_id = '$product_id'";
	mysql_query($sql);
	$GLOBALS['err_msg']="Status Changed Successfully";
	disphtml("main();");
}
?>