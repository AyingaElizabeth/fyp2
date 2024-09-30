<?php
require('top.inc.php');
isAdmin();

// Step 1: Fix existing data
function fixDatabaseIds($con) {
    // Create a temporary column
    $sql = "ALTER TABLE agri_tips ADD COLUMN temp_id INT AUTO_INCREMENT PRIMARY KEY";
    mysqli_query($con, $sql);

    // Copy the temporary IDs to the original id column
    $sql = "UPDATE agri_tips SET id = temp_id";
    mysqli_query($con, $sql);

    // Drop the temporary column
    $sql = "ALTER TABLE agri_tips DROP COLUMN temp_id";
    mysqli_query($con, $sql);

    // Ensure the id column is set as AUTO_INCREMENT
    $sql = "ALTER TABLE agri_tips MODIFY id INT AUTO_INCREMENT PRIMARY KEY";
    mysqli_query($con, $sql);

    echo "Database IDs have been fixed.";
}

// Uncomment the following line to run the fix (run only once!)
// fixDatabaseIds($con);

// Delete functionality
if(isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id'])){
    $id = intval($_GET['id']);
    $delete_sql = "DELETE FROM agri_tips WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $delete_sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if(mysqli_stmt_execute($stmt)){
            header("Location: " . $_SERVER['PHP_SELF'] . "?delete_success=1");
            exit();
        } else {
            echo "Error deleting record: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch all notes
$sql = "SELECT * FROM agri_tips ORDER BY id DESC";
$res = mysqli_query($con, $sql);

if(!$res){
    die("Error fetching records: " . mysqli_error($con));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agriculture Notes</title>
</head>
<body>
<div class="wrapper">
    <div class="content pb-0">
        <?php
        if(isset($_GET['delete_success'])) {
            echo "<div class='alert alert-success'>Note deleted successfully.</div>";
        }
        ?>
        <div class="orders">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="box-title">Agriculture Notes</h4>
                            <h4 class="box-link"><a href="pest_add.php">Add note</a></h4>
                        </div>
                        <div class="card-body--">
                            <div class="table-stats order-table ov-h">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="serial">#</th>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>About</th>
                                            <th>Added on</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $i = 1;
                                        while($row = mysqli_fetch_assoc($res)) {
                                        ?>
                                        <tr>
                                            <td class="serial"><?php echo $i; ?></td>
                                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                                            <td><?php echo htmlspecialchars($row['about']); ?></td>
                                            <td><?php echo htmlspecialchars($row['date_t']); ?></td>
                                            <td>
                                                <a href='pest_add.php?id=<?php echo $row['id']; ?>' class='badge badge-edit'>Edit</a>
                                                <a href='?type=delete&id=<?php echo $row['id']; ?>' class='badge badge-delete' onclick="return confirm('Are you sure you want to delete this note?');">Delete</a>
                                            </td>
                                        </tr>
                                        <?php 
                                        $i++;
                                        } 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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