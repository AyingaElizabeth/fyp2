<?php
require('top.inc.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>weFarm| Dashboard </title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

</head>
    <body>
    <div class="wrapper">
        <!-- Sidebar  -->
   
        <div class="content pb-0">
            <div class="animated fadeIn">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="card">
            <div class="card-body">
				   <h4 class="box-title">AGriculture tips </h4>
                   <h4 class="box-link"><a href="pest_add.php">Add view</a> </h4>
				</div> 
            
            <div class="card-body">
              <div id="tabs-4"><table class="table table-striped thead-dark table-bordered table-hover" id="mhishi">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Region</th>
                    <th>Added on</th>
                    <th>ACTION</th>
                    </tr>
                </thead>
                    <?php
                     $a=1;
                    $query=mysqli_query($con,"select *from agri_tips ");
                     while($row=mysqli_fetch_array($query))
                        {
                          
                          ?>
                          <tr>
                              <td><?php echo $a;?></td>
                            <td><?php echo $row['type'];?></td>    
                            <td><?php echo $row['description'];?></td> 
                            <td><?php echo $row['region'];?></td>   
                            <td><?php echo $row['date_t'];?></td>
                             <td>    
                  <a href="pest_view.php?edited=1&idx=<?php echo $row['id']; ?>"  class="btn btn-danger"><span class="fa fa-trash"></span></a>
                              </td>  
                          </tr>
                          <?php
                       
                            $a++;
                      }
                                          
                       if (isset($_GET['id']) && is_numeric($_GET['idx']))
                      {
                          $id = $_GET['idx'];
                          if ($stmt = $con->prepare("DELETE FROM agri_tips WHERE id = ? LIMIT 1"))
                          {
                              $stmt->bind_param("i",$id);
                              $stmt->execute();
                              $stmt->close();
                               ?>
                    <div class="alert alert-warning " >
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <strong> Successfully! </strong><?php echo'Record Successfully Deleted';?></div>
                   <script>
                       setTimeout(function () {
                        window.location.href = "pest_view.php";
                        }, 5000); 
                      
                    </script>
            
                    <?php
                          }
                          

                      }
                
                      ?>
                </table> </div>
            </div>
            </div>    
            </div>
           
            </div>
            
        
        </div>
    </div>
  
       

     
       
   
</body>
</html>
<?php
require('footer.inc.php');
?>


