<?
error_reporting(0);
ob_start();
require("admin_utils.php");

if($_SESSION['admin_userid']==''){
	header('Location: index.php');
}
?>

<!--- TinyMCE -->
<script type="text/javascript" src="../includes/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/style.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->

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
	$sql="SELECT count(*) FROM page";
	$res=mysql_query($sql);
	$row = mysql_fetch_row($res);
    $count =  $row[0];
	if ($_POST[search_mode]=="ALPHA")
	{
	$color_sql="SELECT * FROM page where title like '$_POST[txt_alpha]%'"  . " ORDER BY page_title LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM page where page_title like '$_POST[txt_alpha]%'"  . " ORDER BY id "));
	$count=$row[0];
	}
	if ($_POST[search_mode]=="SEARCH")
	{
	$color_sql="SELECT * FROM page where page_title like '".stripslashes($_POST[txt_search])."%'" . " ORDER BY id LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM page where page_title like '".stripslashes($_POST[txt_search])."%'" . " ORDER BY id"));
	$count=$row[0];
	}
	if ($_POST[search_mode]=="")
	{
	$color_sql="SELECT *  FROM page ORDER BY id LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM page ORDER BY id"));
	$count=$row[0];
	}
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
			
    <!--td width="7%" align="right"><a href="javascript:document.frm_opts.submit();" title=" Refresh the page"><img border="0" src="images/icon_reload.gif"></a></td>
			
    <td align="right" width="6%"><a href="javascript:add_color();" title=" Add Page"><img src="images/plus_icon.gif" border="0"></a></td-->
		</tr>
	</table>
	
	
<table width="99%" align="center" border="0" cellpadding="5" cellspacing="2"  class="ThinTable">

  <tr class="TDHEAD"> 
            <td colspan="6" align="left"  class="text_main_header"> 
              Pages</td>
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
      
    <td width="19%" bgcolor="#CFC5BC"><strong> Title</strong></td>
    <td width="30%" bgcolor="#CFC5BC"><strong>Description</strong></td>
	  <td width="13%" bgcolor="#CFC5BC" align="center"><strong>Status</strong></td>
    <td width="15%" align="center" bgcolor="#CFC5BC"><strong>Edit</strong></td>
    <!--td width="13%" align="center" bgcolor="#CFC5BC"><strong>Delete</strong></td-->
  </tr>
  <?
		$cnt=$GLOBALS[start]+1;
		while($rec=mysql_fetch_array($rs))
		{
			
		?>
  <tr onMouseOver="this.bgColor='<?=SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''" class="text_small"> 
    <td valign="top"><?=$cnt++ ?></td>
    <td valign="top"><?=$rec['page_title']?></td>
    <td valign="top"><?=substr($rec['page_content'],0,100);?>...</td>
    <td align="center"><a href="javascript:active_deactive(<?=$rec['id'];?>,<?=$GLOBALS[start]?>)" title="Activate Deactivate Status"> 
      <?
			if($rec['is_Active']=='Y')
			{				
	 ?>
      			<img src="images/icon_active.png"  border="0" title="Click to Deactivate Page"> 
      <? 	} 
	  		else
			{ ?>
      			<img src="images/icon_inactive.png"  border="0" title="Click to Activate Page"> 
      <? 
	  		} 
	  ?>
      </a></td>
    <td align="center"><a href="javascript:Edit(<?=$rec['id'];?>,<?=$GLOBALS[start]?>);" title="Edit Page Details"><img src="images/edit_icon.gif" border="0"></a></td>
    <!--td align="center"><a href="javascript:Delete(<?=$rec['id'];?>,<?=$GLOBALS[start]?>)" title="Delete Page Details"><img name="xx" src="images/delete_icon.gif" border="0"></a></td--> 
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
		$sql = "SELECT * FROM page WHERE id=".$id;		
		$rs  = mysql_query($sql);
		$rec = mysql_fetch_array($rs);
		$title         			= $rec['page_title'];
		$description         		= $rec['page_content'];
		//$image							= $rec['page_image'];
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
/*	if(document.frmedit.page_content.value==''){
			alert("Please enter Page Description.");
			document.frmedit.page_content.focus();
			return false;
		}
		if(document.frmedit.projectImage.value==''){
			alert("Please enter Image.");
			document.frmedit.projectImage.focus();
			return false;
		}
*/

/*		if(document.frmedit.projectImage1.value=="")
	{
		if(document.frmedit.projectImage.value== "")
		{
			alert("Please upload Photo.");
			return false;
		}
	}
	
	if(document.frmedit.video1.value=="")
	{
		if(document.frmedit.video.value== "")
		{
			alert("Please upload video.");
			return false;
		}
	}

	
	if(document.frmedit.projectImage.value !='')
		{
			path = document.frmedit.projectImage.value;   
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
	
	
if(document.frmedit.video.value !='')
		{
			path = document.frmedit.video.value;   
			start = path.lastIndexOf(".")                    
			if (start == -1){                                
   					alert("please upload .mp4,.3gp,.avi,.mkv file.");  
			}
			else{
				 start++                                       
				 extension = path.substring(start, path.length).toLowerCase()  
			if ((extension != "mp4") &&  (extension != "3gp") &&  (extension != "avi") && (extension != "mkv"))
			{ 
				alert("please upload .mp4,.3gp,.avi,.mkv file.");  
					return false;	
				 }
				 else{
				 return true;
				 }
			}
		}
*/
		
		
 return true;   		
}
</script>
<form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmedit" onSubmit="return check();" >
  <input type="hidden" name="mode" value="update">
     
	<input type="hidden" name="id" value="<?=$id?>" >
