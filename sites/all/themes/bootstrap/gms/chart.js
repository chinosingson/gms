(function ($) {
	Drupal.behaviors.graph_content = {
		attach: function (context){
			if (Drupal.settings.graph_content)  {
				var data = Drupal.settings.graph_content.data.data;
				var title = Drupal.settings.graph_content.data.title;
				// Place a div name correcly.
				$("#show_graph_div").append("<div id='show_report'>Graph will display here.</div>");
				//$(function($) {
				$('#show_report').highcharts({
						chart: {
								type: 'spline'
						},
						title: {
								text: title
						},
						subtitle: {
								text: ''
						},
						xAxis: {
								type: 'datetime',
								dateTimeLabelFormats: { // don't display the dummy year
										month: '%e. %b',
										year: '%Y'
								}
						},
						yAxis: {
								title: {
										text: title
								},
								min: 0
						},
						tooltip: {
								formatter: function() {
												return ''+ this.series.name +' '+ Highcharts.dateFormat('%e. %b %Y', this.x) +': '+ this.y;
								}
						},
						series: $.parseJSON(data)
				});
				//});
			}
		}
	}
}(jQuery));