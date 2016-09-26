<?php
session_start();
include 'configdb.php';

$username=$_POST['username'];
$password=$_POST['password'];

$browser = $_SERVER['HTTP_USER_AGENT'];
$logintime = date("h:i:sa");
$logindate = date("m/d/Y");
$clientip = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT registration.FName,Login_Type,login.User_Id FROM login INNER JOIN registration ON login.User_Id = registration.User_Id WHERE Username='$username' and Password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) 
    {
    
     
    // output data of each row
    while($row = $result->fetch_assoc())
          {
           $FName=$row["FName"];
           $LoginType=$row["Login_Type"];
           $User_Id=$row["User_Id"];
           $sqllogin = "INSERT INTO log_record (User_Id,Username,browser,login_time,login_date,client_ip) VALUES ('$User_Id','$username','$browser','$logintime','$logindate','$clientip')";
           $record = $conn->query($sqllogin);
          }
      if($LoginType=='User')
          {
		  header("Location:index.php");
          $_SESSION['uname'] = $FName;
          $_SESSION['User_Id'] = $User_Id;
          $_SESSION['LoginMsg']='You Have Logged In Successfully';
          }
     else 
      if($LoginType=='Admin')
          {
		
          header("Location:Admin/");
          $_SESSION['admin'] = $FName;
          $_SESSION['AdminMsg']='Welcome To Administrator Panel.';
          }
    } else
    {
		header("Location:login.php");
		$_SESSION['LoginMsg']='Invalid Username or Password';
    }
$conn->close();
?>





