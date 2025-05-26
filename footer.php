<?php 
	$quote=array();
	$quote[]="<span class='fa-lg fa fa-recycle'></span> Keep The City Green And Clean";
	$quote[]="<span class='fa-lg fa fa-tint'></span> Dont Waste Food And Water";
	$quote[]="<span class='fa-lg fa fa-globe'></span> Plant Tree And Save Earth";
?>	

<footer>
  <div class="container-fluid">
  <div class="row">

     
      <div class="col-md-2 col-sm-4 paddingtop-bottom">
        <h6 class="heading7">User Review</h6>
        <div class="post">
        <?php 
          require_once 'php-sentiment-analyzer-master/src/Analyzer.php'; // Update path as needed
          use Sentiment\Analyzer;

          $analyzer = new Analyzer();

          $sq = "SELECT * FROM review ORDER BY RID DESC LIMIT 0,3";
          $re = $con->query($sq);

          // Initialize counters
          $positive_count = 0;
          $negative_count = 0;
          $neutral_count = 0;

          $reviews = [];

          if ($re->num_rows > 0) {
              while ($ro = $re->fetch_assoc()) {
                  $a = date_parse($ro["LOGS"]);
                  $c = date("F") . " " . $a["day"] . ", " . $a["year"];    

                  // Perform sentiment analysis
                  $sentiment = $analyzer->getSentiment($ro["MES"]);

                  // Determine sentiment category and count
                  $sentiment_label = "Neutral";
                  if ($sentiment['compound'] >= 0.05) {
                      $sentiment_label = "Positive 😊";
                      $positive_count++;
                  } elseif ($sentiment['compound'] <= -0.05) {
                      $sentiment_label = "Negative 😞";
                      $negative_count++;
                  } else {
                      $neutral_count++;
                  }

                  $reviews[] = [
                      "message" => $ro["MES"],
                      "date" => $c,
                      "sentiment" => $sentiment_label
                  ];
              }
          }

          // Convert data to JSON for JavaScript
          $chart_data = json_encode([
              "positive" => $positive_count,
              "negative" => $negative_count,
              "neutral" => $neutral_count
          ]);

        ?>
              <?php foreach ($reviews as $review) : ?>
                  <p><?php echo $review["message"]; ?> <span><?php echo $review["date"]; ?></span> <strong>(<?php echo $review["sentiment"]; ?>)</strong></p>
              <?php endforeach; ?>
            <button id="toggle" class="btn">Hide Chart</button>
        </div>
      </div>
      <div class="col-md-4 col-sm-6" id="chartContainer" style="display:block;margin-top:60px;">
        <canvas id="sentimentChart"></canvas> <!-- Chart Canvas -->
      </div>
      <div class="col-md-3 col-sm-5" id="chartContainer1" style="display:block;margin-top:30px;">
        <canvas id="sentimentChart1"></canvas> <!-- Chart Canvas -->

        <script src="chart.umd.js"></script> <!-- Chart.js Library -->
            <script>
                // Parse JSON data from PHP
                var chartData = <?php echo $chart_data; ?>;

                var ctx = document.getElementById('sentimentChart').getContext('2d');
                var sentimentChart = new Chart(ctx, {
                    type: 'bar', // Chart type
                    data: {
                        labels: ['Positive', 'Negative', 'Neutral'],
                        datasets: [{
                            label: 'Number of Reviews',
                            data: [chartData.positive, chartData.negative, chartData.neutral], // Data from PHP
                            backgroundColor: ['green', 'red', 'gray']
                        }]
                    },
                });
                var ctx = document.getElementById('sentimentChart1').getContext('2d');
                var sentimentChart1 = new Chart(ctx, {
                    type: 'doughnut', // Chart type
                    data: {
                        labels: ['Positive', 'Negative', 'Neutral'],
                        datasets: [{
                            label: 'Number of Reviews',
                            data: [chartData.positive, chartData.negative, chartData.neutral], // Data from PHP
                            backgroundColor: ['green', 'red', 'gray']
                        }]
                    },
                });
                document.getElementById('toggle').addEventListener('click',function(){
                var chartDiv =document.getElementById('chartContainer');
                var chartDiv1 =document.getElementById('chartContainer1');
                if(chartDiv.style.display==='block'){
                  chartDiv.style.display = 'none';
                  chartDiv1.style.display = 'none';
                  this.textContent="View Chart";
                }else{
                  chartDiv.style.display = 'block';
                  chartDiv1.style.display = 'block';
                  this.textContent="Hide Chart";
                }
                })
            </script>
      </div>
      <div class="col-md-3 pull-right col-md-3 col-sm-6 paddingtop-bottom">
			<h6 class="heading7"><?php echo $quote[rand(0,2)]; ?></h6>
			<img src="img/logo.jpg" style='margin-bottom:15px;' class="img-responsive img-thumbnail">
      </div>
    </div>
  </div>
</footer>

<div class="copyright">
  <div class="container">
    <div class="col-md-6">
      <p>Copyright &copy; <?php echo date("Y"); ?> Shopping Site With Sentiment Analysis</p>
    </div>
    <div class="col-md-6">
      <ul class="bottom_ul">
        <li><a href="contact.php">Contact us</a></li>
      </ul>
    </div>
  </div>
</div>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/formValidation.js"></script>
<script>
$(".alert").fadeTo(1000, 1000).slideUp(2000, function(){
    $(".alert").fadeOut(3000);
});
</script>