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
	Count of Employees:<br>
	<table>
		<tr>
			<td>
				Department:
			</td>
			<td>
				<input type="text" name="dept_name" value="">
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
	<br>
	Gender Ratio:<br>
	<table>
		<tr>
			<td>
				Department:
			</td>
			<td>
				<input type="text" name="gender" value="">
			</td>
		</tr>
	</table>
	<br>
	Gender Pay Ratio:<br>
	<table>
		<tr>
			<td>
				Department:
			</td>
			<td>
				<input type="text" name="pay" value="">
			</td>
		</tr>
		<tr>
			<td>
				Title:
			</td>
			<td>
				<input type="text" name="title" value="">
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
	$conn=mysqli_connect($servername,$username,$password,"employees");
	if (!$conn) {
    		error_log("Failed to connect to database!", 0);
	}
	//echo 'Connected'."\n";

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	//echo 'Connected successfully'."<br>";
	$id=$_POST['id'];
	$last=$_POST['last'];
	$dept=$_POST['dept'];
	$dept_name=$_POST['dept_name'];
	$tenure=$_POST['tenure'];
	$gender=$_POST['gender'];
	$dept_pay=$_POST['pay'];
	$title=$_POST['title'];
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
	else if($dept_name!=NULL)
	{
		$sql="select * from (select t1.dept_name,count(t2.emp_no) from departments t1  join dept_emp t2 on t1.dept_no=t2.dept_no group by t1.dept_name) as t3 where dept_name='".$dept_name."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
			echo '#'.$row["dept_name"]." ".$row["count(t2.emp_no)"]."<br>";
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
	else if($gender!=NULL)
	{
		$sql="select count(emp_no) from employees where gender='M' AND emp_no in (select emp_no from dept_emp where dept_no in (select dept_no from departments where dept_name='".$gender."'))";
		$result = $conn->query($sql);
		if($row = $result->fetch_assoc())
		{
			$v1=$row['count(emp_no)'];
			$sql="select count(emp_no) from employees where gender='F' AND emp_no in (select emp_no from dept_emp where dept_no in (select dept_no from departments where dept_name='".$gender."'))";
			$result = $conn->query($sql);
			if($row = $result->fetch_assoc())
			{
				$v2=$row['count(emp_no)'];
				if($v2!=0)
					echo $gender." (Male_To_Female Ratio) : ".($v1/$v2);
				else
					echo "No females in ".$gender;
			}
		}
	}
	else if($dept_pay!=NULL && $title!=NULL)
	{
		$sql="select sum(salary) from salaries where emp_no in(select emp_no from employees where emp_no in(select emp_no from titles where title='".$title."' and emp_no in (select emp_no from dept_emp where dept_no in (select dept_no from departments where dept_name='".$dept_pay."'))) and gender='M') and to_date in (select max(to_date) from salaries where emp_no in (select emp_no from titles))";
		$result = $conn->query($sql);
		if($row = $result->fetch_assoc())
		{
			$v1=$row['sum(salary)'];
			$sql="select sum(salary) from salaries where emp_no in(select emp_no from employees where emp_no in(select emp_no from titles where title='".$title."' and emp_no in (select emp_no from dept_emp where dept_no in (select dept_no from departments where dept_name='".$dept_pay."'))) and gender='F') and to_date in (select max(to_date) from salaries where emp_no in (select emp_no from titles))";
			$result = $conn->query($sql);
			if($row = $result->fetch_assoc())
			{
				$v2=$row['sum(salary)'];
				if($v2!=0)
					echo $dept_pay." (Male_To_Female Pay for ".$title." ) : ".($v1/$v2);
				else
					echo "No females in ".$dept_pay." working as ".$title;
			}
		}
	}
}
?>
