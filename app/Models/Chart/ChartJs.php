<?php
namespace App\Models\Chart;

class ChartJs {
	
	public static function line($canvas, $labels, $data, $opt=null){
		$sdatasets ='';
		$colors = isset($opt->colors) ? $opt->colors : config('app.line_colors');
		foreach($data->label as $i=>$label){
			$sdatasets .='{
				label: "'.$label.'",
				fill: false,
				lineTension: 0.3,
				backgroundColor: "'.(isset($colors[$i])?$colors[$i]:'rgba(2,117,216,0.2)').'",
				borderColor: "'.(isset($colors[$i])?$colors[$i]:'rgba(2,117,216,0.2)').'",
				pointHoverBackgroundColor: "'.(isset($colors[$i])?$colors[$i]:'rgba(2,117,216,0.2)').'",
				pointHitRadius: 50,
				pointBorderWidth: 2,
				data: '.json_encode($data->data[$i]).',
			},';
		}
		//dd($sdatasets);
		
		$sdatasets = rtrim($sdatasets,",");
		
		$sLineChart ='
			<script>
			$(function() {
				var ctx = document.getElementById("'.$canvas.'");
				'.$canvas.'LineChart = new Chart(ctx, {
					type: "line",
					data: {
						labels: '.json_encode($labels).',
						datasets: ['.$sdatasets.']
					},
					options: {
						scales: {
							xAxes: [{
								time: {
									unit: "date"
								},
								gridLines: {
									display: false
								},
								ticks: {
									maxTicksLimit: 7
								}
							}],
							yAxes: [{
								ticks: {
									min: 0,
									//max: 40000,
									maxTicksLimit: 5,
									callback: function(label, index, labels) {
										//return label/1000+"k";
										return label;
									}
								},
								gridLines: {
									color: "rgba(0, 0, 0, .125)",
								}
							}],
						},
						legend: {
							display: '.($opt && isset($opt->legend->display) ? $opt->legend->display : 'false' ).',
							position: "'.($opt && isset($opt->legend->position) ? $opt->legend->position :'right').'",
							align: "end",
							rtl: true,
							labels: {
								fontSize: 11
							},
						},
						tooltips: {
							callbacks: {
								label: function(tooltipItem, data) {
									var label = data.datasets[tooltipItem.datasetIndex].label;
									//return label+": Rp"+tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
									return label+": "+tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
								},
							},
						},
						plugins: {
							datalabels: {
								// hide datalabels for all datasets
								display: true,
								formatter: function(value, context) {
									return value;
									//return context.chart.data.labels[context.dataIndex];
									if(context.dataIndex % 2 == 0){
										if(value>0){
											return "Rp"+value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
										}else{
											return "";
										}
									}else{
										return "";
									}
								},
								font: {
								  size: 11,
								}
							}
						}
					}
				});
			});
			</script>';
			
		//dd($sLineChart);
		
		return $sLineChart;
	}
	
	/*
	* BAR
	*/
	public static function bar($canvas, $labels, $data, $opt=null){
		$colors = isset($opt->colors) ? $opt->colors : config('app.line_colors');
		$sdatasets ='';
		foreach($data->label as $i=>$label){
			$sdatasets .='{
				label: "'.$label.'",
				backgroundColor: "'.(isset($colors[$i])?$colors[$i]:'rgba(2,117,216,0.2)').'",
				data: '.json_encode($data->data[$i]).'
			},';
		}
		
		$sdatasets = rtrim($sdatasets,",");
		
		$sBarChart ='
		<script>
		$(function() {		
			var ctx = document.getElementById("'.$canvas.'").getContext("2d");

			var data = {
			  labels: '.json_encode($labels).',
			  datasets: ['.$sdatasets.']
			};

			var '.$canvas.'BarChart = new Chart(ctx, {
			  type: "bar",
			  data: data,
			  options: {
				barValueSpacing: 10,
				scales: {
				  yAxes: [{
					ticks: {
						min: 0,
						maxTicksLimit: 5,
						callback: function(label, index, labels) {
							//return label/1000+"k";
							return label;
						}
					}
				  }]
				},
				legend: {
					display:false,
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							var label = data.datasets[tooltipItem.datasetIndex].label;
							//return label+": Rp"+tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
							return label;
						},
					},
				},
				plugins:{
					datalabels: {
						// hide datalabels for all datasets
						display: true,
						formatter: function(value, context) {
							//return context.chart.data.labels[context.dataIndex];
							if(value>0){
								return "Rp"+value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
							}else{
								return "";
							}
						},
						font: {
						  size: 9,
						  align :"top",
						}
					}
				}
			  }
			});
		});
		</script>';

		//dd($sBarChart);
		
		return $sBarChart;
	}

	/*
	* PIE
	*/
	public static function pie($canvas, $labels, $data, $opt=null){
		$colors = isset($opt->colors) ? $opt->colors : config('app.line_colors');
		$sdatasets ='{
			label: "Pie",
			backgroundColor: '.json_encode($colors).',
			data: '.json_encode($data->data).'
		}';
		//dd($sdatasets);

		$sPieChart ='
			<script>
			$(function() {
				var ctx = document.getElementById("'.$canvas.'");
				'.$canvas.'PieChart = new Chart(ctx, {
					type: "pie",
					data: {
						datasets: ['.$sdatasets.'],
						labels: '.json_encode($labels).'
					},
					options: {
						responsive: true,
						title: {
							display: false,
							text: "My Pie",
						},
						legend: {
							display: false,
						},
						tooltips: {
							enabled: true,
							callbacks: {
								label: function(tooltipItem, data) {
									var label = data.labels[tooltipItem.index];
									var totalTrx =data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
									var formatTrx = (totalTrx) ? totalTrx.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : 0;
									
									return label+": "+formatTrx;
								},
							},
						},
						plugins: {
							datalabels:{
								formatter: (value, ctx) => {
									let datasets = ctx.chart.data.datasets;
									var sum =0;
									$.each(datasets, function(key, ds){
										$.each(ds.data, function(k, v){
											var tot = parseInt(v);
											sum = sum+(isNaN(tot) ? 0 : tot);
										});
									});
									value = isNaN(parseInt(value)) ? 0 : parseInt(value);
									let numPercent = sum > 0 ? Math.round((value / sum) * 100) : 0;
									let percentage = isNaN(numPercent) ? "0" : numPercent;
			
									return percentage+"%";
								},
								color: "#fff",
								labels: {
									title: {
										font: {
											weight: "bold",
											size: 18,
										}
									},
								}
							}
						},
						
					}
				});
			});
			</script>';

		//dd($sPieChart);
		
		return $sPieChart;
	}
	
}
?>