<?php


$fors_s = $_GET['fors'];
$overs_for_s = $_GET['overs_for'];
$against_s = $_GET['against'];
$overs_against_s = $_GET['overs_against'];
$b_point_s = $_GET['b_point'];
$bonous_s = $_GET['bonous'];

$for=explode( '/', $fors_s);
$t_for=$for[0];
	
$o_for=strstr($overs_for_s, '.');
if($o_for==''){ $t_ball_for=$overs_for_s*6;}
else{
	$o_for_rate=explode( '.', $overs_for_s);
	$o_for_rate1=$o_for_rate[0]*6;
	$t_ball_for=$o_for_rate1+$o_for_rate[1];
}

$against=explode( '/', $against_s);
$t_against=$against[0];

$o_against=strstr($overs_against_s, '.');
if($o_against==''){ $t_ball_against=$overs_against_s*6;}
else{
	$o_against_rate=explode( '.', $overs_against_s);
	$o_against_rate1=$o_against_rate[0]*6;
	$t_ball_against=$o_against_rate1+$o_against_rate[1];
}
$total_for=$t_for/$t_ball_for;
$for_total=$total_for*6; 

$total_against=$t_against/$t_ball_against;
$against_total=$total_against*6;
$rate=$for_total-$against_total;
if($b_point_s==1){
	if($bonous_s=="p"){ 
	
		$rates=$rate+0.5;
    
	}
	else{ 
		$rates=$rate-0.5;
   
	}
}
else{ 
	$rates=$rate;
 
}
//echo '<br>';
//echo $rate-0.5;

?>
<table border="0">
<tr>
    <td width="76">NRR</td>
    <td><input type="text" id="run_rate" name="run_rate" readonly="readonly" value="<?=$rates?>"/></td>
</tr>
</table>





