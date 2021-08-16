<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php
// define variables and set to empty values
$nameErr = $emailErr = "";
$name = $email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
   $guests = array();
  
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
    } else {
       
      $guests['name'] = $name;
  
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    } else {
      $guests['email'] = $email;
    }
  }
  
  if(empty($guests) == false){
   $host = 'mongo-host';

   $connection = new MongoClient( "mongodb://".$host.":27017" );

   $db = $connection->selectDB('hotel');

   $collection = $db->selectCollection('guestbook');
    
   $collection->insert($guests);
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>PHP Redis Guestbook with name and email. </h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Name: <input type="text" name="name" value="">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email" value="">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

  $host = 'mongo-host';

  $connection = new MongoClient( "mongodb://".$host.":27017" );

  $db = $connection->selectDB('hotel');

  $collection = $db->selectCollection('guestbook');

   echo "<h2>Your Input:</h2>";
    
    $iparr = $collection->find();
    
    if(empty($iparr) == false){
    foreach ($iparr as $val) {
     echo 'Guest name: '.$val['name'];
     echo "<br>";
     echo 'Guest email: '.$val['email'];
     echo "<br>";
    }
    }
  
   
#echo "<h2>Your Input:</h2>";
#echo $name;
#echo "<br>";
#echo $email;
#echo "<br>";

?>

</body>
</html>
