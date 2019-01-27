<?
error_reporting(0);
ob_start();
date_default_timezone_set('Asia/Kolkata');
require("admin_utils.php");
require_once("../includes/image_upload_func.php");
require_once("../includes/image_resize_func.php");

if($_SESSION['admin_userid']==''){
	header('Location: index.php');
}
	$month = date("m");
	$year = date("Y");
	$str2 = substr($year, 2);
	$a = $month.$str2;
?>



<?php

	if($_POST['mode']=='add' || $_POST['mode']=='edit') 		disphtml("show_add_edit($_POST[id]);");
	elseif($_POST['mode']=='update')							update_record($_POST['id']);
	elseif($_POST['mode']=='active_deactive')						active_deactive($_POST['id']);
	elseif($_POST['mode']=='delete_rec')						delete_record($_POST['id']);
	else    													disphtml("main();");
	
ob_end_flush();
function main()
{
	if($_POST[hold_page] > 0)  $GLOBALS[start] = $_POST[hold_page];
	$sql="SELECT count(*) FROM banner_image";
	$res=mysql_query($sql);
	$row = mysql_fetch_row($res);
    $count =  $row[0];

	
	$color_sql="SELECT *  FROM banner_image ORDER BY id asc LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM banner_image ORDER BY id asc"));
	$count=$row[0];
	
	$rs=mysql_query($color_sql);
?>
<link rel="stylesheet" href="css/default.css" type="text/css">
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
	document.frm_opts.id.value="";
	document.frm_opts.submit();
}