<!--     <input type="hidden" name="projectImage1" value="<?=$rec['page_image']?>">
    <input type="hidden" name="video1" value="<?=$rec['video']?>">
-->  
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
              Page</td>
          </tr>
          <tr> 
            <td colspan="3" align="right" style="padding-left:10px;"><span class="text_Msg"></span></td>
          </tr>
          <tr> 
            <td class="text_small" align="left"> Title</td>
            <td class="text_small">:</td>
            <td align="left"><input type="text" name="title" size="80"  value="<?=$rec['page_title']?>"></td>
          </tr>
          <tr> 
            <td class="text_small" align="left"> Description</td>
            <td class="text_small">:</td>
            <td align="left">
            	<textarea id="page_content" name="page_content" rows="15" cols="80" style="width: 80%">
                       	<?=stripslashes($description)?>
				</textarea>
		    </td>
          </tr>
		<?php /*?><?php
		if($_POST['mode']=='edit')
		{
		?>  
          <tr>
            <td class="text_small" align="left">Existing Image</td>
            <td class="text_small" >:</td>
            <td align="left"><img name="aaa" src="../projectImage/<?=$rec['page_image']?>" width="150" alt=""></td>
          </tr>
		  <?php
		  }
		  ?>
          <tr> 
            <td width="16%" class="text_small" align="left"> Image</td>
            <td width="2%" class="text_small" >:</td>
            <td width="82%" align="left"><input type="file" name="projectImage"> 
            </td>
          </tr><?php */?>
          <tr> 
            <td height="32" >&nbsp;</td>
            <td >&nbsp;</td>
            <td class="point_txt"><input name="submit" type="submit" class="inplogin" value="<?=$_REQUEST['mode']=='add'?'Add':'Update'?>"> 
              &nbsp; 
                 <input name="button" type="button" class="inplogin" onClick="javascript:window.location='admin_page.php';"  value="Cancel"> 
            </td>
          </tr>
        </table></td>
  </tr>
</table>

</form>
<?php
}

function delete_record($id)
{
	$query=mysql_query('select page_image from page where id = '.$id);
	$image=mysql_fetch_array($query);
	unlink(realpath('../projectImage/'.$image['page_image']));

	$sql_query="DELETE FROM page WHERE id='".$id."'";
	mysql_query($sql_query) or die(mysql_error()." Error in  deletion.");

	$GLOBALS['err_msg']="Information deleted successfully";
	disphtml("main();");
}

 function update_record($row_id = '')
 {  
	 $uploadFlag=0;
	
	if($_FILES['projectImage']['name']!= "")
	{
	
				$Img_Upload_Path  = realpath( '../' )."/projectImage/";
				$Img_Name = $_FILES['projectImage']['name'];
				$New_Name = str_replace("'","_","$Img_Name");
				$New_File_Name =$New_Name;
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
					 $sql_image = mysql_query("select * from page where id=$row_id");
					$res_image = mysql_fetch_array($sql_image);
					$image = realpath( '../' )."/projectImage/".$res_image['page_image'];
					@unlink($image);
					  $sql_update_image = "UPDATE page ";
					$sql_update_image .= "SET page_image ='$New_File_Name'";
					$sql_update_image .= " where id  ='".$row_id ."'";
					mysql_query($sql_update_image) or die($sql_update_image);
					$GLOBALS['err'] = "Picture Updated Successfully";
				}
				else {
	 				$GLOBALS['err']= " Please Upload .gif or .jpeg,.jpg file ."; 
				}
	}
	
	if($row_id==''){
	  
			 $sql="INSERT INTO page
				  SET 
				  page_image					='$New_File_Name',
				  page_title					='".$_POST['title']."',
				  page_content					='".$_POST['page_content']."'";
				 
			mysql_query($sql) or die(mysql_error()."Error in insert");
			$GLOBALS['err_msg'] = "Information Inserted Successfully";
	   } 
	   
	else{
		 $sql_update="UPDATE page   
			   		 SET  
					 page_title					='".$_POST['title']."',
					 page_content			='".$_POST['page_content']."'
					 
					 WHERE id=".$row_id;
			 	
		mysql_query($sql_update) or die("ERROR IN UPDATE");		   
		$GLOBALS['err_msg'] = "Information Updated Successfully";
	}
	$GLOBALS['mode'] = "";
	disphtml("main();");
}	

function active_deactive($id)
{
	$product_id  = trim($_POST['id']);
	$sql = "UPDATE page SET is_active = if(is_active = 'N','Y','N') WHERE id = '$id'";
	mysql_query($sql);
	$GLOBALS['err_msg']="Status Changed Successfully";
	disphtml("main();");
}
?>