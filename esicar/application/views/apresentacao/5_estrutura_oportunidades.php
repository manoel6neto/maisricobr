<script type="text/javascript">

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages': ['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
<?php $num_programas = 0; ?>
<?php foreach ($programas_ministerio as $key => $value): ?>
    <?php if ($value != end($programas_ministerio)): ?>
                ['<?php echo '(' . count($value) . ') ' . strtoupper($key); ?>', <?php echo(count($value));
        $num_programas+=count($value); ?>],
    <?php else: ?>
                ['<?php echo '(' . count($value) . ') ' . strtoupper($key); ?>', <?php echo(count($value));
        $num_programas+=count($value); ?>]
    <?php endif; ?>
<?php endforeach; ?>
        ]);

        // Set chart options
        var options = {
            'title': <?php echo ($num_programas); ?>,
            'width': 1280,
            'height': 720,
            'is3D': true,
            'pieSliceText': 'value-and-percentage',
            'tooltip': {textStyle: {color: '#FF0000', bold: true}, showColorCode: true, trigger: 'selection'},
            'chartArea': {left: 0, top: 0, width: '100%', height: '100%'},
            'backgroundColor': '#f3f3f3',
            'legend': {position: 'labeled', alignment: 'center', textStyle: {color: '#222222', bold: true}}
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

</script>
<h1 style="margin-left: 32px">Total de programas: <?php echo ($num_programas);?></h1>
<div id="chart_div" style="margin-top: 20px !important; display: flex;  flex-direction: row; justify-content: center; align-items: center; vertical-align: central;">
</div>