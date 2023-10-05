
<div class="content-wrapper" id="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mt-1">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right mt-1">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?php echo $all_history_adjust; ?></h3>
                            <p>History Adjustments</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-list"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo $pending_count; ?></h3>

                            <p>Pendings</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $approved_count; ?></h3>

                            <p>Approved</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-thumbs-up"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $disapproved_count; ?></h3>
                            <p>Disapproved</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-thumbs-down"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary" style="background-color: rgba(245, 245, 245, 0.57)">
                        <div class="card-header">
                            <h3 class="card-title">Donut Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary" style="background-color: rgba(245, 245, 245, 0.57)">
                        <div class="card-header">
                            <h3 class="card-title">Line Chart</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>

    //******************** BAR CHART ******************//

    const pendingCountBar = <?php echo $pending_count; ?>;
    const approvedCountBar = <?php echo $approved_count; ?>;
    const disapprovedCountBar = <?php echo $disapproved_count; ?>;
    
    const dataBar = {
        labels: ['Pending', 'Approved', 'Disapproved'],
        datasets: [{
            label: 'Counts',
            data: [pendingCountBar, approvedCountBar, disapprovedCountBar],
            backgroundColor: ['#DC3545','#28A745','#FFC107'],
            borderColor: '#FFFFFF',
            borderWidth: 1
        }]
    };
    
    const configBar = {
        type: 'bar',
        data: dataBar,
        options: {
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Status Distribution',
                }
            }
        }
    };
    
    const ctxBar = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(ctxBar, configBar);

    //******************** DONUT CHART ******************//
    const pendingCountDonut = <?php echo $pending_count; ?>;
    const approvedCountDonut = <?php echo $approved_count; ?>;
    const disapprovedCountDonut = <?php echo $disapproved_count; ?>;
    
    const dataDonut = {
        labels: ['Pending', 'Approved', 'Disapproved'],
        datasets: [{
            data: [pendingCountDonut, approvedCountDonut, disapprovedCountDonut],
            backgroundColor: ['#DC3545','#28A745','#FFC107'],
            borderColor: '#FFFFFF',
        }]
    };
    
    const configDonut = {
        type: 'doughnut',
        data: dataDonut,
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Status Distribution',
                }
            }
        }
    };
    
    const ctxDonut = document.getElementById('donutChart').getContext('2d');
    const donutChart = new Chart(ctxDonut, configDonut);
</script>