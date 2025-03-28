<script>
    (function($) {
        'use strict';

        //site chart
        let chart;
        flatpickr("#daterange", {
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: [
                "{{ $data['start_date'] }}",
                "{{ $data['end_date'] }}"
            ],
            onClose: function(selectedDates) {

                if (selectedDates.length === 2) {
                    const start = selectedDates[0];
                    const end = selectedDates[1];

                    $.get('{{ route('admin.dashboard') }}', {
                        start_date: flatpickr.formatDate(start, "Y-m-d"),
                        end_date: flatpickr.formatDate(end, "Y-m-d")
                    }, function(chartData) {
                        if (chart) chart.destroy();
                        chart_show(chartData);
                    });
                }
            }
        });


        var chartData = {
            'date_label': @json($data['date_label']),
            'deposit_statistics': @json($data['deposit_statistics']),
            'withdraw_statistics': @json($data['withdraw_statistics']),
            'symbol': @json($data['symbol'])
        }
        chart_show(chartData);

        function chart_show(chartData) {
            var date_label = Object.keys(chartData['date_label']);
            var deposit_data = Object.values(chartData['deposit_statistics']);
            var withdraw_data = Object.values(chartData['withdraw_statistics']);
            var symbol = chartData['symbol'];



            // Bar Chart
            var data = {
                labels: date_label,
                datasets: [{
                        label: 'Payments: ' + symbol + sumArrayValues(deposit_data),
                        data: deposit_data,
                        backgroundColor: '#ef476f',
                        borderColor: '#ffffff',
                        borderWidth: 0,
                        borderRadius: 90,
                        tension: 0.1
                    },
                    {
                        label: 'Approved Withdraws: ' + symbol + sumArrayValues(withdraw_data),
                        data: withdraw_data,
                        backgroundColor: '#2a9d8f',
                        borderColor: '#ffffff',
                        borderWidth: 0,
                        borderRadius: 90,
                        tension: 0.1
                    },
                ]
            };
            // render init block


            var ctx = document.getElementById('depositChart');
            var configuration = {
                type: 'bar',
                data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return (context.dataset.label.split(symbol)[0]).split(' ')[1] + ': ' +
                                        symbol + context.formattedValue;
                                }
                            }
                        }
                    }
                }
            }

            if (chart) {
                chart.destroy();
                chart = new Chart(ctx, configuration);
            } else {
                chart = new Chart(ctx, configuration);
            }
        }




        // //Plan chart
        // var invest_data = Object.values(schema);
        // var invest_label = Object.keys(schema);
        // // Bar Chart
        // var data = {
        //     labels: invest_label,
        //     datasets: [{
        //         label: 'Total Investment',
        //         data: invest_data,
        //         backgroundColor: [
        //             '#5e3fc9',
        //             '#2a9d8f',
        //             '#ee6c4d',
        //             '#6d597a',
        //             '#003566',
        //             '#ef476f',
        //             '#718355',
        //         ],
        //         borderColor: [
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff'
        //         ],
        //         borderWidth: 3,
        //         borderRadius: 12,
        //         barPercentage: 0.3,
        //         hoverBackgroundColor: '#003566',
        //     }]
        // };
        // // render init block
        // new Chart(
        //     document.getElementById('schemeChart'), {
        //         type: 'doughnut',
        //         data,
        //         options: {
        //             scales: {
        //                 y: {
        //                     beginAtZero: true
        //                 }
        //             }
        //         }
        //     }
        // );


        // // Country Chart
        // var country_data = Object.values(country);
        // var country_label = Object.keys(country);
        // var data = {
        //     labels: country_label,
        //     datasets: [{
        //         label: 'Country',
        //         data: country_data,
        //         backgroundColor: [
        //             '#5e3fc9',
        //             '#2a9d8f',
        //             '#ef476f',
        //             '#718355',
        //             '#ee6c4d',
        //             '#6d597a',
        //             '#003566',
        //             "#b91d47",
        //             "#00aba9",
        //             "#2b5797",
        //             "#e8c3b9",
        //             "#1e7145"
        //         ],
        //         borderColor: [
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff'
        //         ],
        //         borderWidth: 3,
        //         borderRadius: 12,
        //         barPercentage: 0.3,
        //         hoverBackgroundColor: '#003566',
        //     }]
        // };
        // // render init block
        // new Chart(
        //     document.getElementById('countryChart'), {
        //         type: 'doughnut',
        //         data,
        //         options: {
        //             scales: {
        //                 y: {
        //                     beginAtZero: true
        //                 }
        //             }
        //         }
        //     }
        // );

        // // Browser Chart
        // var browser_data = Object.values(browser);
        // var browser_label = Object.keys(browser);
        // var data = {
        //     labels: browser_label,
        //     datasets: [{
        //         label: 'Browser',
        //         data: browser_data,
        //         backgroundColor: [
        //             '#5e3fc9',
        //             '#2a9d8f',
        //             '#ef476f',
        //             '#718355',
        //             '#ee6c4d',
        //             '#6d597a',
        //             '#003566',
        //             "#b91d47",
        //             "#00aba9",
        //             "#2b5797",
        //             "#e8c3b9",
        //             "#1e7145"
        //         ],
        //         borderColor: [
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff'
        //         ],
        //         borderWidth: 2,
        //         borderRadius: 12,
        //         barPercentage: 0.3,
        //         hoverBackgroundColor: '#003566',
        //     }]
        // };
        // // render init block
        // new Chart(
        //     document.getElementById('browserChart'), {
        //         type: 'polarArea',
        //         data,
        //         options: {
        //             scales: {
        //                 y: {
        //                     beginAtZero: true
        //                 }
        //             }
        //         }
        //     }
        // );

        // // OS Chart
        // var platform_data = Object.values(platform);
        // var platform_label = Object.keys(platform);
        // var data = {
        //     labels: platform_label,
        //     datasets: [{
        //         label: 'OS',
        //         data: platform_data,
        //         backgroundColor: [
        //             '#5e3fc9',
        //             '#718355',
        //             '#ef476f',
        //             '#ee6c4d',
        //             "#b91d47",
        //             "#2b5797",
        //             "#e8c3b9",
        //             "#1e7145",
        //             '#2a9d8f',
        //         ],
        //         borderColor: [
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff',
        //             '#ffffff'
        //         ],
        //         borderWidth: 3,
        //         borderRadius: 12,
        //         barPercentage: 0.3,
        //         hoverBackgroundColor: '#003566',
        //     }]
        // };
        // // render init block
        // new Chart(
        //     document.getElementById('osChart'), {
        //         type: 'pie',
        //         data,
        //         options: {
        //             scales: {
        //                 y: {
        //                     beginAtZero: true
        //                 }
        //             }
        //         }
        //     }
        // );

    })(jQuery);
</script>
