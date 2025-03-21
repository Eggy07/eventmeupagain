<?php
include('includes/checklogin.php');
check_login();
if(isset($_POST['submit']))
{
  $adminid=$_SESSION['odmsaid'];
  $AName=$_POST['username'];
  $fName=$_POST['firstname'];
  $lName=$_POST['lastname'];
  $mobno=$_POST['mobilenumber'];
  $email=$_POST['email'];
  $sql="update tbladmin set UserName=:adminname,FirstName=:firstname,LastName=:lastname,MobileNumber=:mobilenumber,Email=:email where ID=:aid";
  $query = $dbh->prepare($sql);
  $query->bindParam(':adminname',$AName,PDO::PARAM_STR);
  $query->bindParam(':firstname',$fName,PDO::PARAM_STR);
  $query->bindParam(':lastname',$lName,PDO::PARAM_STR);
  $query->bindParam(':email',$email,PDO::PARAM_STR);
  $query->bindParam(':mobilenumber',$mobno,PDO::PARAM_STR);
  $query->bindParam(':aid',$adminid,PDO::PARAM_STR);
  $query->execute();
  echo '<script>alert("Profile has been updated")</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>

    <div class="container-scroller">
        
        <?php @include("includes/header.php");?>
        
        <div class="container-fluid page-body-wrapper">
            
            
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    $adminid=$_SESSION['odmsaid'];
                                    $sql="SELECT * from  tbladmin where ID=:aid";
                                    $query = $dbh -> prepare($sql);
                                    $query->bindParam(':aid',$adminid,PDO::PARAM_STR);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt=1;
                                    if($query->rowCount() > 0)
                                    {
                                        foreach($results as $row)
                                        {  
                                            ?>
                                            <form method="post">
                                                <div class="form-group row">
                                                    <label class="col-12" for="register1-username">Permision:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="adminname" value="<?php  echo $row->AdminName;?>" readonly="true">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="register1-email">User Name:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="username" value="<?php  echo $row->UserName;?>" required='true' >
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="register1-email">First Name:
                                                    </label>
                                                    <div class="col-12">
                                                     <input type="text" class="form-control" name="firstname" value="<?php  echo $row->FirstName;?>" required='true' >
                                                 </div>
                                             </div>
                                             <div class="form-group row">
                                                <label class="col-12" for="register1-email">Last Name:</label>
                                                <div class="col-12">
                                                   <input type="text" class="form-control" name="lastname" value="<?php  echo $row->LastName;?>" required='true' >
                                               </div>
                                           </div>
                                           <div class="form-group row">
                                            <label class="col-12" for="register1-password">Email:</label>
                                            <div class="col-12">
                                              <input type="email" class="form-control" name="email" value="<?php  echo $row->Email;?>" required='true'>
                                          </div>
                                      </div>
                                      <div class="form-group row">
                                        <label class="col-12" for="register1-password">Contact Number:</label>
                                        <div class="col-12">
                                         <input type="text" class="form-control" name="mobilenumber" value="<?php  echo $row->MobileNumber;?>" required='true' maxlength='10'>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                  <label class="col-12" for="register1-password">Registration Date:</label>
                                  <div class="col-12">
                                   <input type="text" class="form-control" id="email2" name="" value="<?php  echo $row->AdminRegdate;?>" readonly="true">
                               </div>
                           </div>
                           <div class="control-group">
                            <label class="control-label" for="basicinput">Profile Image</label>
                            <div class="controls">
                                <?php 
                                    $profilePath = "assets/img/profileimages/" . $row->Photo;
                                    $defaultImage = "assets/img/avatars/pro4.jpg";

                                    if (!empty($row->Photo) && file_exists($profilePath)) { 
                                ?>
                                    <img src="<?php echo $profilePath; ?>" width="150" height="150" alt="Profile Image">
                                <?php 
                                    } else { 
                                ?>
                                    <img src="<?php echo $defaultImage; ?>" width="100" height="100" alt="Default Profile">
                                <?php 
                                    } 
                                ?>
                                <br>
                                <a href="update_image.php?id=<?php echo htmlspecialchars($adminid, ENT_QUOTES, 'UTF-8'); ?>">Change Image</a>
                            </div>
                        </div>

                  </div>       
                  <?php 
              }
          } ?>
          <br>
          <button type="submit" name="submit" class="btn btn-primary btn-fw mr-2" style="float: left;">update</button>
      </form>
  </div>
</div>
</div>
</div>
</div>


<?php @include("includes/footer.php");?>

</div>

</div>

</div>

<?php @include("includes/foot.php");?>

</body>

</html>