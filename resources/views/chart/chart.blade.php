<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart View</title>
    <!-- Include necessary stylesheets and scripts -->
    <!-- For example, you might include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


</head>

<body class="bg-body-secondary">
    <div class="container">
        <div class="header d-flex flex-row align-items-center justify-content-between p-5">
            <div class="title">
                <h2>Number of Views</h2>
                <h6>Product ID : {{ $productId }}</h6>
            </div>
            <div class="dateRange">
                <input type="text" name="daterange" id='datepicker' value="2023/01/01 - 2024/01/01" />
            </div>
        </div>
        <div class="chartArea">
            <canvas id="lineChart" height="200" width="600"></canvas>
        </div>


        <!-- Add your HTML content here -->





    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function() {
            $('#datepicker').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            updateChart();

            $('#datepicker').on('apply.daterangepicker', function(ev, picker) {
                updateChart();
            });

            function updateChart() {
                var dateRange = $('#datepicker').val().split(' - ');
                var startDate = dateRange[0];
                var endDate = dateRange[1];

                var productId = "{{ $productId }}";
                $.ajax({
                    url: "{{ route('fetchData') }}",
                    method: 'GET',
                    data: {
                        productId: productId,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(data) {
                        updateChartWithData(data);
                    },
                    error: function() {
                        console.error('Failed to fetch data.');
                    }
                });
            }

            function updateChartWithData(data) {
                var datasets = [];
                var labels = [];
                console.log("test => ", data);

                data.forEach(function(record) {
                    labels.push(record.unixTime);
                    datasets.push(record.views);
                });

                var ctx = document.getElementById('lineChart').getContext('2d');

                if (window.myLineChart) {
                    window.myLineChart.destroy();
                }

                window.myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Views',
                            data: datasets,
                            borderColor: '#3560a7',
                            borderWidth: 2,
                            fill: false
                        }]
                    },
                    options: {

                    }
                });
            }
        });
    </script>
</body>

</html>
