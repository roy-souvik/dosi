<?php
ob_start();
error_reporting(0);
require("admin_utils.php");
if($_SESSION['admin_userid']==''){
	header('location: index.php');
}
elseif($_POST['mode']=="change_email"){
	change_email();
}
elseif($_POST['mode']=="cancel"){
header('location: admin_main.php');
}
else{
	disphtml("main();");
}
ob_end_flush();

function main(){
	
	$admin_mail_sql="select admin_mail from admin_master where admin_id='".$_SESSION["admin_userid"]."'"; 
	$admin_mail_res=mysql_fetch_array(mysql_query($admin_mail_sql));
	
	?><link rel="stylesheet" href="css/default.css" type="text/css">
	<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="post" onSubmit="return check(this);">
	<input type="hidden" name="mode" value="change_email"><br>	
  <table width="99%" align="center" border="0" class="ThinTable" cellpadding="5" cellspacing="1">
    <tr class="text_main_header"> 
      <td colspan="3">Change Email Id</td>
	</tr>
	<tr>
		<td colspan="3" align="center" class="ERR"><?=$GLOBALS['err_msg'];?></td>
	</tr>
	<tr>
		
      <td align="right" valign="top" class="text_normal">Old Email Id</td>
		
      <td align="center" valign="top" class="text_normal">:</td>
		<td  align="left" valign="top"><input type="text" name="old_admin_mail" value="<?=$admin_mail_res['admin_mail']?>" size="35px" class="textbox" readonly="readonly" ></td>
	</tr>
	<tr>
		
      <td align="right" valign="top" class="text_normal">New Email Id</td>
		
      <td align="center" valign="top" class="text_normal">:</td>
		<td  align="left" valign="top"><input type="text" name="new_admin_mail" size="35px" value="" class="textbox"></td>
	</tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><input type="submit" class="button" value="Change Email Id"> &nbsp;&nbsp; <input name="btn" type="button" class="button" onClick="f_cancel()" value="Cancel"></td>
	</tr>
	</table>
	</form>
	<script language="JavaScript">
	<!--
	
	function f_cancel(){
	form1.mode.value = "cancel";
	form1.submit();
	}
	
	function check(form){
		if(form.old_admin_mail.value.search(/\S/)==-1){
			alert("Please enter Old Email Id");
			form.old_admin_mail.focus();
			return false;
		}
		if(form.new_admin_mail.value.search(/\S/)==-1){
			alert("Please enter New Email Id");
			form.new_admin_mail.focus();
			return false;
		}
		if(form.new_admin_mail.value.search(/\S/)!=-1)
		{
			var regex = /^[\w-]+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7}$/;
			var temp_email = form.new_admin_mail.value ;
			if (regex.exec(temp_email)==null)
				{
					alert("Invalid email format! Please Enter E-Mail Properly");
					form.new_admin_mail.focus();
					form.new_admin_mail.select();
					return false;
				}
		}
		/*if(form.new_admin_pwd.value.length<5){
			alert("Please choose a New Password atleast 5 character long");
			form.new_admin_pwd.focus();
			return false;
		}
		if(form.conf_new_admin_pwd.value.search(/\S/)==-1){
			alert("Please enter Confirm New Password");
			form.conf_new_admin_pwd.focus();
			return false;
		}
		if(form.new_admin_pwd.value!=form.conf_new_admin_pwd.value){
			alert("Please enter similair Passwords");
			form.conf_new_admin_pwd.focus();
			return false;
		}*/
		return true;
	}
	//-->
	</script>
<?
}
function change_email()
{
	$admin_mail_sql="select admin_id from admin_master where admin_id='".$_SESSION["admin_userid"]."' and admin_mail = '".$_POST["old_admin_mail"]."'";
	$admin_mail_res=mysql_query($admin_mail_sql);
	if(mysql_num_rows($admin_mail_res)>0)
	{
		$admin_mail_row=mysql_fetch_array($admin_mail_res);
		$admin_upd_sql="UPDATE admin_master SET admin_mail = '".$_POST["new_admin_mail"]."' WHERE admin_id = '" . $_SESSION["admin_userid"]."'";
		mysql_query($admin_upd_sql);
		$GLOBALS['err_msg']="Email Id Updated.";
		mysql_free_result($admin_mail_res);
	}
	else
	{
		$GLOBALS['err_msg']="Email Id Not Updated.";
	}
	disphtml("main();");
}
?>