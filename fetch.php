<?php

//fetch.php

include('db/database_connection.php');

if(isset($_POST["timestamp"]))
{
 $query = "SELECT PV_array_current from status WHERE timestamp < NOW()";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();

 foreach($result as $row)
 {
  $output[] = array(
   'timestamp'   => $row["timestamp"],
   'PV_array_current'  => floatval($row["voltage"])
  );
 }
 echo json_encode($output);
}

?>
