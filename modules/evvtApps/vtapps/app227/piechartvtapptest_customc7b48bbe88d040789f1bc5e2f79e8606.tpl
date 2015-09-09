
<script type="text/javascript" src="modules/evvtApps/piejs/highcharts.js"></script>
<script type="text/javascript" src="modules/evvtApps/piejs/themes/gray.js"></script>
<script type="text/javascript" src="modules/evvtApps/piejs/modules/exporting.js"></script>
<div id="Graficovtapptest_customc7b48bbe88d040789f1bc5e2f79e8606" style="width: 1300px; height: 650px; margin: 0 auto"></div>
<script language="javascript">
{literal}
var chart;
chart = new Highcharts.Chart({
	     chart: {renderTo: "Graficovtapptest_customc7b48bbe88d040789f1bc5e2f79e8606",
                type: 'column'
            },
            title: {
              text: '{/literal}{$Title}{literal}'
            },
            subtitle: {
            text: '{/literal}{$Title2}{literal}'
            },
            xAxis: {
                               categories: 	[{/literal}{$cat}{literal}]

            },
            yAxis: {
                min: 0,
                title: {
                    text: "*"
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{/literal}{$PotData}{literal}]
        });
{/literal}
</script>
