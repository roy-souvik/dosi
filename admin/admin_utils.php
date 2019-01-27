<?
session_start();
header('Cache-Control: private');
require("../includes/config.php");
require("../includes/dbcon.php");
require("../includes/functions.php");
$GLOBALS['show']=10;
if($_REQUEST['pageNo']=="")
{
	$GLOBALS['start'] = 0;
	$_REQUEST['pageNo'] = 1;
}
else
{
	$GLOBALS['start']=($_REQUEST['pageNo']-1) * $GLOBALS['show'];
}
function disphtml($what){?>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title><?=SITETITLE?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="css/default.css" type="text/css">
	<script type="text/javascript" src="dtree.js"></script>
	<script language="JavaScript">
	function logout()
	{
		document.frm_logout.submit();
		
	}
	function showImage(img_url,width,height){
		var ah=screen.availHeight-30;
		var aw=screen.availWidth-10;
		var xc=(aw-width)/2;
		var yc=(ah-height)/2;
		window.open("admin_showimage.php?img_url="+img_url,"img","width="+width+",height="+height+",left="+xc+",top="+yc+",location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no,dependent=yes,directories=no,titlebar=no");
	}
	</script>

	</head>
	<form name="frm_logout"	action="index.php" method="post">
	<input name="mode" type="hidden" value="logout">
	</form>
	<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="1000" height="100%">
  <tr valign="top">
		<td colspan="2" align="center" height="100" valign="top" bgcolor="#841008"><? include("admin_top.php");?></td>
	</tr>
	<? if($_SESSION['admin_userid']==''){?>
		<tr>
			<td colspan="2" align="center" valign="top"><?=eval($what);?></td>
		</tr>
	<? }else{?>
		<tr>
			
    <td width="25%" align="left" valign="top" style="border:1px solid #666;background: url('images/bg_small.jpg') repeat scroll 0 0 transparent;"> 
      <? include("admin_left.php");?>
    </td>
			
    <td width="75%" valign="top" style="border:1px solid #666;background: url('images/bg_small.jpg') repeat scroll 0 0 transparent;"> 
      <?=eval($what);?>
    </td>
		</tr>
	<? }?>
	
  <tr bgcolor="#422810"> 
    <td colspan="2" align="center" height="20"><font color="#FFFFFF" size="1" face="Verdana">Copyright 
      &copy; 
      <?=date('Y');?>
       - All Rights Reserved</font></td>
	</tr>
	</table>
	</body>
	</html>
<? }

