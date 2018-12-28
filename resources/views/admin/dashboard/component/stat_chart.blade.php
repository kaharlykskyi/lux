<script>
    @php
        foreach ($statistic_new_uses as $item){
            $data[] = $item['data'];
        }
        foreach ($statistic_new_uses as $item){
            $count[] = $item['count'];
        }
        $data = json_encode(array_reverse($data));
        $count = json_encode(array_reverse($count));

        foreach ($statistic_orders as $item){
            $data_orders[] = $item['data'];
        }
        foreach ($statistic_orders as $item){
            $count_orders[] = $item['count'];
        }
        $data_orders = json_encode(array_reverse($data_orders));
        $count_orders = json_encode(array_reverse($count_orders));
    @endphp
    $(document).ready(function () {
        (function ($) {
            try{
                //WidgetChart 1
                var ctx = document.getElementById("widgetChart1");
                if (ctx) {
                    ctx.height = 130;
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: {!! $data !!},
                            type: 'line',
                            datasets: [{
                                data: {!! $count !!},
                                label: 'Пользователи',
                                backgroundColor: 'rgba(255,255,255,.1)',
                                borderColor: 'rgba(255,255,255,.55)',
                            },]
                        },
                        options: {
                            maintainAspectRatio: true,
                            legend: {
                                display: false
                            },
                            layout: {
                                padding: {
                                    left: 0,
                                    right: 0,
                                    top: 0,
                                    bottom: 0
                                }
                            },
                            responsive: true,
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        color: 'transparent',
                                        zeroLineColor: 'transparent'
                                    },
                                    ticks: {
                                        fontSize: 2,
                                        fontColor: 'transparent'
                                    }
                                }],
                                yAxes: [{
                                    display: false,
                                    ticks: {
                                        display: false,
                                    }
                                }]
                            },
                            title: {
                                display: false,
                            },
                            elements: {
                                line: {
                                    borderWidth: 0
                                },
                                point: {
                                    radius: 0,
                                    hitRadius: 10,
                                    hoverRadius: 4
                                }
                            }
                        }
                    });
                }

                //WidgetChart 2
                var ctx = document.getElementById("widgetChart2");
                if (ctx) {
                    ctx.height = 130;
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: {!! $data_orders !!},
                            type: 'line',
                            datasets: [{
                                data: {!! $count_orders !!},
                                label: 'Заказы',
                                backgroundColor: 'transparent',
                                borderColor: 'rgba(255,255,255,.55)',
                            },]
                        },
                        options: {

                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            },
                            responsive: true,
                            tooltips: {
                                mode: 'index',
                                titleFontSize: 12,
                                titleFontColor: '#000',
                                bodyFontColor: '#000',
                                backgroundColor: '#fff',
                                titleFontFamily: 'Montserrat',
                                bodyFontFamily: 'Montserrat',
                                cornerRadius: 3,
                                intersect: false,
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        color: 'transparent',
                                        zeroLineColor: 'transparent'
                                    },
                                    ticks: {
                                        fontSize: 2,
                                        fontColor: 'transparent'
                                    }
                                }],
                                yAxes: [{
                                    display: false,
                                    ticks: {
                                        display: false,
                                    }
                                }]
                            },
                            title: {
                                display: false,
                            },
                            elements: {
                                line: {
                                    tension: 0.00001,
                                    borderWidth: 1
                                },
                                point: {
                                    radius: 4,
                                    hitRadius: 10,
                                    hoverRadius: 4
                                }
                            }
                        }
                    });
                }
            } catch (e) {
                console.log(e)
            }
        })(jQuery)
    });
</script>