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
$totalOrders = 0;
while ($row = mysqli_fetch_assoc($res)) {
    $orderDataPoints[] = array("label" => $row['status'], "y" => $row['count']);
    $totalOrders += $row['count'];
}

// Calculate percentages
foreach ($orderDataPoints as &$point) {
    $point['percentage'] = round(($point['y'] / $totalOrders) * 100, 1);
}

// Fetch vendor data
$vendorRes = mysqli_query($con, "SELECT COUNT(*) AS vendor_count FROM admin_users WHERE role = 1");
$vendorCount = mysqli_fetch_assoc($vendorRes)['vendor_count'];

// Fetch user data
$userRes = mysqli_query($con, "SELECT COUNT(*) AS user_count FROM users");
$userCount = mysqli_fetch_assoc($userRes)['user_count'];

// Fetch user registration trend (last 6 months)
$userTrendRes = mysqli_query($con, "SELECT DATE_FORMAT(added_on, '%Y-%m') AS month, COUNT(*) AS count 
                                    FROM users 
                                    WHERE added_on >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                                    GROUP BY DATE_FORMAT(added_on, '%Y-%m')
                                    ORDER BY month ASC");

$userTrendDataPoints = array();
while ($row = mysqli_fetch_assoc($userTrendRes)) {
    $userTrendDataPoints[] = array("label" => $row['month'], "y" => $row['count']);
}

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
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="box-title">User Registration Trend (Last 6 Months)</h4>
                        <div id="userTrendChartContainer" style="height: 370px; width: 100%;"></div>
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
        legend: {
            cursor: "pointer",
            itemclick: explodePie
        },
        data: [{
            type: "pie",
            showInLegend: true,
            toolTipContent: "{label}: <strong>{y}</strong>",
            indexLabel: "{y}",
            legendText: "{label} - {percentage}%",
            indexLabelFontSize: 16,
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
            type: "column",
            yValueFormatString: "#,##0",
            indexLabel: "{y}",
            indexLabelPlacement: "inside",
            indexLabelFontWeight: "bolder",
            indexLabelFontColor: "white",
            dataPoints: <?php echo json_encode($userVendorDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    userVendorChart.render();

    var userTrendChart = new CanvasJS.Chart("userTrendChartContainer", {
        animationEnabled: true,
        exportEnabled: true,
        title: {
            text: "User Registration Trend (Last 6 Months)"
        },
        axisY: {
            title: "Number of New Users"
        },
        axisX: {
            title: "Month"
        },
        data: [{
            type: "line",
            yValueFormatString: "#,##0",
            dataPoints: <?php echo json_encode($userTrendDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    userTrendChart.render();
}

function explodePie (e) {
    if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
        e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
    } else {
        e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
    }
    e.chart.render();
}
</script>

<?php
require('footer.inc.php');
?>