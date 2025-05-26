<?php 
session_start();
if (!isset($_SESSION["AID"])) {
    echo "<script>window.open('alogin.php','_self')</script>";
}
include("config.php");
require_once 'php-sentiment-analyzer-master/src/Analyzer.php'; // Load Sentiment Analyzer

if (isset($_POST['export_csv'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=reviews.csv');

    $output = fopen('php://output', 'w');
    
    // CSV Column Headers
    fputcsv($output, ['S.no', 'User Name', 'Product', 'Image', 'Review', 'Logs']);

    // Fetch Data from Database
    $sql = "SELECT user_reg.UNAME, products.PNAME, products.LOC, rate_review.MSG, rate_review.LOG 
            FROM rate_review
            INNER JOIN user_reg ON rate_review.UID = user_reg.UID
            INNER JOIN products ON rate_review.PID = products.PID
            ORDER BY rate_review.UID DESC";
    
    $result = $con->query($sql);
    $sno = 1;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Add data to CSV
            fputcsv($output, [
                $sno++, 
                $row['UNAME'], 
                $row['PNAME'], 
                'products/' . $row['LOC'], // Include image path
                $row['MSG'], 
                $row['LOG']
            ]);
        }
    }
    fclose($output);
    exit();
}

use Sentiment\Analyzer;
$analyzer = new Analyzer();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require "head.php"; ?>
    <link rel="stylesheet" type="text/css" href="css/admin.css">
    <!-- First, add Chart.js CDN in the head section -->
    <script src="chart.js"></script>
</head>
<style>
	.small-chart {
    width: 100% !important; 
    height: 300px !important; /* Adjust as needed */
}

</style>
<body>
<?php include "topnav.php"; ?>

<div class="container" style="margin-top:30px">
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-header text-info">User Product Reviews</h2>
        </div>
        <div class="col-md-3">
            <?php include "admin_sidnav.php"; ?>
        </div>
        <div class="col-md-9">
            <div id='output'></div>

            <?php
            $sql = "SELECT user_reg.UID, user_reg.UNAME, rate_review.MSG, rate_review.PID,
                      rate_review.UID, rate_review.CRID, rate_review.LOG, products.LOC,
                      products.PNAME
                    FROM rate_review 
                    INNER JOIN user_reg ON rate_review.UID = user_reg.UID 
                    INNER JOIN products ON rate_review.PID = products.PID 
                    ORDER BY rate_review.UID DESC";
                    
            $result = $con->query($sql);

            // Store Sentiment Counts Per Product
            $productSentiments = [];

            if ($result->num_rows > 0) {
                echo "<table class='table table-hover table-bordered table-striped'>";
                echo '<thead class="text-primary">
                        <tr>
                            <th>S.no</th>
                            <th>User Name</th>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Review</th>
                            <th>Sentiment</th>
                            <th>Logs</th>
                            <th>Delete</th>
                        </tr>
                      </thead>';

                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $i++;

                    // Perform Sentiment Analysis
                    $sentiment = $analyzer->getSentiment($row["MSG"]);
                    $sentiment_label = "Neutral 😐";
                    
                    if ($sentiment['compound'] >= 0.05) {
                        $sentiment_label = "Positive 😊";
                        $productSentiments[$row["PNAME"]]["positive"] = ($productSentiments[$row["PNAME"]]["positive"] ?? 0) + 1;
                    } elseif ($sentiment['compound'] <= -0.05) {
                        $sentiment_label = "Negative 😞";
                        $productSentiments[$row["PNAME"]]["negative"] = ($productSentiments[$row["PNAME"]]["negative"] ?? 0) + 1;
                    } else {
                        $productSentiments[$row["PNAME"]]["neutral"] = ($productSentiments[$row["PNAME"]]["neutral"] ?? 0) + 1;
                    }

                    echo "<tr>";
                    echo "<td>$i</td>";
                    echo "<td>{$row["UNAME"]}</td>";
                    echo "<td>{$row["PNAME"]}</td>";
                    echo "<td><img src='products/{$row["LOC"]}' height='50px' width='50px'></td>";
                    echo "<td>{$row["MSG"]}</td>";
                    echo "<td><strong>$sentiment_label</strong></td>"; // Display Sentiment
                    echo "<td>{$row["LOG"]}</td>";
                    echo "<td><a href='#' class='btn btn-danger btn-xs delpro' data-id='{$row['CRID']}'>
                            <i class='fa fa-trash'></i> 
                          </a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='alert alert-danger'>No Reviews Found</div>";
            }
            ?>
        <form method="post">
    <button type="submit" name="export_csv" class="btn btn-success">
        <i class="fa fa-download"></i> Export Reviews (CSV)
    </button>
