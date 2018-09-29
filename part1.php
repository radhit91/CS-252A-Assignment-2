<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	Employees Search:<br>
	<table>
		<tr>
			<td>
				ID:
			</td>
			<td>
				<input type="text" name="id" value="">
			</td>
		<tr>
			<td>
				Last_Name:
			</td>
			<td>
				<input type="text" name="last" value="">
			</td>
		</tr>
		<tr>
			<td>
				Department:
			</td>
			<td>
				<input type="text" name="dept" value="">
			</td>
		</tr>
	</table>
	<br>
	Employees Within Department:<br>
	<table>
		<tr>
			<td>
				Department:
			</td>
			<td>
				<input type="text" name="tenure" value="">
			</td>
		</tr>
	</table>
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
	$servername = "localhost";
	$username = "newuser";
	$password = "@314_Pass";

	// Create connection
	// 'About to Connect'."\n";
	$conn=mysqli_connect("localhost","newuser","@314_Pass","employees");
	if (!$conn) {
    		error_log("Failed to connect to database!", 0);
	}
	//echo 'Connected'."\n";

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	echo 'Connected successfully'."<br>";
	$id=$_POST['id'];
	$last=$_POST['last'];
	$dept=$_POST['dept'];
	$tenure=$_POST['tenure'];
	if($id!=NULL)
	{
		$sql="select * from employees where emp_no=".$id;
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
			echo '#'.$row["emp_no"]." <".$row["birth_date"]."> ".$row["first_name"]." ".$row["last_name"]." '".$row["gender"]."' <".$row["hire_date"]."><br>";
			}
		}
	}
	else if($last!=NULL)
	{
		$sql="select * from employees where last_name='".$last."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
			echo '#'.$row["emp_no"]." <".$row["birth_date"]."> ".$row["first_name"]." ".$row["last_name"]." '".$row["gender"]."' <".$row["hire_date"]."><br>";
			}
		}
	}
	else if($dept!=NULL)
	{
		$sql="select * from employees where emp_no in (select emp_no from dept_emp where dept_no in (select dept_no from departments where dept_name='".$dept."'))";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
			echo '#'.$row["emp_no"]." <".$row["birth_date"]."> ".$row["first_name"]." ".$row["last_name"]." '".$row["gender"]."' <".$row["hire_date"]."><br>";
			}
		}
	}
	else if($tenure!=NULL)
	{
		$sql="SELECT *, DATEDIFF(to_date, from_date) AS tenure, employees.first_name, employees.last_name FROM dept_emp INNER JOIN employees ON employees.emp_no=`dept_emp`.emp_no AND dept_emp.dept_no in (select dept_no from departments where dept_name='".$tenure."') ORDER BY tenure DESC";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
			echo '#'.$row["emp_no"].'--'.$row["first_name"].' '.$row["last_name"].'--'.$row["tenure"]."<br>";
			}
		}
	}
}
?>