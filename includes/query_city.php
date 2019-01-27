<?php

require_once('config.php');
require_once('db_functions.php');
$db = connect_to_db();

echo $state = $_GET['state']; exit;
$selectedCity = $_GET['selectedCity'];

$cities = get_cities_from_db($db,$state);

echo '<option value="">-Select City-</option>';
if(!empty($cities)) {
  foreach($cities as $city) {
      echo '<option value="'.$city.'" '.($city==$selectedCity?'selected="selected"':'').'>'.$city.'</option>';
  }
}
if(isset($_GET['register']))
{
	if($_GET['register']==1)
	{
		$state = $_GET['state'];
				
		$cities = get_cities_from_db($db,$state);
		
		//echo '<option value="">-Select City-</option>';
		if(!empty($cities)) {
		  foreach($cities as $city) {
			  echo '<option value="'.$city.'">'.$city.'</option>';
		  }
		}
	}
	
}

?>