<?php
ob_start();
error_reporting(0);
require("admin_utils.php");
if($_SESSION['admin_userid']==''){
	header('location: index.php');
}
elseif($_POST['mode']=="change_pwd"){
	change_pwd();
}
elseif($_POST['mode']=="cancel"){
header('location: admin_main.php');
}
else{
	disphtml("main();");
}
ob_end_flush();

function main(){?><link rel="stylesheet" href="css/default.css" type="text/css">
	<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="post" onSubmit="return check(this);">
	<input type="hidden" name="mode" value="change_pwd"><br>	
  <table width="99%" align="center" border="0" class="ThinTable" cellpadding="5" cellspacing="1">
    <tr class="text_main_header"> 
      <td colspan="3">Change Password</td>
	</tr>
	<tr>
		<td colspan="3" align="center" class="ERR"><?=$GLOBALS['err_msg'];?></td>
	</tr>
	<tr>
		
      <td align="right" valign="top" class="text_normal">Old Password</td>
		
      <td align="center" valign="top" class="text_normal">:</td>
		<td  align="left" valign="top"><input type="password" name="old_admin_pwd" value="" class="textbox"></td>
	</tr>
	<tr>
		
      <td align="right" valign="top" class="text_normal">New Password</td>
		
      <td align="center" valign="top" class="text_normal">:</td>
		<td  align="left" valign="top"><input type="password" name="new_admin_pwd" value="" class="textbox"></td>
	</tr>
	<tr>
		
      <td align="right" valign="top" class="text_normal">Confirm New Password</td>
		
      <td align="center" valign="top" class="text_normal">:</td>
		<td align="left" valign="top"><input type="password" name="conf_new_admin_pwd" value="" class="textbox"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><input type="submit" class="button" value="Change Password"> &nbsp;&nbsp; <input name="btn" type="button" class="button" onClick="f_cancel()" value="Cancel"></td>
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
		if(form.old_admin_pwd.value.search(/\S/)==-1){
			alert("Please enter Old Password");
			form.old_admin_pwd.focus();
			return false;
		}
		if(form.new_admin_pwd.value.search(/\S/)==-1){
			alert("Please enter New Password");
			form.new_admin_pwd.focus();
			return false;
		}
		if(form.new_admin_pwd.value.length<5){
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
		}
		return true;
	}
	//-->
	</script>
<?
}
function change_pwd()
{
	$admin_pwd_sql="select admin_id from admin_master where admin_id='".$_SESSION["admin_userid"]."' and admin_password = '".$_POST["old_admin_pwd"]."'";
	$admin_pwd_res=mysql_query($admin_pwd_sql);
	if(mysql_num_rows($admin_pwd_res)>0)
	{
		$admin_pwd_row=mysql_fetch_array($admin_pwd_res);
		$admin_upd_sql="UPDATE admin_master SET admin_password = '".$_POST[new_admin_pwd]."' WHERE admin_id = '" . $_SESSION["admin_userid"]."'";
		mysql_query($admin_upd_sql);
		$GLOBALS['err_msg']="Password Updated.";
		mysql_free_result($admin_pwd_res);
	}
	else
	{
		$GLOBALS['err_msg']="Password Mismatch.";
	}
	disphtml("main();");
}
?>