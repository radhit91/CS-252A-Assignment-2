<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	First Information Report(FIR) Search<br/><br>
  Queries available for search:- <br/>
  <br/>
1. District with most reported criminal cases. <br/>
2. Most inefficient Police Station. <br/>     
3. Crime laws that are most and least applied in reported criminal cases. <br/>   
      

	<table>
		<tr>
			<td>
				<br/>Query Number:
			</td>
			<td>
                        <br/>
				<input type="text" name="id" value="">
			</td>
		<tr>
	</table>	
        <br/>	
        
	<input type="submit">
</form>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	/*// Create connection
	echo "abcd";
	$conn = mysqli_connect("127.0.0.1","abhinav","@314_Pass");
	echo "xyzw";
	// Check connection
	/*if (!$conn) {
		die("Connection failed: " . $conn->connect_error);
	} 
	echo "Connected successfully";*/   
	/*$servername = "localhost";
	$username = "root";
	$password = "radhit";

	// Create connection
	// 'About to Connect'."\n";
	$conn=mysqli_connect($servername,$username,$password,"employees");
	if (!$conn) {
    		error_log("Failed to connect to database!", 0);
	}
	echo 'Connected'."\n";

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	echo 'Connected successfully'."<br>";*/
	$id=$_POST['id'];
	
	

ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php'; 
$m = new MongoDB\Client("mongodb://localhost:27017");

$db = $m->test;
//echo $db;
//echo nl2br("\n");

$collection  = $db->data;

//echo $collection;
//echo nl2br("\n");
//echo $collection->count(['DISTRICT' => 'AGRA']);

//$cursor = $collection->find();
/*foreach ( $cursor as $id => $vsize )
{
    echo "$id: ";
    //var_dump( $value );
echo nl2br("\n");
}*/
//$D =  $collection->find( ['DISTRICT' => 'AGRA'] );
$max = 0;
$max_ineff = 0;
$ps_ineff = "";

$place = "";
echo nl2br("\n");
###################################First Query########################################
if($id==1)
{
  $cursor = $collection->distinct("DISTRICT");
  foreach ( $cursor as $district ){
	//echo "$district";
        //echo nl2br("\n");
	$val = $collection->count(['DISTRICT' => $district]);
	//echo "$val";
	if($val > $max){
		$max = $val;
		$place = $district;
	}
	//echo nl2br("\n");
}
echo "District with maximum reported crime is $place";
echo nl2br("\n");
echo "No of criminal cases reported in $place is $max";
}
###################################First Query########################################

###################################Second Query#######################################
else if($id==2)
{
 
  $cursor = $collection->distinct("PS");
  foreach ( $cursor as $ps ){
	//echo "$ps  ";
	$val = $collection->count(['PS' => $ps]);
        $vali = $collection->count(['PS' => $ps , 'Status' => 'Pending']);
        $temp = $vali/$val;
        if($temp > $max_ineff)
     {
        $max_ineff = $temp;
        $ps_ineff = $ps;
     }          
        //echo nl2br("\n");
}
echo "Most inefficient Police Station is $ps_ineff";
echo nl2br("\n");
echo "Inefficiency(fraction of pending cases) of $ps_ineff is $max_ineff";
}
###################################Second Query#######################################
###################################Third Query#######################################
else if($id==3)
{
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
//print "maximum is $maxl";
echo "Crime law most likely applied in FIR is $maxl";
echo nl2br("\n");
//print "minimum is $minl";
echo "Crime law least likely applied in FIR is $minl";
echo nl2br("\n");
}
###################################Third Query#######################################
else 
{
   echo "Invalid Choice - Please select from among the above query options";
}
}

?>