function getImage($img_url){
	if(file_exists($img_url) && !is_dir($img_url)){
		return $img_url;
	}
	else{
		return "images/noimage.jpg";
	}
}
function deleteFile($file_url){
	if(file_exists($file_url) && !is_dir($file_url)){
		unlink($file_url);
	}
}
function dispDates($name,$selected){
	list($secName,) = explode("_",$name);
	$day=date('d',strtotime($selected));$month=date('m',strtotime($selected));$year=date('Y',strtotime($selected));
	$month_arr=array('January','February','March','April','May','June','July','August','September','October','November','December');?>
	<select name="<?=$name;?>_month" <? if($name == $secName."_start"){ echo " onChange='document.forms[1].".$secName."_end_month.selectedIndex=this.selectedIndex'";}?>>
	<? for($i=1;$i<13;$i++){
		$i=$i<10?$i='0'.$i:$i;?>
		<option value="<?=$i;?>" <?=$i==$month?"selected":"";?>><?=$month_arr[$i-1];?></option>
	<? }?>
	</select>&nbsp;<select name="<?=$name;?>_day" <? if($name == $secName."_start"){ echo " onChange='document.forms[1].".$secName."_end_day.selectedIndex=this.selectedIndex'";}?>>
	<? for($i=1;$i<32;$i++){
		$i=$i<10?$i='0'.$i:$i;?>
		<option value="<?=$i;?>" <?=$day==$i?"selected":"";?>><?=$i;?></option>
	<? }?>
	</select>&nbsp;<select name="<?=$name;?>_year" <? if($name == $secName."_start"){ echo " onChange='document.forms[1].".$secName."_end_year.selectedIndex=this.selectedIndex'";}?>>
	<? for($i=0;$i<7;$i++){?>
		<option value="<?=date('Y')+$i;?>" <?=(date('Y')+$i)==$year?"selected":"";?>><?=date('Y')+$i;?></option>
	<? }?>
	</select>
<? }
function dispBannType($selected){?>
	<select name="banner_type">
	<option value="H" <?=$selected=='H'?'selected':'';?>>Header</option>
	<option value="F" <?=$selected=='F'?'selected':'';?>>Footer</option>
	</select>
<?
}
function dispSections($selected){
	$sel_section_sql="select section_id,section_name from ".SITESECTION." WHERE section_id not in (6,10)";
	$sel_section_res=mysql_query($sel_section_sql);?>
	<select name="section_id">
	<? while($sel_section_row=mysql_fetch_row($sel_section_res)){?>
	<option value="<?=$sel_section_row[0];?>" <?=$selected==$sel_section_row[0]?'selected':'';?>><?=ucwords($sel_section_row[1]);?></option>	
	<? }?>
	</select>
<?
}
function pagination($count,$frmName)
{
	if($_REQUEST['mode']=='delete'){
	$count=$count-1;
	$noOfPages = ceil($count/$GLOBALS['show']);
	$_REQUEST['pageNo']=$noOfPages;
	}
	
	else{
	$noOfPages = ceil($count/$GLOBALS['show']);
	}
?>
<script language="JavaScript">
<!--
function prevPage(no){
	document.<?=$frmName?>.action="<?=$_SERVER['PHP_SELF']?>";
	document.<?=$frmName?>.pageNo.value = no-1;
	document.<?=$frmName?>.submit();
}
function nextPage(no){
	document.<?=$frmName?>.action="<?=$_SERVER['PHP_SELF']?>";
	document.<?=$frmName?>.pageNo.value = no+1;
	document.<?=$frmName?>.submit();
}
function disPage(no){
	document.<?=$frmName?>.action="<?=$_SERVER['PHP_SELF']?>";
	document.<?=$frmName?>.pageNo.value = no;
	document.<?=$frmName?>.submit();
}

//-->
</script>
	<table width="100%" align="center" border="0" cellspacing="0" cellpadding="4">
	  <tr>
		<td width="15%" align="left"><? if($_REQUEST[pageNo]!=1){ ?>
				<a href="javascript:prevPage(<?=$_REQUEST[pageNo] ?>);" onMouseOut="javascript:window.status='Done';" onMouseMove="javascript:window.status='Go to Previous Page';"><font size="3">&#171;</font> Prev</a>
			<? }else{ ?>
				<!--<a href="#" onmouseout="javascript:window.status='Done';" onmousemove="javascript:window.status='Go to Previous Page';"><font size="3">&#171;</font> Prev</a>-->
			<? }?></td>
		<td align="center"><? ####### script to display no of pages #########
			//condition where no of pages is less than display limit
			$displayPageLmt = $GLOBALS['show']; #holds no of page links to display
			if($noOfPages <= $displayPageLmt){
				for($pgLink = 1; $pgLink <= $noOfPages; $pgLink++){
					if($pgLink==$_REQUEST[pageNo]){
						echo "<a href=\"#\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\">[$pgLink]</a>";
					}
					else{
						echo "<a href=\"javascript:disPage($pgLink)\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\">$pgLink</a>";
					}	
					if($pgLink<>$noOfPages) echo "&nbsp;|&nbsp;";
				} #end of for loop
			} #end of if
			//condition for no of pages greater than display limit
			if($noOfPages > $displayPageLmt){
				if(($_REQUEST[pageNo]+($displayPageLmt-1)) <= $noOfPages){
					for($pgLink = $_REQUEST[pageNo]; $pgLink <= ($_REQUEST[pageNo]+$displayPageLmt-1); $pgLink++){
						if($pgLink==$_REQUEST[pageNo]){
							echo "<a href=\"#\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\">[$pgLink]</a>";
						}
						else{
							echo "<a href=\"javascript:disPage($pgLink)\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\">$pgLink</a>";
						}
						if($pgLink<>($_REQUEST[pageNo]+$displayPageLmt-1)) echo "&nbsp;|&nbsp;";
					}#end of for loop						
				}#end of inner if
				else{
					for($pgLink = ($noOfPages - ($displayPageLmt-1)); $pgLink <= $noOfPages; $pgLink++){
						if($pgLink==$_REQUEST[pageNo]){
							echo "<a href=\"#\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\">[$pgLink]</a>";
						}
						else{
							echo "<a href=\"javascript:disPage($pgLink)\" style=\"text-decoration:none\" onmouseout=\"javascript:window.status='Done';\" onmousemove=\"javascript:window.status='Go to this Page';\">$pgLink</a>";
						}
						if($pgLink<>$noOfPages) echo "&nbsp;|&nbsp;";
					}#end of for loop
				}					
			}#end of if noOfPage>displayPageLmt
		?></td>
		<td width="15%" align="right"><? if($_REQUEST[pageNo] != $noOfPages) { ?>
				<a href="javascript:nextPage(<?=$_REQUEST[pageNo] ?>)" onMouseOut="javascript:window.status='Done';" onMouseMove="javascript:window.status='Go to Next Page';">Next <font size="3">&#187;</font></a>
			<? }else{ ?>
				<!--<a href="#" onmouseout="javascript:window.status='Done';" onmousemove="javascript:window.status='Go to Next Page';">Next <font size="3">&#187;</font></a>-->
			<? }?></td>
	  </tr>
	  <? if($noOfPages > 1){ ?>
	  <tr>
		<td colspan="3" align="center">Page no. : <select onChange="javascript:disPage(this.value);" style="font-family:verdana; font-size:11px">
		
		
		<? for($i=1;$i<=$noOfPages;$i++){?>
			<option value="<?=$i;?>"<?=($_REQUEST[pageNo]==$i)?"selected":"";?>><?=$i;?></option>
			
		<? }?>
		</select></td>	
	  </tr>
	  <? } ?>
	</table>
<?
}
function comboPopulation($getSql,$selectedValue)
{	
   $combo_sql=$getSql;
   $combo_rs=mysql_query($combo_sql);	
   while($combo_rows=mysql_fetch_row($combo_rs))
   {   $strSelected = ""; 
   	   if($combo_rows[0]==$selectedValue)
   		{ $strSelected="SELECTED";}
 	  							   
	   echo "<option value='".$combo_rows[0]."' ".$strSelected.">".stripslashes($combo_rows[1])."</option><br>";
   }	   
   mysql_free_result($combo_rs);	
}


