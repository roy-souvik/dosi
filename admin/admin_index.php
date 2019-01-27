<?
ob_start();
	//include("../FCKeditor/fckeditor.php");
	require("admin_utils.php");

	if($_SESSION[admin_userid]=="")   		header("location:index.php");

	if($_POST['cmode']=="update_content")
{
	update_content($_POST['index_id']);
}
	/*elseif($_POST['cmode']=="delete_image")
	{
	delete_image();
	}  */ 
	else
{	
	disphtml("main();");
}	
//ob_end_flush();	
function main()
{
	//if($_POST['content']!="")
	//{
		$prop_sql="SELECT * FROM admin_about WHERE index_id ='".$_GET['index_id']."'";
	//	echo $prop_sql;
		$prop_rs=mysql_query($prop_sql);
		$prop_row=mysql_fetch_array($prop_rs);
		include_once("fckeditor/fckeditor.php") ;
$oFCKeditor = new FCKeditor('FCKeditor1') ;
$oFCKeditor->BasePath = '/hightechlabs/admin/fckeditor/' ;
	//}
?>
	<script language="JavaScript">
	function RefreshContent()
	{
		frm_content.submit();
	}
	function check()
	{
		document.frm_content.cmode.value="update_content";
		document.frm_content.submit();
	}
	/*function check1()
	{
		document.frm_content.cmode.value="delete_image";
		document.frm_content.submit();
	}*/
	</script>
	<form name="frm_content" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
	<input type="hidden" name="index_id" value="<?=$_GET['index_id']?>">
	<input type="hidden" name="cmode">	
		<table width="99%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td>
				<table width="99%" align="center" border="0" cellpadding="5" cellspacing="2">
          <tr >
            <td align="left" style="padding-left:10" class="TDHEAD"><?=strtoupper($prop_row['content_title'])?></td>
          </tr>
          <tr align="center"> 
            <td class="ERR"> 
              <?
						if($_GET['msg'] == 'succ')
							{
							//echo "Content Updated Succeassfully";
							echo $prop_row['content_title']." Updated successfully";
							}//=$GLOBALS['err_msg']?>
            </td>
          </tr>
        </table>
			</td>
		  </tr>
  		  <tr id="edit_content" style="<? if($_GET['about_id']!="") echo 'display:'; else echo 'display:none';?>">
    		<td>
				<table width="99%" align="center" class="border" cellpadding="3" cellspacing="2">
					<!--tr> 
				  <?
					$sql_image= mysql_query("select * from admin_about where about_id='".$_GET['about_id']."'");
					$rec_image= mysql_fetch_array($sql_image);
					?>   
                  <td width="20%">Title</td>
                  <td width="2%">:</td>
                  <td width="80%"><input name="Title" type="text" value="<?=$rec_image['title']?>" size="50" class="textbox"></td>
                </tr>
				
		   <?
		   if($rec_image['image'] != '')
		   	{
		   ?>
		   <tr> 
                  <td width="20%">Show Image</td>
                  <td width="2%">:</td>
                  <td width="80%"><img name="aaa" src="../aboutImage/<?=$rec_image['image']?>" width="100" height="100" alt=""></td>
                </tr>
				<?
				}
				?>
		  <tr> 				  
                  <td width="20%">Upload Image</td>
                  <td width="2%">:</td>
                  <td width="80%"><input type="file" name="content_image" class="textbox"></td>
                </tr-->
				 <tr> 				    
                  <td width="20%">Decription </td>
                  <td width="2%">:</td>
                  <td width="80%"><? 
						    $oFCKeditor->Height='350';
					        $oFCKeditor->Width='580';
							$oFCKeditor->Value= stripslashes($rec_image['description']);
							$oFCKeditor->Create() ;
						
					  ?></td>
                </tr>
				 <tr> 
				 <td colspan="2">&nbsp;</td>
            <td align="left" ><input type="button" value="Update" onClick="javascript:check();" class="inplogin">               </td>
			     </tr>
        </table>
		</td>
  </tr>
</table>
</form>
<?



}
//function delete_image()
//{
//$title=$_POST['content_select'];
// echo $sql = "select * from admin_about where title  ='".$_POST['content_select']."'";
//$query = mysql_query($sql) or die("a");
//if(mysql_num_rows($query)==1)
//{
//echo $sql = "delete image from admin_about where title  ='".$_POST['content_select']."'";
//$query = mysql_query($sql) or die("d");
//}

//}
function update_content()
{	

$uploadFlag=0;
$invalid_image=true;

if($_FILES['content_image']['name']!= ""){
				$Img_Upload_Path  = "../aboutImage/";
				$Img_Name = explode(".",$_FILES['content_image']['name']);
				$New_Name = time().$Img_Name[0];
				$Ext = $Img_Name[1];
			    $New_File_Name = $New_Name.".".$Ext;
				$Img_Upload_Path =  $Img_Upload_Path.$New_File_Name;
				if (!move_uploaded_file($_FILES['content_image']['tmp_name'], $Img_Upload_Path)){
					$GLOBALS['err']=  "Sorry! Photo Upload Failed."; die("Sorry! Student Image Upload Failed.");
				} 
				else{
					$GLOBALS['err']= "Photo Uploaded Successfully."; 
					$uploadFlag=1;

		}		}


if($uploadFlag==1 ){
		
		 $update_image = "update admin_about set image= '".$New_File_Name."' where about_id='".$_POST['about_id']."'";
		mysql_query($update_image);
		}
		
		
		if($_POST['about_id']!='')
		{
	  $update_sql="UPDATE admin_about 
	              SET  title='".$_POST[Title]."',
				  description='".$_POST[FCKeditor1]."'
				  WHERE about_id='".$_POST['about_id']."'";
	$rs=mysql_query($update_sql) or die(mysql_error());
	
		$GLOBALS['err_msg']="Content updated Successfully";
		header("location:about.php?msg=succ&about_id=".$_POST['about_id']);
    }		
	else{
		$GLOBALS['err_msg']="Updation of selected content Failed";
		disphtml("main();");

}
}
?>