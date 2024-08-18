<html>
<head>
		<title>COMP4039 - PHP demonstrations</title>
    <!--The rel attribute specifies the relationship between the current
     document and the linked resource, in this case, it is a stylesheet-->
		<link rel="stylesheet" href="../css/mvp.css">
    <!--The href attribute specifies the URL of the page the link goes to.-->
	</head>
<body>
<main>
  <h1>PHP Demo 14 - A search form</h1>
    Using the object-oriented syntax of mysqli
  <h2><u>People database</u></h2>
  
  <form  method="post">
        Name: <input type="text" name="name"><br/>
      <input type="submit" value="Search">
  </form>

  <?php  
       error_reporting(E_ALL);
       ini_set('display_errors',1);
       
       require("db.inc.php");
                  
       if (isset($_POST['name'])) 
       {
            $mysqli = new mysqli($servername, $username, $password, $dbname);
            //This indicates that any characters can appear before and after 
            //the value of $_POST['name'] in a SQL query, effectively allowing 
            //for partial matching.
            $name = "%{$_POST['name']}%";
            //This line prepares an SQL statement with a placeholder ? for the
            // value that will be provided later.
            $stmt = $mysqli->prepare("SELECT * FROM People WHERE Name LIKE ?");
            //This line binds the parameter $name to the prepared statement. 
            //The "s" indicates that the parameter is a string. This helps prevent 
            //SQL injection by separating the SQL code from the user input.
            $stmt->bind_param("s", $name);
            //This line executes the prepared statement with the bound parameter.
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo $result->num_rows ." rows<br/><br/>";
                while($row = mysqli_fetch_assoc($result)) {
                    echo $row["Name"]." - ".$row["PhoneNumber"]." - ". $row["Address"] . "<br/>"; 
              }

           }
           else {
             echo "Nothing found!";
           }
           $stmt->close();                                              
       }
  ?>  
</main>
<footer><a href="index.php">Back to main page</a></footer>
</body>
</html>