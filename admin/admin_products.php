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
	$sql="SELECT count(*) FROM products where category='latest'";
	$res=mysql_query($sql);
	$row = mysql_fetch_row($res);
    $count =  $row[0];
	if ($_POST[search_mode]=="ALPHA")
	{
	$color_sql="SELECT * FROM products where type like '$_POST[txt_alpha]%' and category='latest'"  . " ORDER BY id asc LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM products where type like '$_POST[txt_alpha]%' and category='latest'"  . " ORDER BY id asc "));
	$count=$row[0];
	}
	if ($_POST[search_mode]=="SEARCH")
	{
	$color_sql="SELECT * FROM products where type like '".stripslashes($_POST[txt_search])."%' and category='latest'" . " ORDER BY type LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM products where type like '".stripslashes($_POST[txt_search])."%'and category='latest'" . " ORDER BY id asc"));
	$count=$row[0];
	}
	if ($_POST[search_mode]=="")
	{
	$color_sql="SELECT *  FROM products where category='latest' ORDER BY id asc LIMIT $GLOBALS[start],$GLOBALS[show]";
	$row=mysql_fetch_array(mysql_query("select count(*) FROM products where category='latest' ORDER BY id asc"));
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
<?php if(isset($_POST['msg'])){
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
			
    <td align="right" width="6%"><a href="javascript:add_color();" title=" Add Latest products"><img src="images/plus_icon.gif" border="0"></a></td>
		</tr>
	</table>

<table width="99%" align="center" border="0" cellpadding="5" cellspacing="2"  class="ThinTable">

  <tr class="TDHEAD"> 
            <td colspan="10" align="left"  class="text_main_header"> 
              Latest Products</td>
          </tr>
  <?php 
	if($count == 0)
	{ 
	?>
  <tr> 
    <td align="center" colspan="11">No records found</td>
  </tr>
  <?php
	}
	else
	{	
	?>
  <tr class="text_normal"> 
    <td width="4%" bgcolor="#CFC5BC"><strong>SL</strong></td> 
    <td width="20%" bgcolor="#CFC5BC"><strong> Type</strong></td>
	<td width="15%" bgcolor="#CFC5BC"><strong>Code</strong></td>
    <td width="16%" bgcolor="#CFC5BC"><strong>Image</strong></td>
    <td width="20%" bgcolor="#CFC5BC"><strong>Description</strong></td>
	<td width="10%" bgcolor="#CFC5BC"><strong>Price</strong></td>
	<td width="10%" bgcolor="#CFC5BC"><strong>Price2</strong></td>
	<td width="6%" bgcolor="#CFC5BC" align="center"><strong>Status</strong></td>
    <td width="6%" align="center" bgcolor="#CFC5BC"><strong>Edit</strong></td>
    <td width="6%" align="center" bgcolor="#CFC5BC"><strong>Delete</strong></td>
  </tr>
  <?php
		$cnt=$GLOBALS[start]+1;
		while($rec=mysql_fetch_array($rs))
		{			
	?>
  <tr onMouseOver="this.bgColor='<?=SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''" class="text_small"> 
    <td valign="top"><?=$cnt++ ?></td>
    <td valign="top"><?=$rec['type']?></td>
	<td valign="top"><?=$rec['code']?></td>
    <td valign="top"><img src="../ProductsImage/thumbs/<?=$rec['image']?>" width="100" /></td>
    <td valign="top">
		<?php
			if(strlen($rec['description'])>80){
			$input=substr($rec['description'],0,80);
			$result=preg_replace(array('#<p>#', '#</p>#'), '', $input, 1);
			echo $result.'....';
			}
			else{
				echo $rec['description'];
			}
			
		?>
    </td>
	<td valign="top"><?=$rec['price']?></td>
	<td valign="top"><?=$rec['price2']?></td>
    <td align="center"><a href="javascript:active_deactive(<?=$rec['id'];?>,<?=$GLOBALS[start]?>)" type="Activate Deactivate Status"> 
      <?
			if($rec['is_active']=='Y')
			{				
	 ?>		  <img src="images/icon_active.png"  border="0" title="Click to Deactivate Latest Product"> 
      <? 	} 
	  		else
			{ ?>
      			<img src="images/icon_inactive.png"  border="0" title="Click to Activate Latest Product">
      <? 
	  		} 
	  ?>
      </a></td>
    <td align="center"><a href="javascript:Edit(<?=$rec['id'];?>,<?=$GLOBALS[start]?>);" title="Edit Product Details"><img src="images/edit_icon.gif" border="0"></a></td>
    <td align="center"><a href="javascript:Delete(<?=$rec['id'];?>,<?=$GLOBALS[start]?>)" title="Delete Product Details"><img name="xx" src="images/delete_icon.gif" border="0"></a></td> 
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
		$sql = "SELECT * FROM products WHERE id=".$id;		
		$rs  = mysql_query($sql);
		$rec = mysql_fetch_array($rs);
		$type         			= $rec['type'];
		$description         	= $rec['description'];
		$image					= $rec['image'];
		$code					= $rec['code'];
		$price					= $rec['price'];
		$price2					= $rec['price2'];
		
		$play_date=$rec['date'];
		$new_play_date=explode("-",$play_date);
		$day=$new_play_date[2];
		$month=$new_play_date[1];
		$year=$new_play_date[0];
	}
	else{
		$current_mode = "add";
	} 
?>
<script language="JavaScript">
function check()
{				
	if(document.frmedit.type.value==''){
			alert("Please enter type.");
			document.frmedit.type.focus();
			return false;
		}
		
	if(document.frmedit.image1.value=="")
	{
		if(document.frmedit.image.value== "")
		{
			alert("Please upload Photo.");
			return false;
		}
	}
	
	if(document.frmedit.code.value==''){
		alert("Please enter a code.");
		document.frmedit.code.focus();
		return false;
	}
	
	if(document.frmedit.price.value==''){
		alert("Please enter price.");
		document.frmedit.price.focus();
		return false;
	}


	
var textarea = tinyMCE.get('page_content').getContent(); 
    if ( (textarea=="") || (textarea==null) ) 
	{
		alert("Please add a description.");
		page_content.focus();
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
              Latest Product</td>
          </tr>
          <tr> 
            <td colspan="3" align="right" style="padding-left:10px;"><span class="text_Msg"></span></td>
          </tr>
          
          <?php if($_POST['mode']=='edit'){ ?>  
         <tr>
            <td class="text_small" align="left">Date</td>
            <td class="text_small" >:</td>
            <td align="left">
            <label style="font-size:12px;margin-left:20px;"> Day </label> <label style="font-size:12px; margin-left:50px;"> Month </label> <label style="font-size:12px; margin-left:45px;"> Year </label><br />
            <select name="day" style="width:70px !important;" >
                <?php for($i=01;$i<=31;$i++){?>
                <option value="<?=$i?>"<?=($i==$day)?'selected="selected"':''?>><?=$i?></option>
                <?php }?>
            </select>
            <select name="month" style="width:70px !important;" >
                <?php for($v=01;$v<=12;$v++){ ?>
                <option value="<?=$v?>"<?=($v==$month)?'selected="selected"':''?>><?=$v?></option>
                <?php }?>
            </select>
             <select name="year" style="width:85px !important;">
                <?php for($y=2010;$y<=2032;$y++){ ?>
                <option value="<?=$y?>"<?=($y==$year)?'selected="selected"':''?>><?=$y?></option>
                <?php }?>
             </select>
            
            </td>
          </tr>
		  <?php }?>
          <tr> 
            <td class="text_small" align="left">Type</td>
            <td class="text_small">:</td>
            <td align="left"><select name="type">
			  <?php
				$sql_type=mysql_query("SELECT id,type FROM saree_type WHERE is_active='Y'");
				while($query_type=mysql_fetch_array($sql_type)){
			  ?>
			  <option value="<?=$query_type['type']?>"<?=($query_type['type']==$type)?'selected="selected"':''?>><?=$query_type['type']?></option>
			  <?php
			  }
			  ?>
			  </select>
			</td>
          </tr>
          		<?php
		
		if($_POST['mode']=='edit')
		{
		?>  
          <tr>
            <td class="text_small" align="left">Existing Image</td>
            <td class="text_small" >:</td>
            <td align="left"><img name="aaa" src="<?='../ProductsImage/thumbs/'.$rec['image']?>" width="150" alt=""></td>
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
            <td class="text_small" align="left">Code</td>
            <td class="text_small">:</td>
            <td align="left"><input type="text" name="code" size="20"  value="<?=$rec['code']?>"></td>
          </tr>
		  
		  <tr> 
            <td class="text_small" align="left">Price</td>
            <td class="text_small">:</td>
            <td align="left"><input type="text" name="price" size="20"  value="<?=$rec['price']?>"></td>
          </tr>	
		  
<!-- -------------------------------------------------------------------------------------------------------------- -->
	  <tr><td><a href="#" class="range_button"><?=$current_mode?> range</a></td>
		 <td>:</td>
		 <td>
			<div class="range_select">
			  <input type="text" name="price2" size="20"  value="<?=$rec['price2']?>">
			</div>
		</td>
		  
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	  </tr>		
		<script>
		$(document).ready(function(){
			$(".range_select").hide();
			$(".range_button").click(function(){
				$(".range_select").slideToggle("fast");
			});
		});
		</script>
	  
<!-- -------------------------------------------------------------------------------------------------------------- -->		
          <tr> 
            <td class="text_small" align="left">Description</td>
            <td class="text_small">:</td>
            <td align="left">
            	<textarea id="page_content" name="page_content" rows="15" cols="80" style="width: 80%">
                       	<?=stripslashes($description)?>
				</textarea>
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
	$query=mysql_query('select image from products where id = '.$id);
	$image=mysql_fetch_array($query);
	unlink(realpath('../ProductsImage/normal/'.$image['image']));
	unlink(realpath('../ProductsImage/thumbs/'.$image['image']));

	$sql_query="DELETE FROM products WHERE id='".$id."'";
	mysql_query($sql_query) or die(mysql_error()." Error in  deletion.");

	$GLOBALS['err_msg']="Information deleted successfully";
	disphtml("main();");
}

 function update_record($row_id = '')
 {  
 	if($row_id=='') {
		$filename=upload_image(realpath('../ProductsImage/normal/'),realpath('../ProductsImage/thumbs/'));
		if(!empty($filename)) {
			$sql="INSERT INTO products
				  SET 
				  category = 'latest',
				  type = '".mysql_real_escape_string($_POST['type'])."',
				  code = '".mysql_real_escape_string($_POST['code'])."',
				  price = '".mysql_real_escape_string($_POST['price'])."',
				  price2 = '".mysql_real_escape_string($_POST['price2'])."',
				  description = '".mysql_real_escape_string($_POST['page_content'])."',
				  date	= CURDATE()";
				  
			mysql_query($sql) or die(mysql_error()."Error in Upload");
			$image=mysql_fetch_assoc(mysql_query("select id from products order by id desc limit 1"));
			$imagename='sp6xnf5gvc3qp_'.$image['id'].strrchr($filename,'.');
			$sql="UPDATE products
				  SET image = '".$imagename."'
				  WHERE id = ".$image['id'];
			mysql_query($sql) or die(mysql_error()."Error in Upload");
			rename(realpath('../ProductsImage/normal/').'/'.$filename,realpath('../ProductsImage/normal/').'/'.$imagename);
			rename(realpath('../ProductsImage/thumbs/').'/'.$filename,realpath('../ProductsImage/thumbs/').'/'.$imagename);
			$GLOBALS['err_msg'] = "Image uploaded Successfully";
		} else {
			$GLOBALS['err_msg'] = "Error in Upload";
		}
	} else {
		$old_image=mysql_fetch_array(mysql_query('select image from products where id = '.$row_id));
		$filename=upload_image(realpath('../ProductsImage/normal/'),realpath('../ProductsImage/thumbs/'));
		if(!empty($filename)) {
			if($filename!=$old_image['image']) {
				unlink(realpath('../ProductsImage/normal/'.$old_image['image']));
				unlink(realpath('../ProductsImage/thumbs/'.$old_image['image']));
					$sql_update_image = "UPDATE products ";
					$sql_update_image .= "SET image ='$filename'";
					$sql_update_image .= " where id  ='".$row_id ."'";
					mysql_query($sql_update_image) or die($sql_update_image);
				//rename(realpath('../ProductsImage/normal/').'/'.$filename,realpath('../ProductsImage/normal/').'/'.$old_image['image']);
				//rename(realpath('../ProductsImage/thumbs/').'/'.$filename,realpath('../ProductsImage/thumbs/').'/'.$old_image['image']);
			}
		}
		$date=$_POST['year'];
		$date.= '-'.$_POST['month'];
		$date.= '-'.$_POST['day'];
		if(!empty($_POST['type'])) {
			$sql_update="UPDATE products   
						 SET  
						 type = '".mysql_real_escape_string($_POST['type'])."',
						 code = '".mysql_real_escape_string($_POST['code'])."',
						 price = '".mysql_real_escape_string($_POST['price'])."',
						 price2 = '".mysql_real_escape_string($_POST['price2'])."',
				  		 description = '".mysql_real_escape_string($_POST['page_content'])."',
						 date ='".$date."'
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
	$sql = "UPDATE products SET is_active = if(is_active = 'N','Y','N') WHERE id = '$id'";
	mysql_query($sql);
	$GLOBALS['err_msg']="Status Changed Successfully";
	disphtml("main();");
}
?>