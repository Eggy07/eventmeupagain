<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include('includes/checklogin.php');
check_login();
if (strlen($_SESSION['odmsaid']==0)) 
{
  header('location:logout.php');
} else{
$pid=intval($_GET['id']);// product id
if(isset($_POST['submit']))
{
  $filename = $_FILES['note']['name'];

    // destination of the file on the server
  $destination = 'companyimages/' . $filename;

    // get the file extension
  $extension = pathinfo($filename, PATHINFO_EXTENSION);

    // the physical file on a temporary uploads directory on the server
  $file = $_FILES['note']['tmp_name'];
  $size = $_FILES['note']['size'];
  // move the uploaded (temporary) file to the specified destination
  if (move_uploaded_file($file, $destination)) {
        //$note=$_FILES["note"]["name"];
        //move_uploaded_file($_FILES["note"]["tmp_name"],"productimages/".$_FILES["note"]["name"]);
    $sql="update  tblcompany set companylogo=:filename ";
    $query = $dbh->prepare($sql);
    $query->bindParam(':filename',$filename,PDO::PARAM_STR);
    $query->execute();
    if ($query->execute()){
      echo '<script>alert("Company logo updated successfully")</script>';
    }else{
      echo '<script>alert("update failed! try again later")</script>';
    }
  }
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
                    <br/>
                    <form class="form-horizontal row-fluid" name="insertproduct" method="post" enctype="multipart/form-data">
                      <?php
                      $sql="SELECT * from  tblcompany";
                      $query = $dbh -> prepare($sql);
                      $query->execute();
                      $results=$query->fetchAll(PDO::FETCH_OBJ);
                      $cnt=1;
                      if($query->rowCount() > 0)
                      {
                        foreach($results as $row)
                        {  
                          ?>
                          <div class="control-group">
                            <label class="control-label" for="basicinput">Company Name</label>
                            <div  class="col-6">
                              <input type="text"   class="form-control" name="companyname"  readonly value="<?php  echo $row->companyname;?>" class="span6 tip" readonly>
                            </div>
                          </div>
                          <br>
                          <div class="control-group"> 
                        <label class="control-label" for="basicinput">Current logo</label>
                        <div class="controls">
                            <?php 
                                $logoPath = "assets/img/companyimages/" . $row->companylogo;

                                if (!empty($row->companylogo) && file_exists($logoPath)) { 
                            ?>
                                <img style="height: auto; width: 300px;" src="<?php echo $logoPath; ?>" alt="Company Logo">
                            <?php 
                                } else { 
                            ?>
                                <img src="assets/img/companyimages/logo1.jpg" alt="Default Logo" width="100" height="100">
                            <?php 
                                } 
                            ?> 
                        </div>
                    </div>

                          <div class="form-group col-md-6">
                            <label>New logo</label>
                            <input type="file" name="note" id="productimage1" class="file-upload-default">
                            <div class="input-group col-xs-12">
                              <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Logo">
                              <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                              </span>
                            </div>
                          </div>
                        <?php }} ?>
                        <br>
                        <div class="form-group row">
                          <div class="col-12">
                            <button type="submit" class="btn btn-primary "  name="submit">
                              <i class="fa fa-plus"></i> Update
                            </button>
                          </div>
                        </div>
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
    <?php }  ?>