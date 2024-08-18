<html>
   <head>
      <title>A pretty phone list</title>

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
      <h1>Phone List</h1>
      <form method="POST">
         Name: <input type="text" name="name"><br/>
         Phone: <input type="text" name="phone"><br/>
         Address: <input type="text" name="address"><br>
          <input type="submit" value="Add Record">
      </form>
      <hr>

      <script>
         function confirmDelete(ID)
         {
            var a =confirm("Are you sure?");
            if (a == true)
            {
               defParam="?del="+encodeURIComponent(ID);
               this.document.location.href=defParam;
            }
         }
      </script>

     <?php 
     // MySQL database information
     $servername = "mariadb";
     $username = "root";
     $password = "rootpwd";
     $dbname = "exercise8";

     $conn = mysqli_connect($servername, $username, $password, $dbname);
     // other code here!
     if(mysqli_connect_errno())
      { 
        echo "Failed to connect to MySQL:".mysqli_connect_error(); 
        die(); 
      } 
      //else 
      //   echo "MySQL connection OK<br/><br/>";


      if (isset($_POST['name']) && $_POST['name']!="" &&
         isset($_POST['phone']) && $_POST['phone']!="" &&
         isset($_POST['address']) && $_POST['address']!="")
      {
         $name=mysqli_real_escape_string($conn, $_POST['name']);
         $phone=mysqli_real_escape_string($conn,$_POST['phone']);
         $address=mysqli_real_escape_string($conn,$_POST['address']);
         $sql = "INSERT INTO People(Name, PhoneNumber,Address) VALUES ('$name','$phone','$address');"; 
         echo "sql=".$sql."<br/>";
         $result = mysqli_query($conn, $sql);
      }

   
      if (isset($_GET['del'])!="" && $_GET['del']!="")
      {
         $sql = "DELETE FROM People WHERE ID=".$_GET['del'].";";
         $result = mysqli_query($conn, $sql);
      }


      $sql = "SELECT * FROM People ORDER BY Name;";
      $result = mysqli_query($conn, $sql);
      echo mysqli_num_rows($result)."rows<br/>";


      if (mysqli_num_rows($result) > 0)
      {
         echo "<table>";
         echo "<tr><th>Name</th><th>Phone</th><th>Address</th></tr>";
      //echo "<ul>";
      while($row = mysqli_fetch_assoc($result))
      {
      //echo "<li>";
      // <tr> define a table row in an HTML table.
         echo "<tr>";
      //<td> (table data) or <th> (table header) tags to define the content of the cells within the row.
         echo "<td>".$row["Name"]."</td>";
         echo "<td>".$row["PhoneNumber"]."</td>";
         echo "<td>".$row["Address"]."&nbsp&nbsp;";
         echo "<button onclick=confirmDelete(".$row["ID"].")>Delete</button></td>";
         echo "</tr>";}
         echo "</table>";
       }
      else
      {
         echo "Database is empty";
      }
      mysqli_close($conn); 
      ?>
   </body>
</html>