</form>
<div class="text-center mb-3">
    <div class="btn-group" role="group" aria-label="Chart Types">
        <button type="button" class="btn btn-primary change-all-charts" data-type="bar">
            <i class="fa fa-bar-chart"></i> Bar
        </button>
        <button type="button" class="btn btn-info change-all-charts" data-type="pie">
            <i class="fa fa-pie-chart"></i> Pie
        </button>
        <button type="button" class="btn btn-success change-all-charts" data-type="doughnut">
            <i class="fa fa-circle-o"></i> Doughnut
        </button>
        <button type="button" class="btn btn-warning change-all-charts" data-type="line">
            <i class="fa fa-line-chart"></i> Line
        </button>
    </div>
</div>

            <!-- Display Sentiment Count Per Product -->
            <h3>Sentiment Analysis for each Product</h3>
			<div class="row">
    <?php foreach ($productSentiments as $product => $sentiments) { ?>
        <div class="col-md-6 text-center">
            <h4><?php echo $product; ?> Sentiments</h4>
            <canvas id="chart_<?php echo md5($product); ?>" class="small-chart"></canvas>
        </div>
    <?php } ?>
</div>

        </div>


    </div>
</div>
<hr>
<?php require "footer.php"; ?>

<!-- Then replace all existing chart-related script tags with this single script block before </body> -->
<script>
// Initialize charts object to store all chart instances
const charts = {};

// Initialize all charts
<?php foreach ($productSentiments as $product => $sentiments) { 
    $positive = $sentiments['positive'] ?? 0;
    $negative = $sentiments['negative'] ?? 0;
    $neutral = $sentiments['neutral'] ?? 0;
?>
    const ctx_<?php echo md5($product); ?> = document.getElementById('chart_<?php echo md5($product); ?>');
    if (ctx_<?php echo md5($product); ?>) {
        charts['<?php echo md5($product); ?>'] = new Chart(ctx_<?php echo md5($product); ?>, {
            type: 'bar',
            data: {
                labels: ['Positive 😊', 'Negative 😞', 'Neutral 😐'],
                datasets: [{
                    label: '<?php echo addslashes($product); ?> Sentiments',
                    data: [<?php echo $positive; ?>, <?php echo $negative; ?>, <?php echo $neutral; ?>],
                    backgroundColor: ['#28a745', '#dc3545', '#6c757d'],
                    borderColor: ['#28a745', '#dc3545', '#6c757d'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
<?php } ?>

// Add event listener for chart type buttons
document.querySelectorAll('.change-all-charts').forEach(button => {
    button.addEventListener('click', function() {
        const newType = this.getAttribute('data-type');
        
        // Update all charts
        Object.keys(charts).forEach(chartId => {
            const chart = charts[chartId];
            if (chart) {
                const data = chart.data;
                const options = chart.options;
                
                // Destroy existing chart
                chart.destroy();
                
                // Create new chart with new type
                charts[chartId] = new Chart(
                    document.getElementById('chart_' + chartId),
                    {
                        type: newType,
                        data: data,
                        options: {
                            ...options,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    }
                );
            }
        });
    });
});
</script>

<script>
$(document).ready(function(){
    $(".delpro").click(function(e){
        e.preventDefault();
        var did = $(this).data("id");
        var row = $(this).closest("tr");

        if (confirm("Are you sure you want to delete this review?")) {
            $.ajax({
                type: 'POST',
                url: 'pro_del_review.php',
                data: { id: did },
                beforeSend: function(){
                    $("#output").html("Deleting...");
                },
                success: function(data){
                    $("#output").html("<div class='alert alert-success'>" + data + "</div>");
                    row.fadeOut(500, function(){ $(this).remove(); });
                },
                error: function(xhr, status, error){
                    $("#output").html("<div class='alert alert-danger'>Error: " + error + "</div>");
                }
            });
        }
    });
});
</script>

</body>
</html>
