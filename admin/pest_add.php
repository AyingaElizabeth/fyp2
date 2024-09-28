<?php 
     

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>WeFarm | Dashboard </title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

</head>
    <body>
        
      <div class="cardbox">
    <div class="wrapper">
        <!-- Sidebar  -->
        <?php  include_once ('top.inc.php'); ?>
            <div class="line"></div>
            <?php
                            if(isset($db,$_POST['submit'])){
                            $description = mysqli_real_escape_string($db,$_POST['description']);
                            $region = mysqli_real_escape_string($db,$_POST['region']);
                            $type = mysqli_real_escape_string($db,$_POST['type']);         
                            $date_t = date('Y-m-d');
                                 
                  
                $sql = "INSERT INTO agri_tips(description,region,type,date_t)VALUES('$description','$region','$type','$date_t')";
                $results = mysqli_query($db,$sql);
                        
                        
                        
                        if($results==1){
                              ?>
                        <div class="alert alert-success  animated bounce" id="sams1">
                        <?php echo'Thank you for adding Agriculatral Tips ';?></div>
                        <?php
                            
                             $query=mysqli_query($db,"select *from `farmers` where region = '$region' ");
                             while($row=mysqli_fetch_array($query)){
                                $phone = $row['phone'];
                            error_reporting(0);
                            $username = 'samstrover';
                            $token = 'e14916d71b35f236e25a3fe9fc8c4449';
                            $bulksms_ws = 'http://portal.bulksmsweb.com/index.php?app=ws';
                            $destinations = $phone;
                            $message = "Today's tip: $type, $description";

                            $ws_str = $bulksms_ws . '&u=' . $username . '&h=' . $token . '&op=pv';
                            $ws_str .= '&to=' . urlencode($destinations) . '&msg='.urlencode($message);
                            $ws_response = @file_get_contents($ws_str); 

                                 
                             }
                           
                          }else{
                                ?>
                         <div class="alert alert-danger samuel animated shake" id="sams1">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong> Error! </strong><?php echo'OPPS something went wrong';?></div>
                        <?php    
                          }      
                      }
                

                
                ?>    
          
            <div class="content pb-0">
            <div class="animated fadeIn">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="card">
                        <div class="card-header"><strong>Add Agriculture tip</strong><small> Form</small></div>  
            <div class="card-body">
              <form action="pest_add.php" method="post">
             <div class="row">
             <div class="col-md-12 form-group">
                 <label>Description</label>
                 <input type="text" name="description" class="form-control" maxlength="200" placeholder="Agricultural Fungicides Broad-spectrum fungicides" required>
             </div>  
               
                 
             </div>  
         <div class="row">
             <div class="col-md-6 form-group">
                 <label>Type</label>
                 <select class="form-control" name="type">
                 <option>Agricultural Herbicides</option>
                 <option>Pest Control Insecticide</option>
                 <option>Agricultural Insecticides</option> 
                 <option>Plant Growth Regulators</option>
                 <option>Acaricide Products For Ticks</option>
                    
                 </select>
             </div> 
              
                 <div class="col-md-6 form-group">
                 <label>Region</label>
                 <select class="form-control" name="region">
                 <option>Nkoowe</option>
                 <option>Sentema</option>
                 <option>Kakiri</option>   
                 </select>
             </div> 
             
             
                 
             </div>  
                <div class="row">
                <div class="col-md-6 form-group">
                <button type="submit" name="submit" class="btn btn-success btn-block"><span class="fa fa-check"></span> Add</button>  
                </div> 
                 <div class="col-md-6 form-group">
                <button type="reset" class="btn btn-danger btn-block"><span class="fa fa-times"></span> Cancel</button>  
                </div>    
                </div>
                
             </form>
            </div>
            </div>    
            </div>
           
            </div>
             
        
        </div>
        </div>
    </div>
  
       
	
    <script>
  $( function() {
   $("#ssm").datepicker({
    minDate: 0,
    maxDate:1,
});
      
  } );
  </script>
   
</body>
</html>
<?php
require('footer.inc.php');
?>