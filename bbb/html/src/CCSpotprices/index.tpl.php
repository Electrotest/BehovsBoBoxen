<?php if($tablespot):?>
	<?= $tablespot ?>
   
<?php endif;?>

<?php if($html):?>
    <div id ='temp'>
    	<caption><?= $text ?></caption>
    <?= $html ?>
<?php endif;?>

<?php if($header):?>
	<?= $header ?>
<?php endif; ?>

<div id="chartContainer2" style="height: 150px; width: 92%;"></div>
</div>

<script>
            window.onload = function() {

            var todaysDate = "<?php echo $todaysDate; ?>";
            var todayArray = <?php echo $todayArray; ?>;

            var tomorrowsDate = "<?php echo $tomorrowsDate; ?>";
            var tomorrowArray = <?php echo $tomorrowArray; ?>;

            CanvasJS.addColorSet("myColors",
                    [
                        "#99C9CC",
                        "orange",
                        "#9BBB58",
                        "#2E8B57",
                        "#3CB371",
                        "#90EE90",
                        "#008080",
                        "#2F4F4F"
                    ]);
					
			var chart2 = new CanvasJS.Chart("chartContainer2",
                    {
                        colorSet: "myColors",
                        zoomEnabled: false,
                        title: {
                            fontColor: "#2f4f4f",
                            fontSize: 30,
                            padding: 10,
                            margin: 15,
                            fontFamily: "comic sans ms",
                            fontWeight: "bold",
                            verticalAlign: "top", 
                            horizontalAlign: "center" 

                        },
                        axisY: {
                            valueFormatString: "0 öre", 
                            maximum: 100,
                            minimum: 0,
                            interval: 10,     
                            tickColor: "#D7D7D7",
                            title: "Pris",
                            titleFontFamily: "comic sans ms",
                            titleFontColor: "steelBlue",
                            lineThickness: 3
                        },
                        theme: "theme2",
                        legend: {
                            verticalAlign: "top",
                            horizontalAlign: "right",
                            fontSize: 15,
                            fontFamily: "tamoha",
                            fontColor: "Sienna",
                        },
                        data: [
                            {
                                type: "line",
                                lineThickness: 3,
                                showInLegend: true,
                                name: todaysDate,
                                dataPoints: [
                                    {x: 1, y: todayArray[0]},
                                    {x: 2, y: todayArray[1]},
                                    {x: 3, y: todayArray[2]},
                                    {x: 4, y: todayArray[3]},
                                    {x: 5, y: todayArray[4]},
                                    {x: 6, y: todayArray[5]},
                                    {x: 7, y: todayArray[6]},
                                    {x: 8, y: todayArray[7]},
                                    {x: 9, y: todayArray[8]},
                                    {x: 10, y: todayArray[9]},
                                    {x: 11, y: todayArray[10]},
                                    {x: 12, y: todayArray[11]},
                                    {x: 13, y: todayArray[12]},
                                    {x: 14, y: todayArray[13]},
                                    {x: 15, y: todayArray[14]},
                                    {x: 16, y: todayArray[15]},
                                    {x: 17, y: todayArray[16]},
                                    {x: 18, y: todayArray[17]},
                                    {x: 19, y: todayArray[18]},
                                    {x: 20, y: todayArray[19]},
                                    {x: 21, y: todayArray[20]},
                                    {x: 22, y: todayArray[21]},
                                    {x: 23, y: todayArray[22]},
                                    {x: 24, y: todayArray[23]}
                                ]
                            },
                            {
                                type: "line",
                                lineThickness: 3,
                                showInLegend: true,
                                name: tomorrowsDate,
                                dataPoints: [
                                    {x: 1, y: tomorrowArray[0]},
                                    {x: 2, y: tomorrowArray[1]},
                                    {x: 3, y: tomorrowArray[2]},
                                    {x: 4, y: tomorrowArray[3]},
                                    {x: 5, y: tomorrowArray[4]},
                                    {x: 6, y: tomorrowArray[5]},
                                    {x: 7, y: tomorrowArray[6]},
                                    {x: 8, y: tomorrowArray[7]},
                                    {x: 9, y: tomorrowArray[8]},
                                    {x: 10, y: tomorrowArray[9]},
                                    {x: 11, y: tomorrowArray[10]},
                                    {x: 12, y: tomorrowArray[11]},
                                    {x: 13, y: tomorrowArray[12]},
                                    {x: 14, y: tomorrowArray[13]},
                                    {x: 15, y: tomorrowArray[14]},
                                    {x: 16, y: tomorrowArray[15]},
                                    {x: 17, y: tomorrowArray[16]},
                                    {x: 18, y: tomorrowArray[17]},
                                    {x: 19, y: tomorrowArray[18]},
                                    {x: 20, y: tomorrowArray[19]},
                                    {x: 21, y: tomorrowArray[20]},
                                    {x: 22, y: tomorrowArray[21]},
                                    {x: 23, y: tomorrowArray[22]},
                                    {x: 24, y: tomorrowArray[23]}
                                ]
                            },
                        ]
                    });
				chart2.render();
            };

        </script>
<p><a href = '<?= create_url("spotprices/getspot") ?>'><?= $get ?></a></p>
</div>
