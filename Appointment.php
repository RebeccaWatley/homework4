<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apppointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      
     <?php
$servername = "localhost";
$username = "rebeccca_ruser";
$password = "WL]8Dmr[Qag6";
$dbname = "rebeccca_MIS";
    
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  switch ($_POST['saveType']) {
    case 'Add':
      $sqlAdd = "insert into Appointment (ApptDay) value (?)";
      $stmtAdd = $conn->prepare($sqlAdd);
      $stmtAdd->bind_param("s", $_POST['iName']);
      $stmtAdd->execute();
      echo '<div class="alert alert-success" role="alert">New appointment added.</div>';
      break;
    case 'Edit':
      $sqlEdit = "update Appointment set ApptDay=? where ApptID=?";
      $stmtEdit = $conn->prepare($sqlEdit);
      $stmtEdit->bind_param("si", $_POST['iName'], $_POST['iid']);
      $stmtEdit->execute();
      echo '<div class="alert alert-success" role="alert">Appointment edited.</div>';
      break;
    case 'Delete':
      $sqlDelete = "delete from Appointment where ApptID=?";
      $stmtDelete = $conn->prepare($sqlDelete);
      $stmtDelete->bind_param("i", $_POST['iid']);
      $stmtDelete->execute();
      echo '<div class="alert alert-success" role="alert">Appointment deleted.</div>';
      break;
  }
}      
?>          
    <h1>Appointments</h1>
    <h4> The following information is whose patient ID number corresponds to the appointment day and which doctors ID number they are seeing</h4>
<table class="table table-striped">
  <thead>
    <tr>
      <th>ApptID</th>
      <th>ApptDay</th>
      <th>PatientID</th>
      <th>DoctorID</th>
    </tr>
  </thead>
  <tbody>

<?php
$sql = "select * from Appointment";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
?>
    
  <tr>
    <td><?=$row["ApptID"]?></td>
    <td><?=$row["ApptDay"]?></td>
    <td><?=$row["DoctorName"]?></td>
    <td><?=$row["PatientID"]?></td>
    
 <td>
   
    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editAppointment<?=$row["ApptID"]?>">
                Edit
              </button>
              <div class="modal fade" id="editAppointment<?=$row["ApptID"]?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editAppointment<?=$row["ApptID"]?>Label" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="editAppointment<?=$row["ApptID"]?>Label">Edit Appointment</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form method="post" action="">
                        <div class="mb-3">
                          <label for="editAppointment<?=$row["ApptID"]?>Name" class="form-label">Name</label>
                          <input type="text" class="form-control" id="editAppointment<?=$row["ApptID"]?>Name" aria-describedby="editAppointment<?=$row["ApptID"]?>Help" name="iName" value="<?=$row['ApptDay']?>">
                          <div id="editAppointment<?=$row["ApptID"]?>Help" class="form-text">Enter the appointment's day.</div>
                        </div>
                        <div class="mb-3">
                          <label for="DoctorIDList" class="form-label">Doctor ID</label>
                          <select class="form-select" aria-label="SelectDrID" id="DoctorIDList" name="id">
                        </div>
       <?php
    $ApptSql = "select * from Doctor order by DoctorID";
    $ApptResult = $conn->query($ApptSql);
    while($ApptRow = $ApptResult->fetch_assoc()) {
      if ($ApptRow['DoctorID'] == $row['DoctorID']) {
        $selText = " selected";
      } else {
        $selText = "";
      }
?>
  <option value="<?=$ApptRow['DoctorID']?>"<?=$selText?>><?=$ApptRow['DoctorID']?></option>                         
<?php
    }
?>
                        <input type="hidden" name="did" value="<?=$row['DoctorID']?>">
                        <input type="hidden" name="saveType" value="Edit">
                        <input type="submit" class="btn btn-primary" value="Submit">
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </td>
            <td>
              <form method="post" action="">
                <input type="hidden" name="iid" value="<?=$row["ApptID"]?>" />
                <input type="hidden" name="saveType" value="Delete">
                <input type="submit" class="btn" onclick="return confirm('Are you sure?')" value="Delete">
              </form>
            </td> 
  </td>        
  </tr>
           
<?php
  }
} else {
  echo "0 results";
}
$conn->close();
?>
  </tbody>
    </table>
  <br />
      <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAppointment">
        Add New
      </button>

      <!-- Modal -->
      <div class="modal fade" id="addAppointment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addAppointmentLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="addAppointmentLabel">Add Appointment</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="post" action="">
                <div class="mb-3">
                  <label for="ApptDay" class="form-label">Name</label>
                  <input type="text" class="form-control" id="ApptDay" aria-describedby="nameHelp" name="iName">
                  <div id="nameHelp" class="form-text">Enter the appointment's day.</div>
                </div>
                <input type="hidden" name="saveType" value="Add">
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  </body>
</html>
