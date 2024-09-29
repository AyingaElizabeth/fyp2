<?php
require_once('init.php');
require_once('top.inc.php');

// Fetch order status data from the database
$res = mysqli_query($con, "SELECT order_status.name AS status, COUNT(*) AS count 
                           FROM `order` 
                           JOIN order_status ON order_status.id = `order`.order_status 
                           GROUP BY `order`.order_status 
                           ORDER BY count DESC");

$orderDataPoints = array();
while ($row = mysqli_fetch_assoc($res)) {
    $orderDataPoints[] = array("label" => $row['status'], "y" => $row['count']);
}

// Fetch vendor data
$vendorRes = mysqli_query($con, "SELECT COUNT(*) AS vendor_count FROM admin_users WHERE role=1");
$vendorCount = mysqli_fetch_assoc($vendorRes)['vendor_count'];

// Fetch user data
$userRes = mysqli_query($con, "SELECT COUNT(*) AS user_count FROM users");
$userCount = mysqli_fetch_assoc($userRes)['user_count'];

$userVendorDataPoints = array(
    array("label" => "Users", "y" => $userCount),
    array("label" => "Vendors", "y" => $vendorCount)
);
?>

<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="box-title">Order Status Dashboard</h4>
                        <div id="orderChartContainer" style="height: 370px; width: 100%;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="box-title">Users and Vendors</h4>
                        <div id="userVendorChartContainer" style="height: 370px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script>
window.onload = function () {
    var orderChart = new CanvasJS.Chart("orderChartContainer", {
        animationEnabled: true,
        exportEnabled: true,
        title: {
            text: "Order Status Distribution"
        },
        subtitles: [{
            text: "Based on current database records"
        }],
        data: [{
            type: "pie",
            showInLegend: true,
            legendText: "{label}",
            indexLabelFontSize: 16,
            indexLabel: "{label} - #percent%",
            yValueFormatString: "#,##0",
            dataPoints: <?php echo json_encode($orderDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    orderChart.render();

    var userVendorChart = new CanvasJS.Chart("userVendorChartContainer", {
        animationEnabled: true,
        exportEnabled: true,
        title: {
            text: "Users and Vendors Distribution"
        },
        axisY: {
            title: "Number of Users/Vendors"
        },
        data: [{
            type: "bar",
            yValueFormatString: "#,##0",
            indexLabel: "{y}",
            indexLabelPlacement: "inside",
            indexLabelFontWeight: "bolder",
            indexLabelFontColor: "white",
            dataPoints: <?php echo json_encode($userVendorDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    userVendorChart.render();
}
</script>

<?php
require('footer.inc.php');
?>