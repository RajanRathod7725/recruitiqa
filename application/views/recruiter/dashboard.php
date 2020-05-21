<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>

    <?php require_once('includes/headerscripts.php'); ?>

</head>


<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php require_once('includes/topbar.php'); ?>
    <?php require_once('includes/sidebar.php'); ?>

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Analytics Start -->
                <section id="dashboard-analytics">
                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12">
                            <div class="card bg-analytics text-white">
                                <div class="card-content">
                                    <div class="card-body text-center">
                                        <img src="<?php echo base_url(); ?>/resources/app-assets/images/elements/decore-left.png" class="img-left" alt="card-img-left">
                                        <img src="<?php echo base_url(); ?>/resources/app-assets/images/elements/decore-right.png" class="img-right" alt="card-img-right">
                                        <div class="avatar avatar-xl bg-primary shadow mt-0">
                                            <div class="avatar-content">
                                                <i class="feather icon-award white font-large-1"></i>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <h1 class="mb-2 text-white">Congratulations <?php echo ucfirst($this->session->userdata('recruiter_name')); ?>,</h1>
                                            <p class="m-auto w-75">You submitted <strong><?php echo $profile->count; ?></strong> profiles last week, Check out the active roles and start submitting the candidates!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex flex-column align-items-start pb-0">
                                    <div class="avatar bg-rgba-primary p-50 m-0">
                                        <div class="avatar-content">
                                            <i class="feather icon-users text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="text-bold-700 mt-1 mb-25"><?php echo $job->jobcount; ?></h2>
                                    <p class="mb-0">Active Jobs</p>
                                </div>
                                <div class="card-content">
                                    <div id="subscribe-gain-chart"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Approval & Disqualification Rate</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <div id="mixed-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Dashboard Analytics end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <?php require_once('includes/footer.php'); ?>
    <?php require_once('includes/footerscripts.php'); ?>
    <script>
        /*recruiter dashboard chart*/
        var $primary = '#7367F0',
            $success = '#28C76F',
            $danger = '#EA5455',
            $warning = '#FF9F43',
            $info = '#00cfe8',
            $label_color_light = '#dae1e7';

        var themeColors = [$primary, $success, $danger, $warning, $info];

        var yaxis_opposite = false;
        if($('html').data('textdirection') == 'rtl'){
            yaxis_opposite = true;
        }
        // Mixed Chart
        // -----------------------------
        var mixedChartOptions = {
            chart: {
                height: 350,
                type: 'line',
                stacked: false,
            },
            colors: themeColors,
            stroke: {
                width: [0, 2, 5],
                curve: 'smooth'
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%'
                }
            },
            // colors: ['#3A5794', '#A5C351', '#E14A84'],
            series: [{
                name: 'TEAM A',
                type: 'column',
                data: [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 30]
            }, {
                name: 'TEAM B',
                type: 'area',
                data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43]
            }, {
                name: 'TEAM C',
                type: 'line',
                data: [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39]
            }],
            fill: {
                opacity: [0.85, 0.25, 1],
                gradient: {
                    inverseColors: false,
                    shade: 'light',
                    type: "vertical",
                    opacityFrom: 0.85,
                    opacityTo: 0.55,
                    stops: [0, 100, 100, 100]
                }
            },
            labels: ['01/01/2003', '02/01/2003', '03/01/2003', '04/01/2003', '05/01/2003', '06/01/2003', '07/01/2003', '08/01/2003', '09/01/2003', '10/01/2003', '11/01/2003'],
            markers: {
                size: 0
            },
            legend: {
                offsetY: -10
            },
            xaxis: {
                type: 'datetime'
            },
            yaxis: {
                min: 0,
                tickAmount: 5,
                title: {
                    text: 'Points'
                },
                opposite: yaxis_opposite
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y) {
                        if (typeof y !== "undefined") {
                            return y.toFixed(0) + " views";
                        }
                        return y;

                    }
                }
            }
        }
        var mixedChart = new ApexCharts(
            document.querySelector("#mixed-chart"),
            mixedChartOptions
        );
        mixedChart.render();


        /*cahrt 2*/
        var gainedChartoptions = {
            chart: {
                height: 100,
                type: 'area',
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                },
                grid: {
                    show: false,
                    padding: {
                        left: 0,
                        right: 0
                    }
                },
            },
            colors: [$primary],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0.9,
                    opacityFrom: 0.7,
                    opacityTo: 0.5,
                    stops: [0, 80, 100]
                }
            },
            series: [{
                name: 'Last Week Job(s)',
                data: [<?php echo $week_job; ?>]
            }],

            xaxis: {
                labels: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                }
            },
            yaxis: [{
                y: 0,
                offsetX: 0,
                offsetY: 0,
                padding: { left: 0, right: 0 },
            }],
            tooltip: {
                x: { show: false }
            },
        }

        var gainedChart = new ApexCharts(
            document.querySelector("#subscribe-gain-chart"),
            gainedChartoptions
        );

        gainedChart.render();
        /*recruiter dashboard chart end*/
    </script>

</body>

</html>