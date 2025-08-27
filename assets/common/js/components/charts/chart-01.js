import ApexCharts from "apexcharts";

// ===== chartOne
const chart01 = () => {
	const chartOneOptions = {
		series: [
			{
				name: 'Profit',
				data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
			}, {
				name: 'Loss',
				data: [76, 85, 100, 98, 87, 95, 91, 75, 94]
			}
		],
		colors: ['#2EBD85', '#F6465D'],
		chart: {
			fontFamily: "Outfit, sans-serif",
			type: "bar",
			height: 180,
			toolbar: {
				show: false,
			},
		},
		plotOptions: {
			bar: {
				horizontal: false,
				columnWidth: "39%",
				borderRadius: 5,
				borderRadiusApplication: "end",
			},
		},
		dataLabels: {
			enabled: false,
		},
		stroke: {
			show: true,
			width: 4,
			colors: ["transparent"],
		},
		xaxis: {
			categories: [
				"Jan",
				"Feb",
				"Mar",
				"Apr",
				"May",
				"Jun",
				"Jul",
				"Aug",
				"Sep",
				"Oct",
				"Nov",
				"Dec",
			],
			axisBorder: {
				show: false,
			},
			axisTicks: {
				show: false,
			},
		},
		legend: {
			show: true,
			position: "top",
			horizontalAlign: "left",
			fontFamily: "Outfit",

			markers: {
				radius: 99,
			},
		},
		yaxis: {
			title: false,
		},
		grid: {
			yaxis: {
				lines: {
					show: true,
				},
			},
		},
		fill: {
			opacity: 1,
		},

		tooltip: {
			x: {
				show: false,
			},
			y: {
				formatter: function (val) {
					return val;
				},
			},
		},
	};

	const chartSelector = document.querySelectorAll("#profitLossChart");

	if (chartSelector.length) {
		const chartFour = new ApexCharts(
			document.querySelector("#profitLossChart"),
			chartOneOptions,
		);
		chartFour.render();
	}
};

export default chart01;
