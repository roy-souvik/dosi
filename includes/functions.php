<?
function OrderByColumn($column_name,$field_name,$order_field,$order_by,$optional='')
	{
	
		$str = "<A href=\"javascript:toggle_order('".$field_name."','".$optional."')\">".$column_name."</a>";
		if ($order_field == $field_name && $GLOBALS['mode_toggle'] == "toggle" || $optional ="class" || $optional =="section") 
		$str = $str;
		//echo $str;
		return $str;		
	}
	
function fetchCountryName($ID)

  {

     $country_sql="select * from ".TABLE_COUNTRY_MASTER." where is_active=1";

	 $country_rs=mysql_query($country_sql);

	 while($country_rec=mysql_fetch_array($country_rs))

	 {

	   if($country_rec['country_id']==$ID)

	   {

	        echo "<option value='".$country_rec['country_id']."' selected>".$country_rec['country_name']."</option>";

	   }

	   else

	   {

	        echo "<option value='".$country_rec['country_id']."'>".$country_rec['country_name']."</option>";

	   }

	 }

	 mysql_free_result($country_rs);

  }

function fetchStateName($ID)

  {

     $state_sql="select * from ".TABLE_STATE_MASTER." where is_active=1";

	 $state_rs=mysql_query($state_sql);

	 while($state_rec=mysql_fetch_array($state_rs))

	 {

	   if($state_rec['state_id']==$ID)

	   {

	        echo "<option value='".$state_rec['state_id']."' selected>".$state_rec['state_name']."</option>";

	   }

	   else

	   {

	        echo "<option value='".$state_rec['state_id']."'>".$state_rec['state_name']."</option>";

	   }

	 }

	 mysql_free_result($state_rs);

  }

function getCountryNamebyID($ID) 

 {

      $country_sql="select  country_abbr from  ".TABLE_COUNTRY_MASTER." WHERE  country_id=".$ID;

	  $country_rs=mysql_query($country_sql);

	  $country_rec=mysql_fetch_array($country_rs);

	  $country_name=$country_rec['country_abbr'];

	  mysql_free_result($country_rs);

	  return $country_name;

 }

 function DisplayAlphabet(){

		$str="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";

		$str=split(",",$str);

		for($i=0; $i < sizeof($str); $i++){

			echo "<a href=\"#\" class='link' onClick=\"javascript:search_alpha('".$str[$i]."')\">$str[$i]</a>&nbsp;&nbsp;&nbsp;";

		}

}

function getValue($TableName,$ID,$ID_Name,$FieldNames)

{

	$sql_getValue = "Select ".$FieldNames." from ".$TableName." where ".$ID_Name."='".$ID."'";

	

	$query_getValue = mysql_query($sql_getValue);

	$rs_getValue = mysql_fetch_array($query_getValue);

	

	if (mysql_num_rows($query_getValue) > 0) return $rs_getValue[0];

	else return "";

	

	mysql_free_result($query_getValue);

}

function insert_record($table_name,$blockValueArray,$add_field='')
{
	$sql = "INSERT INTO ".$table_name." SET ";
	foreach($_POST as $key=>$value)
	{
	 	if(!in_array($key,$blockValueArray))
	  	{	
			$sql1.= $key ." ='".trim($value)."'".",";
		}
	}
	$sql1 = substr($sql1,0,-1);
	$query = $sql ." " .$sql1.($add_field!=''?",".$add_field."":"");
	return  mysql_query($query) or die(mysql_error()."Error in insert".$query);
}

function update($table_name1,$unique_table_id,$edit_id){

	 $val = $_POST;

	$sql = "update  ".$table_name1." set ";

		foreach($val as $key=>$value){

			if($key != "mode" ){

				if($key != 'submit'){

					if($key != 'row_id'){

						$sql1.= $key ." ='".$value."'".",";

					}	

				}

				$sql4  = "where " .$unique_table_id." = ".$edit_id."";

			}

		}

	 $sql2 = substr($sql1,0,-1);

	 $query= $sql ." " .$sql2 . " " .$sql4;

	return mysql_query($query) or die(mysql_error()."Error in Update");

}

function duplicate_check($table_name,$field1,$field2='',$field3=''){

	$sql = "select * from ".$table_name." where ".$field1." = '".$_POST[$field1]."'";

	if($field2 != ''){

		$sql.= " and  ".$field2." = '".$_POST[$field2]."'";

	}

	if($field3 != ''){

		$sql.= "and ".$field3." = '".$_POST[$field3]."'";

	}

	$query = mysql_query($sql);

	return $query;

}

function record_existance_another_table($table_name,$field1,$field2,$field3){

	$sql = "select * from ".$table_name." where ".$field1." = '".$_POST[$field1]."' and  ".$field2." = '".$_POST[$field2]."'

			and ".$field3." = '".$_POST[$field3]."'";

	$query = mysql_query($sql);

	return $query;		

			

}

function duplicate_check_for_edit($table_name,$field1,$field2='',$field3='',$unique_table_id,$edit_id){

	$sql = "select * from ".$table_name." where ".$field1." = '".$_POST[$field1]."' ";

	if($field2 != ''){

		$sql .= " and  ".$field2." = '".$_POST[$field2]."' ";

	}

	if($field3 != ''){	

		$sql .= " and ".$field3." = '".$_POST[$field3]."' ";

	}	 

	$sql .= "and " .$unique_table_id." <> ".$edit_id."";

	$query = mysql_query($sql);

	return $query;

}

function view_edit_records($table_name,$unique_table_id,$edit_id){

	$sql=mysql_query("select * from ".$table_name ." where " .$unique_table_id." = ".$edit_id."") or die(mysql_error());

	$rec = mysql_fetch_array($sql);

	return $rec;

}

function view_records_details($table_name,$unique_table_id,$edit_id){

	$sql=mysql_query("select * from ".$table_name ." where " .$unique_table_id." = ".$edit_id."") or die(mysql_error());

	$rec = mysql_fetch_array($sql);

	return $rec;

}

function DeleteRecord($table_name,$unique_table_id,$edit_id){

	$sql = mysql_query("delete from ".$table_name ." where " .$unique_table_id." = ".$edit_id."") or die(mysql_error());

	return $sql;

}

function StatusChange($table_name,$unique_table_id,$edit_id,$status_field){

	

	$sql = mysql_query("update ".$table_name." set ".$status_field." = if(".$status_field." = 'N','Y','N') 

						where ".$unique_table_id." = ".$edit_id."") or die(mysql_error());



	//return $sql;					

}

function pass($len=10){

$pass="";

$num = "0123456789abcdefghijklmnopqrstuvwxyz";

$i=0;

	while($i < 10){

		$char = 



substr($num,mt_rand(0,strlen($num)-1),1);

		$pass .= $char;

		$i++;

	}

 return $pass;

}

function getWords($text, $limit){

	$array = explode(" ",$text, $limit+1);

		if (count($array) > $limit)

		{

			unset($array[$limit]);

		}

	return implode(" ", $array);

}

?>