function Edit(ID,record_no)
{
	document.frm_opts.mode.value='edit';
	document.frm_opts.id.value=ID;
	document.frm_opts.hold_page.value = record_no*1;
	document.frm_opts.submit();
}
function ChangeStatus(ID,record_no)
{
	document.frm_opts.mode.value='change_status';
	document.frm_opts.id.value=ID;
	document.frm_opts.hold_page.value = record_no*1;
	document.frm_opts.submit();
}
function Delete(ID,record_no)
{
	var UserResp = window.confirm("Are you sure to remove this?");
	if( UserResp == true )
	{
		document.frm_opts.mode.value='delete_rec';
		document.frm_opts.id.value=ID;
		document.frm_opts.hold_page.value = record_no*1;
		document.frm_opts.submit();
	}
}
function active_deactive(id,record_no)
		{
			document.frm_opts.mode.value='active_deactive';
			document.frm_opts.id.value=id;
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
			
    <td align="right" width="6%"><a href="javascript:add_color();" title=" Add Image"><img src="images/plus_icon.gif" border="0"></a></td>
		</tr>
	</table>
	
	
<table width="99%" align="center" border="0" cellpadding="5" cellspacing="2"  class="ThinTable">

  <tr class="TDHEAD"> 
            <td colspan="6" align="left"  class="text_main_header"> 
              Banner Image List</td>
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
    <td width="5%" bgcolor="#CFC5BC"><strong>SL</strong></td>
    <!--<td width="14%" bgcolor="#CFC5BC"><strong> Title</strong></td>-->
    <td width="25%" bgcolor="#CFC5BC"><strong>Description</strong></td>
    <td width="40%" bgcolor="#CFC5BC"><strong>Image</strong></td>
    <td width="10%" bgcolor="#CFC5BC" align="center"><strong>Status</strong></td>
    <td width="10%" align="center" bgcolor="#CFC5BC"><strong>Edit</strong></td>
    <td width="10%" align="center" bgcolor="#CFC5BC"><strong>Delete</strong></td>
  </tr>
  <?
		$cnt=$GLOBALS[start]+1;
		while($rec=mysql_fetch_array($rs))
		{
			
		?>
  <tr onMouseOver="this.bgColor='<?=SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''" class="text_small"> 
    <td valign="middle" align="center"><?=$cnt++ ?></td>
    <!--<td valign="top"><?=$rec['title']?></td>-->
    <td valign="middle" align="center"><?php echo $rec['description']; ?> </td>
    <td valign="middle" align="center"><img src="../BannerImage/thumbs/<?=$rec['image']?>" width="200" /></td>
    <td align="center"><a href="javascript:active_deactive(<?=$rec['id'];?>,<?=$GLOBALS[start]?>)" title="Activate Deactivate Status"> 
      <?
			if($rec['is_active']=='Y')
			{				
	 ?>
      			<img src="images/icon_active.png"  border="0" title="Click to Deactivate Image"> 
      <? 	} 
	  		else
			{ ?>
      			<img src="images/icon_inactive.png"  border="0" title="Click to Activate Image">
      <? 
	  		} 
	  ?>
      </a></td>
    <td align="center"><a href="javascript:Edit(<?=$rec['id'];?>,<?=$GLOBALS[start]?>);" title="Edit Image Details"><img src="images/edit_icon.gif" border="0"></a></td>
    <td align="center"><a href="javascript:Delete(<?=$rec['id'];?>,<?=$GLOBALS[start]?>)" title="Delete Image Details"><img name="xx" src="images/delete_icon.gif" border="0"></a></td> 
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
		<input type="hidden" name="id" value="">
		<input type="hidden" name="search_type" value="<?=$_POST['search_type']?>">
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


function show_add_edit($id = '')
{ 
	if($id!="")
	{	
		$current_mode = "Edit";
		$sql = "SELECT * FROM banner_image WHERE id=".$id;		
		$rs  = mysql_query($sql);
		$rec = mysql_fetch_array($rs);
		$descriptions         		= $rec['description'];
		$image							= $rec['image'];
	}
	else{
		$current_mode = "add";
	} 
?>
<script language="JavaScript">
function check()
{				
		
	/*if(document.frmedit.title.value==''){
			alert("Please enter Title.");
			document.frmedit.title.focus();
			return false;
		}*/
		
	if(document.frmedit.image1.value=="")
	{
		if(document.frmedit.image.value== "")
		{
			alert("Please upload Photo.");
			return false;
		}
	}
	if(document.frmedit.page_content.value==''){
			alert("Please enter Image Description.");
			document.frmedit.page_content.focus();
			return false;
		}
	

	if(document.frmedit.image.value !='')
		{
			path = document.frmedit.image.value;   
			start = path.lastIndexOf(".")                    
			if (start == -1){                                
   				alert("please upload .jpeg,.gif,.png,.jpg file.");  
			}
			else{
				 start++                                       
				 extension = path.substring(start, path.length).toLowerCase()  
			if ((extension != "jpg") &&  (extension != "jpeg") &&  (extension != "gif") && (extension != "png"))
			{ 
				alert("please upload .jpeg,.gif,.png,.jpg file.");  
					return false;	
				 }
				 else{
				 return true;
				 }
			}
		}
	
	
		
 return true;   		
}
</script>
<form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmedit" onSubmit="return check();" >
  <input type="hidden" name="mode" value="update">
      <input type="hidden" name="image1" value="<?=$rec['image']?>">
	<input type="hidden" name="id" value="<?=$id?>" >
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
              Image</td>
          </tr>
          <tr> 
            <td colspan="3" align="right" style="padding-left:10px;"><span class="text_Msg"></span></td>
          </tr>
          <!--<tr> 
            <td class="text_small" align="left"> Title</td>
            <td class="text_small">:</td>
            <td align="left"><input type="text" name="title" size="80"  value="<?=$rec['title']?>"></td>
          </tr>-->
          		<?php
		
		if($_POST['mode']=='edit')
		{
		?>  
          <tr>
            <td class="text_small" align="left">Existing Image</td>
            <td class="text_small" >:</td>
            <td align="left"><img name="aaa" src="<?='../BannerImage/thumbs/'.$rec['image']?>" width="150" alt=""></td>
          </tr>
		  <?php
		  }
		  ?>
          <tr> 
            <td width="16%" class="text_small" align="left"> <?=$_POST['mode']=='add'?'':'New '?>Image</td>
            <td width="2%" class="text_small" >:</td>
            <td width="82%" align="left"><input type="file" name="image"> 
            </td>
          </tr>

          <tr> 
            <td class="text_small" align="left"> Description</td>
            <td class="text_small">:</td>
            <td align="left">
            	<input type="text" name="page_content" value="<?=$descriptions?>" size="40" />
            	
            	
		    </td>
          </tr>
          <tr> 
            <td height="32" >&nbsp;</td>
            <td >&nbsp;</td>
            <td class="point_txt"><input name="submit" type="submit" class="inplogin" value="<?=$_REQUEST['mode']=='add'?'Add':'Update'?>"> 
              &nbsp; 
                 <input name="button" type="button" class="inplogin" onClick="javascript:window.location='admin_banner.php';"  value="Cancel"> 
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
	$query=mysql_query('select image from banner_image where id = '.$id);
	$image=mysql_fetch_array($query);
	unlink(realpath('../BannerImage/normal/'.$image['image']));
	unlink(realpath('../BannerImage/thumbs/'.$image['image']));

	$sql_query="DELETE FROM banner_image WHERE id='".$id."'";
	mysql_query($sql_query) or die(mysql_error()." Error in  deletion.");

	$GLOBALS['err_msg']="Information deleted successfully";
	disphtml("main();");
}

 function update_record($row_id = '')
 {  
 
 
 	if($row_id=='') {
		$filename=upload_image(realpath('../BannerImage/normal/'),realpath('../BannerImage/thumbs/'));
		if(!empty($filename)) {
			$sql="INSERT INTO banner_image
				  SET 
				  description = '".mysql_real_escape_string($_POST['page_content'])."'";
				  
			mysql_query($sql) or die(mysql_error()."Error in Upload");
			$image=mysql_fetch_assoc(mysql_query("select id from banner_image order by id desc limit 1"));
			$imagename='sp6xnf5gvc3qp_'.$image['id'].strrchr($filename,'.');
			$sql="UPDATE banner_image
				  SET image = '".mysql_real_escape_string($imagename)."'
				  WHERE id = ".$image['id'];
			mysql_query($sql) or die(mysql_error()."Error in Upload");
			rename(realpath('../BannerImage/normal/').'/'.$filename,realpath('../BannerImage/normal/').'/'.$imagename);
			rename(realpath('../BannerImage/thumbs/').'/'.$filename,realpath('../BannerImage/thumbs/').'/'.$imagename);
			$GLOBALS['err_msg'] = "Image uploaded Successfully";
		} else {
			$GLOBALS['err_msg'] = "Error in Upload";
		}
	} else {
		$old_image=mysql_fetch_array(mysql_query('select image from banner_image where id = '.$row_id));
		$filename=upload_image(realpath('../BannerImage/normal/'),realpath('../BannerImage/thumbs/'));
		if(!empty($filename)) {
			if($filename!=$old_image['image']) {
				unlink(realpath('../BannerImage/normal/'.$old_image['image']));
				unlink(realpath('../BannerImage/thumbs/'.$old_image['image']));
					$sql_update_image = "UPDATE banner_image ";
					$sql_update_image .= "SET image ='$filename'";
					$sql_update_image .= " where id  ='".$row_id ."'";
					mysql_query($sql_update_image) or die($sql_update_image);
				//rename(realpath('../BannerImage/normal/').'/'.$filename,realpath('../BannerImage/normal/').'/'.$old_image['image']);
				//rename(realpath('../BannerImage/thumbs/').'/'.$filename,realpath('../BannerImage/thumbs/').'/'.$old_image['image']);
			}
		}
		if(!empty($_POST['page_content'])) {
			
			$sql_update="UPDATE banner_image   
						 SET  
						 description = '".mysql_real_escape_string($_POST['page_content'])."'
						 WHERE id = ".$row_id;
			mysql_query($sql_update) or die("Error in Update");
		}
		$GLOBALS['err_msg'] = "Information Updated Successfully";
	}
	$GLOBALS['mode'] = "";
	disphtml("main();");

}	

function active_deactive($id)
{
	$product_id  = trim($_POST['id']);
	$sql = "UPDATE banner_image SET is_active = if(is_active = 'N','Y','N') WHERE id = '$id'";
	mysql_query($sql);
	$GLOBALS['err_msg']="Status Changed Successfully";
	disphtml("main();");
}
?>