function thumbnail($filethumb,$file,$Twidth,$Theight,$tag)
{
list($width,$height,$type,$attr)=getimagesize($file);
	switch($type)
	{
		case 1:
			$img = ImageCreateFromGIF($file);
		break;
		case 2:
			$img=ImageCreateFromJPEG($file);
		break;
		case 3:
			$img=ImageCreateFromPNG($file);
		break;
	}
	if($tag == "width") //width constraint
	{
		$Theight=round(($height/$width)*$Twidth);
	}
	elseif($tag == "height") //height constraint
	{
		$Twidth=round(($width/$height)*$Theight);
	}
	else
	{
		if($width > $height)
			$Theight=round(($height/$width)*$Twidth);
		else
			$Twidth=round(($width/$height)*$Theight);
	}
	$thumb=imagecreatetruecolor($Twidth,$Theight);
	if(imagecopyresampled($thumb,$img,0,0,0,0,$Twidth,$Theight,$width,$height))
	{
		
		switch($type)
		{
			case 1:
				ImageGIF($thumb,$filethumb);
			break;
			case 2:
				ImageJPEG($thumb,$filethumb);
			break;
			case 3:
				ImagePNG($thumb,$filethumb);
			break;
		}
		chmod($filethumb,0666);
		return true;
	}
}	
?>

