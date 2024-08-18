<?php
/* We need to start or resume the session to store session variables 
like username etc. */
session_start();
// Make sure we report all errors 
error_reporting(E_ALL);
ini_set('display_errors',1);
// We're using this to work out if we've had a login issue later
$loginerror = FALSE;
// Are we posting a logout signal to this page? If so, unset the session variables
//isset() will return false when checking a variable that has been assigned to null. 
//Also note that a null character ("\0") is not equivalent to the PHP null constant.
if (isset($_POST["logout"]))
{
  unset($_SESSION["user"]);
  unset($_SESSION["id"]);
}
// If we are posting the username and password to attempt a login, handle that here
if(isset($_POST["username"]) &&
 isset($_POST["password"]))
{
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  if (!$conn){
        die("Connection failed");
  }
  $user=$_POST["username"];
  $pass=$_POST["password"];
// the backticks (``) are used to enclose the table and column names in the SQL query. 
/*It's worth mentioning that using backticks for table and column names
 is MySQL-specific syntax. Other database systems, like PostgreSQL or 
 SQLite, may use double quotes (") or square brackets ([]) instead of 
 backticks for identifier delimiting. If you're writing code that needs
  to be compatible with multiple database systems, you might want to 
  use a database abstraction layer or ORM (Object-Relational Mapping) 
  library, which can handle these differences for you.*/
  $query="SELECT * FROM `Users` WHERE `username`='$user'
          AND `password`='$pass'";
  $result=mysqli_query($conn,$query);
  
  $id=-1;
  if ($result->num_rows > 0) 
  {
    $result->num_rows;
//The fetch_assoc() / mysqli_fetch_assoc() function fetches a result row 
//as an associative array(dictionary).
    $row = $result->fetch_assoc();
    $id = $row["id"];
  }
//Information about the current user is kept in the session variables 
//and accesible to all the pages of a web application. The global PHP 
//$_SESSION variable stores values of all session variables.

  if($id != -1 && !isset($_SESSION["user"]) && !isset($_SESSION["id"]))
  {
    $_SESSION["user"] = $user;
    $_SESSION["id"] = $id;
  }
  // If there was a login attempt, i.e., user and pass were POST-ed but
  // the session vars are not set, then there's been a login problem, so flag it
  
  elseif(!isset($_SESSION["user"]) && !isset($_SESSION["id"]))
  {
    $loginerror = TRUE;
  }
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Helin_Lei_CW2</title>
    <link rel="stylesheet" href="../css/mvp.css">
    <style>
         table,td{
         padding: 0.3rem;
         border:1px solid black;
         border-collapse:collapse;
         text-align:left;
         background-color:#F9E79F;
         color:#A9CCE3;
         font-family: Tahoma, Arial, Helvetica, sans-serif;}
         th{
            padding:0.3rem;
            background-color: #F4D03F;
            color:#FEF5E7;}
         h1{
            font-family: Charcoal,sans-serif;
            color: #A9CCE3;}
         p,form{
            font-family: Arial, Helvetica, sans-serif;
            color:#A9CCE3;}
      </style>
</head>
<body>
<main>
    <h1>Police system
<?php

// Q1:Selectively show the logout button / form only if this user is logged in            
if(isset($_SESSION["user"]) && isset($_SESSION["id"]))
{
  ?>
  <form method="POST">
      <?php
      echo "<p>" . $_SESSION["user"] . " is logged in</p>";
      ?>
      <p>new_password: <input type="text" name="new_password"><br/>
          <input type="submit" name="change password" value="Change password"/>
          <input type="submit" name="logout" value="Logout"/>
  </form>
  <?php
}
else if (!isset($_SESSION["user"]) && !isset($_SESSION["id"]))
  {
    if ($loginerror)
      echo "<p>Invalid Username or Password</p>";

?>
    <form method="POST">
      Username: 
      <input name="username" type="text" id="user" value="" size="30" maxlength="32" required/><br/>
      Password: 
      <input name="password" type="password" id="pass" value="" size="30" maxlength="32" required/><br/>
      <input type="Submit" name="login" value="Login"/>
    </form>
<?php
  }
//Q1:Replace the old password with new_password

if(isset($_SESSION["user"]) &&
isset($_SESSION["id"]) &&
isset($_POST['new_password']) && 
$_POST['new_password']!="" )
{
  $newpass=$_POST['new_password'];
  $id = $_SESSION["id"];
  $query="UPDATE `Users` SET `password`= '$newpass' WHERE `id`='$id'";
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  $result=mysqli_query($conn,$query);
}



//Q2: Selectively show the Name searching box only if this user is logged in
if(isset($_SESSION["user"]) && isset($_SESSION["id"]))
{
  ?>
  <form method="POST">
      <p>Search by name or driving licence
      <p>Name: <input type="text" name="Name"><br/>
      <p>Driving Licence Number: <input type ="text" name="DLN"><br/>
          <input type="submit" name="submit" value="search"/>
  </form>
  <?php
}

//traversing arrays. It means assigning each element in the array to value 
//and assigning the corresponding key(array index) to $key. This allows you
// to iterate through the elements in the array one by one and operate on them.
function search_name($needle, $haystack){
  if(is_array($haystack)){
    foreach ($haystack as $value){
      if(stripos($needle, $value !== false )){
        return true;
      }
    }
  }else if(is_string($haystack)){
    return stripos($haystack, $needle) !== false;
  }
  return false;
}

//Determine whether $POST["Name"] is part of $row["People_name"](case insensitive)
//Get the full names of all when searching by name
if(isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
(isset($_POST["Name"]) && $_POST["Name"] !="" ))
{
  $Name =$_POST["Name"];
  $query="SELECT `People_name` FROM `People`";
  //echo "Query: " . $query . "<br>";
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  if(!$conn){
    die("failed to connect database: ".mysqli_connect_error());
  }
  $result=mysqli_query($conn,$query);
  echo "<ul>";
  $found = false;
  while($row= mysqli_fetch_assoc($result)){
    if (search_name($Name, $row["People_name"])){
      echo "<li>";
      echo $row["People_name"];
      echo "</li>";
      $found = true;
      $userid=$_SESSION["id"];
      $tableName='People';
      logAudit($tableName,'search',$userid,"Search by people name");
    }
  }
  echo "</ul>";
  if(!$found)
  {
    echo "The person is not in the system";
  }
  if($result !== null){
    mysqli_free_result($result);
  }
  }
  

//Q2:Print the full name of people when searching by driving licence
if(isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
(isset($_POST["DLN"]) && $_POST["DLN"] !=""))
{
  $DrivingLicenceNumber = $_POST["DLN"];
  //notice the difference between '' and ``
  $query="SELECT `People_name` FROM `People` WHERE `People_licence` ='$DrivingLicenceNumber'";
  //echo "Query: " . $query . "<br>";
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  if(!$conn){
    die("failed to connect database: ".mysqli_connect_error());
  }
  $result=mysqli_query($conn, $query);
  $found = false;
  echo "<ul>";  // start list loop through each row of the result 
  //(each tuple will be contained in the associative array $row)
  while($row = mysqli_fetch_assoc($result)) {
    // output name and phone number as list item
    echo "<li>";
    echo $row["People_name"]; 
    $found = true;
		echo "</li>";
    $userid=$_SESSION["id"];
    $tableName='People';
    logAudit($tableName,'search',$userid,"Search people by driving licence");
  } 
  echo "</ul>"; // end of list
  
  if(!$found ) // if query result is empty 
  {
    echo "The person is not in the system";
  }else if ($found){
    echo "The person is in the system";
  }
}
  // frees the memory associated with the result
  //if ($result !== null){
  //  mysqli_free_result($result);
  //}
  // close database connection
  //mysqli_close($conn);

//Q3: print the details of car when seaching by vehicle licence
if(isset($_SESSION["user"]) && isset($_SESSION["id"]))
{
  ?>
  <form method="POST">
    <p>Print the details of car when searching
    <p>Vehicle Licence: <input type="text" name="VL"><br/>
        <input type="submit" name="submit" value="search"/>
  </form>
  <?php
}

if(isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
(isset($_POST["VL"]) && $_POST["VL"] !=""))
{
  $VehicleLicence = $_POST["VL"];
  //notice the difference between '' and ``
  //COALESCE(): if data is NULL , print Unkown
  $query="SELECT V.Vehicle_type , V.Vehicle_colour , P.People_name, P.People_licence
  FROM `Vehicle` AS V
  LEFT OUTER JOIN `Ownership` AS O ON V.Vehicle_ID = O.Vehicle_ID
  LEFT OUTER JOIN `People` AS P ON O.People_ID = P.People_ID
  WHERE V.Vehicle_licence = '$VehicleLicence'
  UNION 
  SELECT V.Vehicle_type , V.Vehicle_colour , P.People_name, P.People_licence
  FROM `Vehicle` AS V
  RIGHT OUTER JOIN `Ownership` AS O ON V.Vehicle_ID = O.Vehicle_ID
  RIGHT OUTER JOIN `People` AS P ON O.People_ID = P.People_ID
  WHERE V.Vehicle_licence = '$VehicleLicence'";
  //echo "Query: " . $query . "<br>";
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  $result=mysqli_query($conn, $query);
  // $found is a signal for the result
  $found = false;
  echo "<table>";
  echo "<tr><th>Vehicle Type</th><th>Vehicle Colour</th><th>People Name</th><th>People Licence</th></tr>";
  //loop through each row of the result 
  while($row = mysqli_fetch_assoc($result)) 
  {
    // output name and phone number as list item
    echo "<tr>";
    echo "<td>".(isset($row["Vehicle_type"])? $row["Vehicle_type"]:'Unkown')."</td>"; 
    echo "<td>".(isset($row["Vehicle_colour"])? $row["Vehicle_colour"]:'Unkown')."</td>";
    echo "<td>".(isset($row["People_name"])? $row["People_name"]:'Unkown')."</td>";
    echo "<td>".(isset($row["People_licence"])? $row["People_licence"]:'Unkown')."</td>";
		echo "</tr>";
    $found = true;
    $userid=$_SESSION["id"];
    $tableName='Vehicle and People';
    logAudit($tableName,'search',$userid,"Search the details of vehicle by driving licence");
  } 
  echo "</table>"; // end of table
  
  if(!$found) // if query result is empty 
  {
    echo "The vehicle is not in the system";
  } 
}
  // frees the memory associated with the result
  //if ($result !== null){
  //  mysqli_free_result($result);
  //}
  // close database connection
  //mysqli_close($conn);

//Output error message when getting wrong user and password


//Q4: enter details for a new vehicle
if(isset($_SESSION["user"]) && isset($_SESSION["id"]))
{
  ?>
  <form method="POST">
    <p>Enter details for a new vehicle
    <p>New Vehicle Licence: <input type="text" name="NVL"><br/>
    <p>Vehicle make and model: <input type="text" name="mm"><br/>
    <p>Colour: <input type="text" name="colour"><br/>
    <p>Owner Name: <input type="text" name="ON"><br/>
    <p>Owner Address: <input type="text" name="OA"><br/>
    <p>Owner Licence: <input type="text" name="OL"><br/>
        <input type="submit" name="submit" value="enter"/>
  </form>
  <?php
}

//mysqli_query is a function used to execute a SQL query on a MySQL 
//database. It returns a mysqli_result object, which represents the 
//result set of the query. The mysqli_result object can then be used 
//to fetch the actual data from the database. So, the main difference 
//is that mysqli_query is used to execute the query, while mysqli_result
// is used to work with the result set of the query.
if(isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
(isset($_POST["NVL"]) && $_POST["NVL"] !="") &&
(isset($_POST["mm"]) && $_POST["mm"] !="") &&
(isset($_POST["colour"]) && $_POST["colour"] !="") &&
(isset($_POST["ON"]) && $_POST["ON"] !="") &&
(isset($_POST["OA"]) && $_POST["OA"] !="") &&
(isset($_POST["OL"]) && $_POST["OL"] !="")
)
{
  $NewVL= $_POST["NVL"];
  $MM= $_POST["mm"];
  $colour=$_POST["colour"];
  $ON=$_POST["ON"];
  $OA=$_POST["OA"];
  $OL=$_POST["OL"];
  //notice the difference between '' and ``
  $query="SELECT `People_licence` FROM `People` WHERE `People_licence` = '$OL'";
  //echo "Query: " . $query . "<br>";
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  $result=mysqli_query($conn, $query);
  //if the owner is already in system
  if ($result->num_rows > 0){
    echo $result->num_rows."rows<br><br/>";
    //store the new vehicle information
    $query2="INSERT INTO `Vehicle` (`Vehicle_licence`,`Vehicle_colour`,`Vehicle_type`)
    VALUES ('$NewVL','$colour','$MM')";
    $result2=mysqli_query($conn,$query2);
    // get the Vehicle ID
    if($result2)
    {
      $VID=mysqli_insert_id($conn);
    }else{
      echo mysqli_error($conn);
    }
    //$query3="SELECT `LAST_INSERT_ID()`";
    //$result3=mysqli_query($conn,$query3);
    //$row3=mysqli_fetch_array($result3);
    //get the people ID
    $query4="SELECT `People_ID` FROM `People` WHERE `People_licence` = '$OL'";
    $result4=mysqli_query($conn,$query4);
    $row4=mysqli_fetch_array($result4);
    //store the ownership into database
    //echo "$row4[People_ID]";
    $query5="INSERT INTO `ownership` (`People_ID`,`Vehicle_ID`)
    VALUES ('$row4[People_ID]','$VID')";
    $result5=mysqli_query($conn,$query5);
    if($result5)
    {
      echo "succeed in storing ownership";
      $userid=$_SESSION['id'];
      $tableName='ownership';
      logAudit($tableName,'Insert',$userid,"storing new ownership");
    }else{
      echo mysqli_error($conn);
    }
  }//if the vehicle owner is not in system
  else if ($result->num_rows == 0){
    //store the new vehicle information
    $query2="INSERT INTO `Vehicle` (`Vehicle_licence`,`Vehicle_colour`,`Vehicle_type`)
    VALUES ('$NewVL','$colour','$MM')";
    $result2=mysqli_query($conn,$query2);
    //Get the Vehicle ID 
    if($result2)
    {
      $VID=mysqli_insert_id($conn);
    }else{
      echo mysqli_error($conn);
    }
    //store the new person information
    $query4="INSERT INTO `People` (`People_name`,`People_address`,`People_licence`)
    VALUES ('$ON','$OA','$OL')";
    $result4=mysqli_query($conn,$query4);
    //Get the People ID 
    if($result4)
    {
      $PID=mysqli_insert_id($conn);
    }else{
      echo mysqli_error($conn);
    }
    //store the ownership into database
    $query6="INSERT INTO `ownership` (`People_ID`,`Vehicle_ID`)
    VALUES ('$PID','$VID')";
    $result6=mysqli_query($conn,$query6);
    if($result6)
    {
      echo "succeed in storing ownership";
      $userid=$_SESSION['id'];
      $tableName='ownership';
      logAudit($tableName,'Insert',$userid,"storing new ownership");
    }else{
      echo mysqli_error($conn);
    }
  }
  }

// Q5:file a report for an incident 
if(isset($_SESSION["user"]) && isset($_SESSION["id"]))
{
  ?>
  <form method="POST">
    <p>File a new report for an incident
    <p>New incident report: <input type="text" name="NIR"><br/>
    <p>Incident date: <input type="text" name="ID"><br/>
    <p>Licence of vehicle involved: <input type="text" name="LVI"><br/>
    <p>Licence of people involved: <input type="text" name="LPI"><br/>
    <!--please pay attention to indentation in line 436-->
    <select name="offencetype" id="offence">
      <option value="">--- Choose offence ---</option>
      <option value="1">Speeding</option>
      <option value="2">Speeding on a motorway</option>
      <option value="3">Seat belt offence</option>
      <option value="4">Illegal parking</option>
      <option value="5">Drink driving</option>
      <option value="6">Driving without a licence</option>
      <option value="7">Traffic light offences</option>
      <option value="8">Cycling on pavement</option>
      <option value="9">Failure to have control of vehicle</option>
      <option value="10">Dangerous driving</option>
      <option value="11">Careless driving</option>
      <option value="12">Dangerous cycling</option>
    </select>
    <input type="submit" name="submit" value="submit"/>
  </form>
  <form method="POST">
    <p>Enter new people into system
    <p>People Name: <input type="text" name="NName"><br/>
    <p>People Address: <input type="text" name="NAddress"><br/>
    <p>People Licence: <input type="text" name="NLicence"><br/>
    <input type="submit" name="submit" value="submit"/>
  </form>
  <?php
  if(isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
      (isset($_POST["NName"]) && $_POST["NName"] !="") &&
      (isset($_POST["NAddress"]) && $_POST["NAddress"] !="" &&
       isset($_POST["NLicence"]) && $_POST["NLicence"] !="") 
    ){
      $NName=$_POST["NName"];
      $NAddress=$_POST["NAddress"];
      $NLicence=$_POST["NLicence"];
      $query2="INSERT INTO `People` (`People_name`,`People_address`,`People_licence`)
               VALUES ('$NName','$NAddress','$NLicence')";
      $servername="mariadb";
      $username="root";
      $password="rootpwd";
      $dbname="coursework2";
      $conn = mysqli_connect($servername,$username,$password,$dbname);
      $result2=mysqli_query($conn,$query2);
      if($result2){
        echo "Successfully added the people";
        $IDPI=mysqli_insert_id($conn);;
    }else{
      echo "Failed to add the people";
    }
  }
  ?>
    <form method="POST">
    <p>Enter new vehicle
    <p>Vehicle type: <input type="text" name="VT"><br/>
    <p>Vehicle colour: <input type="text" name="VC"><br/>
    <p>Vehicle licence: <input type="text" name="NVL"><br/>
        <input type="submit" name="submit" value="submit"/>
    </form>
    <?php
    if(isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
    (isset($_POST["VT"]) && $_POST["VT"] !="") &&
    (isset($_POST["VC"]) && $_POST["VC"] !="") &&
    (isset($_POST["NVL"]) && $_POST["NVL"] !="") 
    ){
      $VT=$_POST["VT"];
      $VC=$_POST["VC"];
      $VL=$_POST["NVL"];
      $query4="INSERT INTO `Vehicle` (`Vehicle_type`,`Vehicle_colour`,`Vehicle_licence`)
             VALUES ('$VT','$VC','$VL')";
      $servername="mariadb";
      $username="root";
      $password="rootpwd";
      $dbname="coursework2";
      $conn = mysqli_connect($servername,$username,$password,$dbname);
      $result4=mysqli_query($conn,$query4);
      if($result4){
        echo "Successfully added the vehicle";
        $IDVI=mysqli_insert_id($conn);
      }else{
        echo "Failed to add the vehicle";
      }
    }
}

if(isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
(isset($_POST["NIR"]) && $_POST["NIR"] !="") &&
(isset($_POST["ID"]) && $_POST["ID"] !="") &&
(isset($_POST["LVI"]) && $_POST["LVI"] !="") &&
(isset($_POST["LPI"]) && $_POST["LPI"] !="") &&
(isset($_POST["offencetype"]) && $_POST["offencetype"] !="")
)
{
  $NIR= $_POST["NIR"];
  $ID= $_POST["ID"];
  $LVI=$_POST["LVI"];
  $LPI=$_POST["LPI"];
  $IDO=$_POST["offencetype"];
  //Search People ID to confirm whether it is already in system or not
  $query="SELECT * FROM `People` WHERE `People_licence`='$LPI'";
  //echo "Query: " . $query . "<br>";
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  $result=mysqli_query($conn,$query);
  // Store first result set
  //if people is already in the police system
  if ($result->num_rows > 0){
    $row = mysqli_fetch_array($result);
    $IDPI=$row['People_ID'];
    echo "People is already in police system with ID: $IDPI"."<br>";
  }else{
    //if people is not in the police system
    echo "People is not in police system, please enter more details about new people before file report"."<br>";
  }
  
  //Search Vehicle ID to confirm whether it is already in system or not
  $query3="SELECT `Vehicle_ID` FROM `Vehicle` WHERE `Vehicle_licence`='$LVI'";
  $result3=mysqli_query($conn,$query3);
  //if vehicle is already in police system
  if($result3->num_rows > 0){
    $row3 = mysqli_fetch_array($result3);
    $IDVI=$row3['Vehicle_ID'];
    echo "Vehicle is already in the police system with ID: $IDVI"."<br>";
  }else{
    echo "Vehicle is not in the police system, please enter some details about new vehicles";
  }
  //add all the details into incident table
  if(isset($IDVI) && isset($IDPI)){
    $query5="INSERT INTO `incident` (`Vehicle_ID`,`People_ID`,`Incident_Date`,`Incident_Report`,`Offence_ID`)
          VALUES ('$IDVI','$IDPI','$ID','$NIR','$IDO')";
    $result5=mysqli_query($conn,$query5);
    if($result5){
      echo "Successfully added incident report";
      $IDIR=mysqli_insert_id($conn);
      $userid=$_SESSION['id'];
      $tableName='incident';
      logAudit($tableName,'Insert',$userid,"added new incident report");
    } else{
      echo "Failed to add incident report";}
  }
}


// Q5: retrieve existing reports (e.g., via a search)
if(isset($_SESSION["user"]) && isset($_SESSION["id"]))
{
  ?>
  <form method="POST">
    <p>Retrieve existing reports
    <p>Incident report search: <input type="text" name="irs"><br/>
        <input type="submit" name="submit" value="search"/>
    <p>Replace old report with new report
    <p>new report: <input type="text" name="NR"><br/>
        <input type="submit" name="newreport" value="submit"/>
  </form>
  <?php
}

if(isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
      (isset($_POST["irs"]) && $_POST["irs"] !="") ) 
  {
    $RS=$_POST["irs"];
    $sql="SELECT * FROM `incident` WHERE `Incident_Report`='$RS'";
    $servername="mariadb";
    $username="root";
    $password="rootpwd";
    $dbname="coursework2";
    $conn = mysqli_connect($servername,$username,$password,$dbname);
    $result7=mysqli_query($conn,$sql);
    if($result7->num_rows > 0){
      $row7 = mysqli_fetch_array($result7);
      $_SESSION['IID']=$row7['Incident_ID'];
      $DI=$row7['Incident_Date'];
      $IR=$row7['Incident_Report'];
      echo "Successfully searched incident. "."<br>";
      echo "Incident ID = $_SESSION[IID]. Date = $DI". "<br>";
      $userid=$_SESSION['id'];
      $tableName='incident';
      logAudit($tableName,'search',$userid,"Retrive existing report");   
    }
  }
//Q5: Edit the report
if(isset($_POST["NR"]) && $_POST["NR"] !=""){
  $NR=$_POST["NR"];
  //$IID=$row7['Incident_ID'];
  $sql2="UPDATE `incident` SET `Incident_Report`= '$NR' WHERE `Incident_ID`='$_SESSION[IID]'";
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  $result8=mysqli_query($conn,$sql2);
  if($result8){
    echo"Successfully replaced old report with new report";
    $userid=$_SESSION['id'];
    $tableName='incident';
    logAudit($tableName,'Update',$userid,"Replace old report with new report");
  }
  else{
    echo "Failed to search incident report";
  }
}

// Q6: Two additions
//(1) Create new police officer accounts
if(isset($_POST["username"]) && isset($_POST["password"]) &&
   isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
   $_SESSION["user"] == 'daniels' && $_SESSION["id"] == '3')
{
  ?>
  <form method="POST">
    <p>Create new police officer account
    <p>New username: <input type="text" name="NU"><br/>
    <p>New password: <input type="text" name="NP"><br/>
      <input type="submit" name="enter" value="enter"/>
  </form>
  
  <form method="POST">
    <p>Associate fines to reports</p>
    <p>Fine amount: <input type="text" name="FA"><br/></p>
    <p>Fine points: <input type="text" name="FP"><br/></p>
    <p>Incident ID: <input type="text" name="iid"><br/></p>
    <input type="submit" name="enter" value="enter"/>
  </form>
  <?php
}

if(isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
  $_SESSION["user"] == 'daniels' && $_SESSION["id"] == '3' &&
  (isset($_POST["NU"]) && $_POST["NP"] !="") &&
  (isset($_POST["NP"]) && $_POST["NP"] !="")) 
{
  $Nuser=$_POST["NU"];
  $Npass=$_POST["NP"];
  $sql3="INSERT INTO `Users`(`username`,`password`) 
         VALUES ('$Nuser','$Npass')";
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  $result9=mysqli_query($conn,$sql3);
  if($result9){
    echo"Successfully added new username and password";
    $userid=$_SESSION['id'];
    $tableName='Users';
    logAudit($tableName,'Insert',$userid,"added new username and password");
  }
  else{
    echo "Failed to add new username and password";
  }
}
//Q6:(2) Associate fines to reports
if(isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
  $_SESSION["user"] == 'daniels' && $_SESSION["id"] == '3' &&
  (isset($_POST["FA"]) && $_POST["FA"] !="") &&
  (isset($_POST["FP"]) && $_POST["FP"] !="") &&
  (isset($_POST["iid"]) && $_POST["iid"] !="")) 
{
  $FA=$_POST["FA"];
  $FP=$_POST["FP"];
  $iid=$_POST["iid"];
  $sql4="INSERT INTO `fines`(`Fine_Amount`,`Fine_Points`,`Incident_ID`) 
         VALUES ('$FA','$FP','$iid')";
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  $result9=mysqli_query($conn,$sql4);
  if($result9){
    echo"Successfully associated fines with incident";
    $userid=$_SESSION['id'];
    $tableName='fines';
    logAudit($tableName,'Insert',$userid,"Associated fines with incident");
  }
  else{
    echo "Failed to associated fines with incident";
  }
}


// Q7: have access to an audit trail to account for database
// record accesses and changes that are made 
//(e.g., deletions, updates, etc.)
function logAudit($tableName,$action,$userId,$details){
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  $sql5="INSERT INTO audit (`table_name`,`action`,`userid`,`details`)
         VALUES(?,?,?,?)";
  // make reference from demo 14
  //ssis" 表示参数的类型。userId,tableName, recordId,action, $details 分别是要绑定到参数上的变量。
  $stmt=$conn->prepare($sql5);
  $stmt->bind_param("ssis",$tableName,$action,$userId,$details);
  $stmt->execute();
}
if(isset($_SESSION["user"]) && isset($_SESSION["id"]) &&
  ($_SESSION["user"] == 'daniels' && $_SESSION["id"] == '3' ))
{
  //audit trail on web interface
  $servername="mariadb";
  $username="root";
  $password="rootpwd";
  $dbname="coursework2";
  $conn = mysqli_connect($servername,$username,$password,$dbname);
  $sql6="SELECT * FROM `audit` ORDER BY `time` DESC";
  $result7=mysqli_query($conn,$sql6);
  if (mysqli_num_rows($result7) > 0)
  {
     echo "<table>";
     echo "<tr><th>Userid</th><th>Table name</th><th>Changes</th><th>Time</th><th>Details</th></tr>";
  //echo "<ul>";
  while($row7 = mysqli_fetch_assoc($result7))
  {
  //echo "<li>";
  // <tr> define a table row in an HTML table.
     echo "<tr>";
  //<td> (table data) or <th> (table header) tags to define the content of the cells within the row.
     echo "<td>".$row7['userid']."</td>";
     echo "<td>".$row7['table_name']."</td>";
     echo "<td>".$row7['action']."</td>";
     echo "<td>".$row7['time']."</td>";
     echo "<td>".$row7['details']."&nbsp&nbsp;";
     echo "</tr>";}
     echo "</table>";
   }
}

// Q1: Output loginerror & login page 
if (isset($_POST["username"]) && isset($_POST["password"]) &&
  !isset($_SESSION["user"]) && !isset($_SESSION["id"]))
{
    if ($loginerror)
        echo "<p>Invalid Username or Password</p>";
?>           
    <form method="POST">
      Username: 
      <input name="username" type="text" id="user" value="" size="15" maxlength="32" required/><br/>

      Password: 
      <input name="password" type="password" id="pass" value="" size="15" maxlength="32" required/><br/>
      <input type="Submit" name="login" value="Login"/>
    </form>

<?php
}
?>
</main>
<footer><a href="index.php">Back to main page</a></footer>
</body>
</html>