<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold Price Information</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta property="og:title" content="Gold Price By Megat Irfan">
    <meta property="og:description" content="Check the latest gold prices in MYR with a chart by ">
    <meta property="og:image" content="https://razzihajemi.files.wordpress.com/2017/06/public-gold-logo-maroon.png"> <!-- Replace with the actual URL of your gold price image -->
    <!-- Add Bootstrap CSS link -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Add DM Sans font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center"></h1>
    
    <!-- Displaying the image -->
    <div class="text-center">
        <img src="https://razzihajemi.files.wordpress.com/2017/06/public-gold-logo-maroon.png" alt="Public Gold Logo" width="30%">
    </div>
    <?php
        date_default_timezone_set('Asia/Kuala_Lumpur');
        // Constants for conversion
        $gramsPerOunce = 31.1035; // Conversion factor from ounces to grams
        $openExchangeRatesApiKey = 'ab91947af02d33681a657496';
        $exchangeRateApiUrl = "https://open.er-api.com/v6/latest";
        $exchangeRateApiParams = [
            'base' => 'USD',
            'symbols' => 'MYR',
            'apikey' => $openExchangeRatesApiKey,
        ];
        
        $exchangeRateApiResponse = file_get_contents($exchangeRateApiUrl . '?' . http_build_query($exchangeRateApiParams));
        $exchangeRateData = json_decode($exchangeRateApiResponse, true);
        
        if (isset($exchangeRateData['rates']['MYR'])) {
            $usdToMyrExchangeRate = $exchangeRateData['rates']['MYR'];
        } else {
            $error = 'Error fetching exchange rate data';
        }
        $url = 'https://data-asg.goldprice.org/dbXRates/USD';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);

        if (curl_errno($ch)) {
            echo '<p>Error fetching data: ' . curl_error($ch) . '</p>';
            exit;
        }

        curl_close($ch);

        $decoded = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo '<p>Error decoding JSON: ' . json_last_error_msg() . '</p>';
            exit;
        }

       
        if (isset($decoded->items[0]->xauPrice) && isset($decoded->date)) {
            date_default_timezone_set('Asia/Kuala_Lumpur');
            $date1 = date('d/m/Y h:i:s a', time());
            $item = $decoded->items[0];
            $date = $decoded->date;
            $goldPricePerOunce = $item->xauPrice;

            // Calculate gold price per gram in MYR
            $goldPricePerGramMYR = ($goldPricePerOunce / $gramsPerOunce) * $usdToMyrExchangeRate;

            echo "<br><p class='text-center'>Malaysia Gold price per Gram on  $date1 is <br><b>RM " . number_format($goldPricePerGramMYR, 2) . "</p>";
        } else {
            echo '<p class="text-center text-danger">Unexpected JSON structure</p>';
            exit;
        }
    ?>
    <!-- Chart Container -->
    <div class="mt-5">
    <canvas id="goldPriceChart" style="max-width: 100%;"></canvas>
        </div>
    </div>

    <!-- Bootstrap and Chart.js Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript for Chart -->
    <script>
    // Your existing PHP code to get $date and $goldPricePerGramMYR

    // Chart.js configuration
    var ctx = document.getElementById('goldPriceChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // Change the chart type as needed
        data: {
            labels: ['Gold Price (MYR)'],
            datasets: [{
                label: 'Gold Price',
                data: [<?php echo json_encode($goldPricePerGramMYR); ?>],
                backgroundColor: 'rgba(238, 232, 170, 0.2)', // Change the color as needed
                borderColor: 'rgba(238, 232, 170, 1)', // Change the color as needed
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<footer class="mt-5 text-center">
    <p>Created by <a href="https://megatirfan.com" target="_blank">Megat Irfan</a></p>
</footer>
</body>
</html>
</body>
</html>