<?
function disphtml_new($what){?>
	<html>
	<head>
	<title><?=SITETITLE?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="../css_style/default.css" type="text/css">
	<script language="JavaScript">
	function logout()
	{
		document.frm_logout.submit();
		
	}
	function showImage(img_url,width,height){
		var ah=screen.availHeight-30;
		var aw=screen.availWidth-10;
		var xc=(aw-width)/2;
		var yc=(ah-height)/2;
		window.open("admin_showimage.php?img_url="+img_url,"img","width="+width+",height="+height+",left="+xc+",top="+yc+",location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no,dependent=yes,directories=no,titlebar=no");
	}
	</script>
	<form name="frm_logout"	action="index.php" method="post">
	<input name="mode" type="hidden" value="logout">
	</form>
	</head>
	<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="780" height="100%">
	<tr>
		<td colspan="2" align="center" height="90"><? include("admin_top.php");?></td>
	</tr>
	<? if($_SESSION['admin_userid']==''){?>
		<tr>
			<td colspan="2" align="center" valign="top"><?=eval($what);?></td>
		</tr>
	<? }else{?>
		<tr>
			<!--<td width="23%" valign="top" bgcolor="#DADDEC"><? //include("admin_left.php");?></td>-->
			<td valign="top" colspan="2"><?=eval($what);?></td>
		</tr>
	<? }?>
	<tr>
		
    <td colspan="2" align="center" height="20" bgcolor="#939FC7"><font color="#FFFFFF" size="1" face="Verdana">Copyright 
      &copy; 
      <?=date('Y');?>
      www.forrentinChennai.com - All Rights Reserved</font></td>
	</tr>
	</table>
	</body>
	</html>
<? } ?>

<script id="clientEventHandlersJS" language="javascript"  type="text/javascript">
var url = "data_combo.php?param="; // The server-side script
var isWorking = false;
var http = getHTTPObject(); // We create the HTTP Object


function handleHttpResponse()
 {
  if (http.readyState == 4) 
  {
    if (http.responseText.indexOf('invalid') == -1)
	 {
      // Use the XML DOM to unpack the city and state data 
	
	//var x = new Array();
	//var y = new Array();
	
	var arr_state_id = new Array();
	var arr_state_name = new Array();
		
	if (window.ActiveXObject)
	{
		var xmlDocument = http.responseXML;
		//alert(http.responseText);
		var x = new Array();
		var y = new Array();
		x=xmlDocument.getElementsByTagName("state_code");
		y=xmlDocument.getElementsByTagName("state_name");
		for(var i=0;i<=x.length-1;i++)
			 arr_state_id[i] = x.item(i).text;
		for( i=0;i<=y.length-1;i++)
			 arr_state_name[i] = y.item(i).text;
	}
	else
	{
		/* <--cut it out-->*/
		var xmlDocument = http.responseText;
		//alert(xmlDocument);
		var arr = new Array();
		var brr = new Array();
		arr = xmlDocument.split('?');
		var j =0;
		alert(arr.length);
		for(var i=0;i<arr.length;i++)
		{
			if(arr[i] != '')
			{
				//alert(arr[i]);
				brr = arr[i].split('#');
				arr_state_id[j] = brr[0];
				arr_state_name[j] = brr[1];
				j += 1;			
			}
		}
			/*<-cut it out-> */
	}
	/*var xmllb = document.getElementById('xmllb');
	xmllb.innerHTML = xmlDocument;*/
    isWorking = false;
	
	for(i=0;i<=document.getElementById('state_code').options.length;i++)
	{
		document.getElementById('state_code').options[0] = null ;
		i=0;
		if(document.getElementById('state_code').options.length == 0) break;
	} 
	var pnt = -1;
	for(j=0;j<arr_state_name.length;j++)
	{
			pnt +=1;
			var new_opt= new Option(arr_state_name[j],arr_state_id[j]) ;
			document.getElementById('state_code').options[pnt]=new_opt ;
	}
      /*var state = xmlDocument.getElementsByTagName('state').item(0).firstChild.data;
	  state = state + xmlDocument.getElementsByTagName('state').item(1).firstChild.data;
      document.getElementById('city').value = city;
      document.getElementById('state').value = state;*/
     
	 
	 // isWorking = false;
	  
    }
  }
}

function f_change() 
{
	var countryElement = document.getElementById("country_abbr");
	var countryValue = countryElement.options[countryElement.selectedIndex].value;
	if (!isWorking && http)
    {
		// --> var countryValue = countryElement.options[countryElement.selectedIndex].value;
		//alert(countryValue);
		var browse_stat = "&browse_stat=" + ((window.ActiveXObject)?'1':'2');
		//alert(browse_stat);
		http.open("GET", url + escape(countryValue) + browse_stat , true);
		//alert(url+countryValue+browse_stat);
		//window.location=url+countryValue+browse_stat;
		http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		http.onreadystatechange = handleHttpResponse;
		isWorking = true;
		http.send(null);
    }
}

function getHTTPObject() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
	  xmlhttp.overrideMimeType("text/xml");
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}
</script>
