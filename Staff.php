<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
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
      $stmtAdd->bind_param("s", $_POST['sName']);
      $stmtAdd->execute();
      echo '<div class="alert alert-success" role="alert">New patient added.</div>';
      break;
    case 'Edit':
      $sqlEdit = "update Staff set StaffName=? where AdminID=?";
      $stmtEdit = $conn->prepare($sqlEdit);
      $stmtEdit->bind_param("si", $_POST['sName'], $_POST['sid']);
      $stmtEdit->execute();
      echo '<div class="alert alert-success" role="alert">Staff edited.</div>';
      break;
    case 'Delete':
      $sqlDelete = "delete from Staff where AdminID=?";
      $stmtDelete = $conn->prepare($sqlDelete);
      $stmtDelete->bind_param("i", $_POST['sid']);
      $stmtDelete->execute();
      echo '<div class="alert alert-success" role="alert">Staff deleted.</div>';
      break;
  }
}
?>       
    <h1>Staff</h1>
<table class="table table-striped">
  <thead>
    <tr>
      <th>AdminID</th>
      <th>StaffName</th>
      <th>Postion</th>
      <th>Gender</th>
    </tr>
  </thead>
  <tbody>
    
<?php
$sql = "SELECT * from Staff";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
?>
  <tr>
    <td><?=$row["AdminID"]?></td>
    <td><?=$row["StaffName"]?></td>
    <td><?=$row["Position"]?></td>
    <td><?=$row["Gender"]?></td>
    
   <td>
    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editStaff<?=$row["AdminID"]?>">
                Edit
              </button>
              <div class="modal fade" id="editStaff<?=$row["AdminID"]?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editStaff<?=$row["AdminID"]?>Label" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="editStaff<?=$row["AdminID"]?>Label">Edit Staff</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form method="post" action="">
                        <div class="mb-3">
                          <label for="editStaff<?=$row["AdminID"]?>Name" class="form-label">Name</label>
                          <input type="text" class="form-control" id="editStaff<?=$row["AdminID"]?>Name" aria-describedby="editStaff<?=$row["AdminID"]?>Help" name="sName" value="<?=$row['StaffName']?>">
                          <div id="editStaff<?=$row["AdminID"]?>Help" class="form-text">Enter the Staff's name.</div>
                        </div>
                        <input type="hidden" name="sid" value="<?=$row['AdminID']?>">
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
                <input type="hidden" name="sid" value="<?=$row["AdminID"]?>" />
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
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaff">
        Add New
      </button>

      <!-- Modal -->
      <div class="modal fade" id="addStaff" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addStaffLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="addStaffLabel">Add Staff</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="post" action="">
                <div class="mb-3">
                  <label for="StaffName" class="form-label">Name</label>
                  <input type="text" class="form-control" id="StaffName" aria-describedby="nameHelp" name="iName">
                  <div id="nameHelp" class="form-text">Enter the staff's name.</div>
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
