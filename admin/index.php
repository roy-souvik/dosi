<?php
ob_start();
error_reporting(0);
require("admin_utils.php");
if($_POST['mode']=="login"){
	login();
}
elseif($_POST['mode']=="logout"){
	logout();
}
else{
	disphtml("showLogin();");
}
ob_end_flush();
function showLogin(){
	if($_SESSION['admin_userid']!=""){
		header("Location: admin_main.php");
	}?><link rel="stylesheet" href="css/default.css" type="text/css">
	<form name="frm_login" action="<?=$_SERVER['PHP_SELF'];?>" method="post" onSubmit="return check(this);">
	<input type="hidden" name="mode" value="login"><BR><BR>	
  <table width="45%" border="0" align="center" cellpadding="5" cellspacing="0" class="ThinTable">
    <tr class="text_main_header"> 
      <td colspan="3">Administration Page Login</td>
	</tr>
	<tr>
		
      <td align="center" class="ErrorText" colspan="3">
        <?=$GLOBALS['err_msg'];?>
      </td>
	</tr>
	<tr>
		
      <td class="text_normal">Login</td>
		
      <td class="text_normal">:</td>
		<td><input name="admin_userid" type="text" class="textbox" value="" maxlength="15"></td>
	</tr>
	<tr>
		
      <td class="text_normal">Password</td>
		
      <td class="text_normal">:</td>
		<td><input name="admin_password" type="password" class="textbox" value="" maxlength="12"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
		<td><input type="submit" class="button" value=" Login "></td>
	</tr>
	</table>
	</form>
	<script language="JavaScript">
	<!--
	document.frm_login.admin_userid.focus();
	function check(form){
		if (document.frm_login.admin_userid.value.search(/\S/)==-1) {
		alert('Please enter your Login');
		document.frm_login.admin_userid.focus();
		return(false);
		}
	if (document.frm_login.admin_password.value.search(/\S/)==-1) {
		alert('Please enter your password.');
		document.frm_login.admin_password.focus();
		return false;
		}
		return true;
	}
	//-->
	</script>
<?
}
function login(){
	echo $login_sql="select * from admin_master where BINARY admin_userid='".$_POST[admin_userid]."' and BINARY admin_password='".$_POST[admin_password]."'";
	//echo "L: " . $login_sql; 
	//echo 'dd'.$login_rs=mysql_query($login_sql);
	$login_rs=mysql_query($login_sql);
	
	if($login_row=mysql_fetch_array($login_rs)){
		//echo 'dddddd';
		session_register("admin_userid");
		echo $_SESSION['admin_userid']=$login_row['admin_id'].'ddd'; 
		header('location: admin_main.php');
	}
	else{
		$GLOBALS['err_msg']="Invalid Login or Password.";
		disphtml("showLogin();");
	}
}
function logout(){
	$_SESSION['admin_userid']=="";
	unset($_SESSION['admin_userid']);
	$GLOBALS['err_msg']="You are Logged Out.";
	disphtml("showLogin();");
}
?>