<!DOCTYPE html>

<html>
<body>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php'; 
$m = new MongoDB\Client("mongodb://localhost:27017");

$db = $m->test;
echo $db;
echo nl2br("\n");

$collection  = $db->news;

echo $collection;
echo nl2br("\n");
echo $collection->count(['DISTRICT' => 'AGRA']);

//$cursor = $collection->find();
/*foreach ( $cursor as $id => $vsize )
{
    echo "$id: ";
    //var_dump( $value );
echo nl2br("\n");
}*/
//$D =  $collection->find( ['DISTRICT' => 'AGRA'] );
$max = 0;
$place = "";
echo nl2br("\n");
###################################First Query########################################
$cursor = $collection->distinct("DISTRICT");
foreach ( $cursor as $district ){
	$val = $collection->count(['DISTRICT' => $district]);
	//echo "$val";
	if($val > $max){
		$max = $val;
		$place = $district;
	}
	//echo nl2br("\n");
}
echo "Place is $place with $max";
$c = 0;
###################################First Query########################################

###################################Second Query#######################################
/*$cursor = $collection->distinct("PS");
foreach ( $cursor as $ps ){
	//$val = $collection->count(['DISTRICT' => $district]);
	echo "$ps";
	echo nl2br("\n");
}*/
###################################Second Query#######################################
###################################Third Query#######################################
echo nl2br("\n");
$maxcl = 0;
$mincl = INF;
$maxl = "";
$minl = "";
$cursor = $collection->distinct("Act_Section");
foreach ($cursor as $as){
	$val = $collection->count(['Act_Section' => $as]);
	if($val > $maxcl){
		$maxcl = $val;
		$maxl = $as;
	}
	if($val < $mincl){
		$mincl = $val;
		$minl = $as;
	}
}
print "maximum is $maxl";
echo nl2br("\n");
print "minimum is $minl";
echo nl2br("\n");
###################################Third Query#######################################
?>

