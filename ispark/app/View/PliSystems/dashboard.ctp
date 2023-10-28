<?php 
   echo $this->Html->css('jquery-ui');
   echo $this->Html->script('jquery-ui');
?>
<script language="javascript">
   $(function () {
   $("#AttenDate").datepicker1({
       changeMonth: true,
       changeYear: true
   });
   });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="row">
   <div id="breadcrumb" class="col-xs-12">
      <a href="#" class="show-sidebar">
      <i class="fa fa-bars"></i>
      </a>
      <ol class="breadcrumb pull-left"></ol>
      <div id="social" class="pull-right">
         <a href="#"><i class="fa fa-google-plus"></i></a>
         <a href="#"><i class="fa fa-facebook"></i></a>
         <a href="#"><i class="fa fa-twitter"></i></a>
         <a href="#"><i class="fa fa-linkedin"></i></a>
         <a href="#"><i class="fa fa-youtube"></i></a>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-xs-12 col-sm-12">
      <div class="box">
         <div class="box-header" style="text-align: center;">
            <div class="box-name">
               <span>Dashboard</span>
            </div>
            <div class="box-icons">
               <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
               <a class="expand-link"><i class="fa fa-expand"></i></a>
               <a class="close-link"><i class="fa fa-times"></i></a>
            </div>
            <div class="no-move"></div>
         </div>
         <div class="box-content box-con">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-sm-6">
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="col-sm-6">
                        <canvas id="mytimeChart"></canvas>
                    </div>
                </div>
                <br>
                    
                   
                <div class="form-group">
                    <form action="" method="post">
                        <div class="col-sm-2">
                            <label>Metrics</label>
                            <?php $metrics = ['ALL'=>'ALL','7'=>'Last 7 days','3'=>'Last 3 days','1'=>'Last 1 days']?>
                            <?php echo $this->Form->input('metrics',array('label' => false,'options'=>$metrics,'class'=>'form-control','id'=>'metrics','onchange'=>"this.form.submit()")); ?>
                            <label>Department</label>
                            <?php echo $this->Form->input('department',array('label' => false,'options'=>$department,'class'=>'form-control','id'=>'department','onchange'=>"this.form.submit()")); ?>
                           
                        </div>
                    </form>
                    <div class="col-sm-5">
                        <label>Ticket Create</label>
                        <canvas id="tic_create"></canvas>
                    </div>
                    <div class="col-sm-5">
                        <canvas id="tic_close"></canvas>
                    </div>
                </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
    // Get the canvas element
    const ctx = document.getElementById('myChart').getContext('2d');

    const dates = <?php echo json_encode($dates); ?>;
    const counts = <?php echo json_encode($counts); ?>;

    // Create the chart
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Number of conversations last 7 Days',
                data: counts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
     const ctx1 = document.getElementById('mytimeChart').getContext('2d');

     const hourlyCounts = <?php echo json_encode($hourlyCounts); ?>

    function formatHourLabel(hour) {
        const ampm = hour < 12 ? 'AM' : 'PM';
        const h = hour % 12 || 12; // Convert 0 to 12 for 12-hour format
        const m = '00'; // Zero-padding for minutes
        return `${h}:${m} ${ampm}`;
    }

    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: [...Array(24).keys()].map(formatHourLabel),
            datasets: [{
                label: 'Conversation per Hour of a day',
                data: hourlyCounts,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>

    const ctx2 = document.getElementById('tic_create').getContext('2d');

    const departmentNames = <?php echo json_encode($departmentNames); ?>;
    const ticketCounts = <?php echo json_encode($ticketCounts) ; ?>;

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: departmentNames,
            datasets: [{
                label: 'Tickets',
                data: ticketCounts,
                backgroundColor: <?php echo json_encode($backgroundColors); ?>,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                    title: {
                        display: true,
                        text: 'Tickets by Department',
                        position: 'top',
                        font: {
                            size: 16
                        }
                    },
                    subtitle: {
                        display: true,
                        text: 'Total Tickets: <?php echo $totalCount; ?>',
                        position: 'bottom',
                        font: {
                            size: 14
                        }
                    }
                }
        }
    });
</script>

<script>
    const ctx3 = document.getElementById('tic_close').getContext('2d');
    const data3 = [5, 10, 15, 12, 20, 30, 25];

    const tic_close_dates = <?php echo json_encode($tic_close_dates); ?>;
    const tic_close_counts = <?php echo json_encode($tic_close_counts); ?>;

    const myChart3 = new Chart(ctx3, {
        type: 'line',
        data: {
            labels: tic_close_dates,
            datasets: [{
                label: 'Tickets Close',
                data: tic_close_counts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                    title: {
                        display: true,
                        text: 'Tickets Close',
                        position: 'top',
                        font: {
                            size: 16
                        }
                    },
                    subtitle: {
                        display: true,
                        text: 'Total Close Tickets: <?php echo $totalcloseCount; ?>',
                        position: 'bottom',
                        font: {
                            size: 14
                        }
                    }
                }
        }
    });
</script>

