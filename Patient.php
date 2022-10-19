<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patients</title>
    <link 
     href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  </head>
  <body>
    <div class= "container">
      
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
      $sqlAdd = "insert into Patient (FirstName) value (?)";
      $stmtAdd = $conn->prepare($sqlAdd);
      $stmtAdd->bind_param("s", $_POST['pName']);
      $stmtAdd->execute();
      echo '<div class="alert alert-success" role="alert">New patient added.</div>';
      break;
    case 'Edit':
      $sqlEdit = "update Patient set FirstName=? where PatientID=?";
      $stmtEdit = $conn->prepare($sqlEdit);
      $stmtEdit->bind_param("si", $_POST['pName'], $_POST['pid']);
      $stmtEdit->execute();
      echo '<div class="alert alert-success" role="alert">Patient edited.</div>';
      break;
    case 'Delete':
      $sqlDelete = "delete from Patient where PatientID=?";
      $stmtDelete = $conn->prepare($sqlDelete);
      $stmtDelete->bind_param("i", $_POST['pid']);
      $stmtDelete->execute();
      echo '<div class="alert alert-success" role="alert">Patient deleted.</div>';
      break;
  }
}
?>
    <h1>Patients</h1>
<table class="table table-striped">
  <thead>
    <tr>
      <th>PatientID</th>
      <th>FirstName</th>
      <th>LastName</th>
      <th>Gender</th>
    </tr>
  </thead>
  <tbody>
    
<?php   
$sql = "SELECT PatientID, FirstName, LastName, Gender from Patient";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
?>
    
  <tr>
    <td><?=$row["PatientID"]?></td>
    <td><a href="patient-doctor.php?id=<?=$row["PatientID"]?>"><?=$row["FirstName"]?></a></td>
    <td><?=$row["LastName"]?></td>
    <td><?=$row["Gender"]?></td>
    
    <td>
    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editPatient<?=$row["PatientID"]?>">
                Edit
              </button>
              <div class="modal fade" id="editPatient<?=$row["PatientID"]?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editPatient<?=$row["PatientID"]?>Label" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="editPatient<?=$row["PatientID"]?>Label">Edit Patient</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form method="post" action="">
                        <div class="mb-3">
                          <label for="editPatient<?=$row["PatientID"]?>Name" class="form-label">Name</label>
                          <input type="text" class="form-control" id="editPatient<?=$row["PatientID"]?>Name" aria-describedby="editPatient<?=$row["PatientID"]?>Help" name="pName" value="<?=$row['FirstName']?>">
                          <div id="editPatient<?=$row["PatientID"]?>Help" class="form-text">Enter the Patient's name.</div>
                        </div>
                        <input type="hidden" name="pid" value="<?=$row['PatientID']?>">
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
                <input type="hidden" name="pid" value="<?=$row["PatientID"]?>" />
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
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatient">
        Add New
      </button>

      <!-- Modal -->
      <div class="modal fade" id="addPatient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addPatientLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="addPatientLabel">Add Patient</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="post" action="">
                <div class="mb-3">
                  <label for="FirstName" class="form-label">Name</label>
                  <input type="text" class="form-control" id="FirstName" aria-describedby="nameHelp" name="iName">
                  <div id="nameHelp" class="form-text">Enter the patient's name.</div>
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
