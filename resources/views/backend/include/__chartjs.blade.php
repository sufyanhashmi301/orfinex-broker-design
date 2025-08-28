<script>
    (function ($) {
        'use strict';

        // Professional Color Palette
        const professionalColors = {
            slate: '#64748b',
            darkSlate: '#475569',
            lightSlate: '#94a3b8',
            emerald: '#10b981',
            amber: '#f59e0b',
            blue: '#3b82f6',
            red: '#ef4444',
            cyan: '#06b6d4',
            lime: '#84cc16',
            orange: '#f97316'
        };

        // Chart color arrays for consistency
        const chartColors = [
            professionalColors.slate,
            professionalColors.emerald,
            professionalColors.amber,
            professionalColors.blue,
            professionalColors.red,
            professionalColors.cyan,
            professionalColors.darkSlate,
            professionalColors.lime,
            professionalColors.orange,
            professionalColors.lightSlate
        ];

        // Site chart
        let chart;

        $('#transactions_statistics_filter').on('submit', function (e) {
            e.preventDefault();

            const dateRangeStr = $('input[name="daterange"]').val();

            if (!dateRangeStr || !dateRangeStr.includes(' to ')) {
                alert('Please select a valid date range.');
                return;
            }

            const [startDate, endDate] = dateRangeStr.split(' to ');

            $.get('{{ route('admin.dashboard') }}', {
                start_date: startDate,
                end_date: endDate
            }, function(chartData) {    
                if (typeof chart !== 'undefined' && chart !== null) {
                    chart.destroy();
                }
                chart_show(chartData);
            });
        });

        function chart_show(chartData){
            var date_label = Object.keys(chartData['date_label']);
            var deposit_data = Object.values(chartData['deposit_statistics']);
            var invest_data = Object.values(chartData['demo_deposit_statistics']);
            var withdraw_data = Object.values(chartData['withdraw_statistics']);
            var profit_data = Object.values(chartData['ib_bonus_statistics']);
            var symbol = chartData['symbol'];

            // Professional Bar Chart Configuration
            var data = {
                labels: date_label,
                datasets: [{
                    label: 'Total Deposit ' + symbol + sumArrayValues(deposit_data),
                    data: deposit_data,
                    backgroundColor: professionalColors.slate,
                    borderColor: '#ffffff',
                    borderWidth: 0,
                    borderRadius: 6,
                    borderSkipped: false,
                }, {
                    label: 'Total Withdraw ' + symbol + sumArrayValues(withdraw_data),
                    data: withdraw_data,
                    backgroundColor: professionalColors.red,
                    borderColor: '#ffffff',
                    borderWidth: 0,
                    borderRadius: 6,
                    borderSkipped: false,
                }, {
                    label: 'Total Demo Deposit ' + symbol + sumArrayValues(invest_data),
                    data: invest_data,
                    backgroundColor: professionalColors.emerald,
                    borderColor: '#ffffff',
                    borderWidth: 0,
                    borderRadius: 6,
                    borderSkipped: false,
                }, {
                    label: 'Total IB Bonus ' + symbol + sumArrayValues(profit_data),
                    data: profit_data,
                    backgroundColor: professionalColors.amber,
                    borderColor: '#ffffff',
                    borderWidth: 0,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            };

            var ctx = document.getElementById('depositChart');
            var configuration = {
                type: 'bar',
                data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)',
                                drawBorder: false,
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: 'Inter, system-ui, sans-serif',
                                    size: 12,
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: 'Inter, system-ui, sans-serif',
                                    size: 12,
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'start',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                font: {
                                    family: 'Inter, system-ui, sans-serif',
                                    size: 12,
                                    weight: '500'
                                },
                                color: '#475569'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleColor: '#f8fafc',
                            bodyColor: '#e2e8f0',
                            borderColor: '#475569',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function (context) {
                                    return (context.dataset.label.split(symbol)[0]).split(' ')[1] + ': ' + symbol + context.formattedValue;
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

        var chartData = {
            'date_label': @json($data['date_label']),
            'deposit_statistics': @json($data['deposit_statistics']),
            'demo_deposit_statistics': @json($data['demo_deposit_statistics']),
            'withdraw_statistics': @json($data['withdraw_statistics']),
            'ib_bonus_statistics': @json($data['ib_bonus_statistics']),
            'symbol': @json($data['symbol']),
        }
        chart_show(chartData);

        // Deposit Statistics Chart (Doughnut)
        var schema = @json($data['total_deposit_statistics']);
        var invest_data = Object.values(schema);
        var invest_label = Object.keys(schema).map(function(key) {
            return key.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
        });

        var schemeData = {
            labels: invest_label,
            datasets: [{
                label: 'Total Deposit',
                data: invest_data,
                backgroundColor: chartColors.slice(0, invest_data.length),
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 8,
                hoverBorderWidth: 4,
            }]
        };

        new Chart(document.getElementById('schemeChart'), {
            type: 'doughnut',
            data: schemeData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15,
                            font: {
                                family: 'Inter, system-ui, sans-serif',
                                size: 11,
                                weight: '500'
                            },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#f8fafc',
                        bodyColor: '#e2e8f0',
                        borderColor: '#475569',
                        borderWidth: 1,
                        cornerRadius: 8,
                    }
                }
            }
        });

        // Country Statistics Chart (Doughnut)
        var country = @json($data['country']);
        var country_data = Object.values(country);
        var country_label = Object.keys(country);

        var countryData = {
            labels: country_label,
            datasets: [{
                label: 'Country',
                data: country_data,
                backgroundColor: chartColors.slice(0, country_data.length),
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 6,
                hoverBorderWidth: 4,
            }]
        };

        new Chart(document.getElementById('countryChart'), {
            type: 'doughnut',
            data: countryData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 12,
                            font: {
                                family: 'Inter, system-ui, sans-serif',
                                size: 11,
                                weight: '500'
                            },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#f8fafc',
                        bodyColor: '#e2e8f0',
                        borderColor: '#475569',
                        borderWidth: 1,
                        cornerRadius: 8,
                    }
                }
            }
        });

        // Browser Statistics Chart (Doughnut)
        var browser = @json($data['browser']);
        var browser_data = Object.values(browser);
        var browser_label = Object.keys(browser);

        var browserData = {
            labels: browser_label,
            datasets: [{
                label: 'Browser',
                data: browser_data,
                backgroundColor: chartColors.slice(0, browser_data.length),
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 6,
                hoverBorderWidth: 4,
            }]
        };

        new Chart(document.getElementById('browserChart'), {
            type: 'doughnut',
            data: browserData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '50%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 12,
                            font: {
                                family: 'Inter, system-ui, sans-serif',
                                size: 11,
                                weight: '500'
                            },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#f8fafc',
                        bodyColor: '#e2e8f0',
                        borderColor: '#475569',
                        borderWidth: 1,
                        cornerRadius: 8,
                    }
                }
            }
        });

        // OS Statistics Chart (Pie)
        var platform = @json($data['platform']);
        var platform_data = Object.values(platform);
        var platform_label = Object.keys(platform);

        var platformData = {
            labels: platform_label,
            datasets: [{
                label: 'Operating System',
                data: platform_data,
                backgroundColor: chartColors.slice(0, platform_data.length),
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 8,
                hoverBorderWidth: 4,
            }]
        };

        new Chart(document.getElementById('osChart'), {
            type: 'pie',
            data: platformData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 12,
                            font: {
                                family: 'Inter, system-ui, sans-serif',
                                size: 11,
                                weight: '500'
                            },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#f8fafc',
                        bodyColor: '#e2e8f0',
                        borderColor: '#475569',
                        borderWidth: 1,
                        cornerRadius: 8,
                    }
                }
            }
        });

    })(jQuery);
</script>