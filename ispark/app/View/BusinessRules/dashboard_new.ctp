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
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

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
                    <form action="" method="post">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-3">
                            <label>Locations</label>
                            <?php echo $this->Form->input('branch',array('label' => false,'options'=>$branchName,'class'=>'form-control','id'=>'branch','onchange'=>"this.form.submit()")); ?>
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-3">
                            <label>Department</label>
                            <?php echo $this->Form->input('department',array('label' => false,'options'=>$department,'class'=>'form-control','id'=>'department','onchange'=>"this.form.submit()")); ?>
                           
                        </div>
                        <div class="col-sm-2"></div>
                    </form>
                </div>

                <div class="form-group">
                    <div class="col-sm-3">
                        <canvas id="intent_by_category" ></canvas>
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3">
                        <canvas id="intent_by_community" ></canvas>
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3">
                        <canvas id="intent_by_mascare" ></canvas>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3">
                        <canvas id="ticket_status" ></canvas>
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3">
                        <canvas id="ticket_by_department"></canvas>
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3">
                        <canvas id="close_ticket_by_department"></canvas>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3">
                        <canvas id="open_ticket_by_department"></canvas>
                    </div>
                    
                </div>

            </div>
        </div>
      </div>
   </div>
</div>


<script>

    var label1 = ['Mas Care', 'Gratitude', 'Community'];
    var data = {
        labels: label1,
        datasets: [{
            data: [<?php echo $mas_care_count; ?>, <?php echo $gratitude_count; ?>, <?php echo $community_count; ?>],
            backgroundColor: ['#33FF57', '#cc33ff', '#3399ff'],
        }],
    };

    var ctx = document.getElementById('intent_by_category').getContext('2d');

    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: {
            plugins: {
                datalabels: {
                        display: true,
                        color: '#ffffff', 
                        font: {
                            size: 14, 
                            weight: 'bold' 
                        },
                        formatter: function (value, context) {
                            var label = label1[context.dataIndex]; 
                            return value > 0 ? label + ': ' + value : ''; 
                        },
                        filter: {
                            enabled: true, 
                            function: function(value, index, values) {
                                return value > 0; 
                            }
                        }
                    }
            },
            datalabels: {
                color: '#ffffff', 
                font: {
                    size: 14, 
                    weight: 'bold' 
                },
                formatter: function(value, context) {
                    return value + ' Tickets'; 
                }
            }
          
        },
        plugins:[ChartDataLabels]
    });
</script>


<script>

    var departmentData = <?php echo $intent_community_dep_count; ?>;
    var departmentLabels = <?php echo $intent_community_dep_label; ?>;
 
    var data = {
        labels: departmentLabels,
        datasets: [{
            data: departmentData,
            backgroundColor: ['#3399ff','#ff3399', '#33cc33','#ffff00','#ff3300'],
        }],
    };

    // Get the canvas element
    var ctx = document.getElementById('intent_by_community').getContext('2d');

    // Create the pie chart
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: {
            plugins: {
                    title: {
                        display: true,
                        text: 'INTENTS BY COMMUNITY',
                        position: 'bottom',
                        font: {
                            size: 14
                        }
                    },
                   
                    datalabels: {
                        display: true,
                        color: '#ffffff', // Set the color of the data labels
                        font: {
                            size: 12, // Set the font size of the data labels
                            weight: 'bold' // Set the font weight of the data labels
                        },
                        formatter: function (value, context) {
                            var label = departmentLabels[context.dataIndex];
                            return value > 0 ? label + ': ' + value : ''; 
                        },
                        filter: {
                            enabled: true, // Enable the filter
                            function: function(value, index, values) {
                                return value > 0; 
                            }
                        }
                    }
                    
                },
                datalabels: {
                    color: '#ffffff', // Set the color of the data labels
                    font: {
                        size: 12, // Set the font size of the data labels
                        weight: 'bold' // Set the font weight of the data labels
                    },
                    formatter: function (value, context) {
                        return value > 0 ? value + ' Tickets' : ''; // Display label only if value is greater than 0
                    },
                    filter: {
                        enabled: true, // Enable the filter
                        function: function(value, index, values) {
                            return value > 0; // Display label only if value is greater than 0
                        }
                    }
                }
            },
            plugins:[ChartDataLabels]
    });
    

</script>

<script>

    var MascareData = <?php echo $mascare_dep_count; ?>;
    var MascareLabels = <?php echo $mascare_dep_label; ?>;
 
    var data = {
        labels: MascareLabels,
        datasets: [{
            data: MascareData,
            backgroundColor: ['#33FF57','#cc33ff', '#3399ff'],
        }],
    };
    // Get the canvas element
    var ctx = document.getElementById('intent_by_mascare').getContext('2d');

    // Create the pie chart
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'TOTAL MAS CARE TICKETS',
                    position: 'bottom',
                    font: {
                        size: 12
                    }
                },
                datalabels: {
                        display: true,
                        color: '#ffffff', 
                        font: {
                            size: 12, 
                            weight: 'bold' 
                        },
                        formatter: function (value, context) {
                            var label = MascareLabels[context.dataIndex]; 
                            return value > 0 ? label + ': ' + value : ''; 
                        },
                        filter: {
                            enabled: true, 
                            function: function(value, index, values) {
                                return value > 0; 
                            }
                        }
                    }
            }
        },
        plugins:[ChartDataLabels]
    });
    

</script>

<script>
 
    var TicketData = <?php echo $ticket_count; ?>;
    var TicketLabels = <?php echo $ticket_status; ?>;
 
    var data = {
        labels: TicketLabels,
        datasets: [{
            data: TicketData,
            backgroundColor: ['#3366ff','#cc0000'],
        }],
    };

  // Get the canvas element
  var ctx = document.getElementById('ticket_status').getContext('2d');

  // Create the pie chart
  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: data,
    options: {
        plugins: {
            title: {
                display: true,
                text: 'TICKETS STATUS',
                position: 'bottom',
                font: {
                    size: 12
                }
            },
            datalabels: {
                display: true,
                color: '#ffffff', 
                font: {
                    size: 12, 
                    weight: 'bold' 
                },
                formatter: function (value, context) {
                    var label = TicketLabels[context.dataIndex]; 
                    return value > 0 ? label + ': ' + value : ''; 
                },
                filter: {
                    enabled: true, 
                    function: function(value, index, values) {
                        return value > 0; 
                    }
                }
            }
        }
    },
    plugins:[ChartDataLabels]
  });

</script>



<script>
    const ctx2 = document.getElementById('ticket_by_department').getContext('2d');

    const departmentNames = <?php echo json_encode($departmentNames); ?>;
    const ticketCounts = <?php echo json_encode($ticketCounts); ?>;
    const backgroundColors = ['#99ff66','#ff6699','#66ccff','#ffff4d','#ff33cc','#ffad33'];

    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: departmentNames,
            datasets: [{
                data: ticketCounts,
                backgroundColor: backgroundColors,
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'MAS CARE TICKETS BY DEPARTMENT',
                    position: 'bottom',
                    font: {
                        size: 12
                    }
                },
                datalabels: {
                    display: true,
                    color: '#000000', 
                    font: {
                        size: 12, 
                        weight: 'bold' 
                    },
                    formatter: function (value, context) {
                        var label = departmentNames[context.dataIndex]; 
                        return value > 0 ? label + ': ' + value : ''; 
                    },
                    filter: {
                        enabled: true, 
                        function: function(value, index, values) {
                            return value > 0; 
                        }
                    }
                }
            }
        },
        plugins:[ChartDataLabels]
    });
</script>

<script>
    const ctx3 = document.getElementById('close_ticket_by_department').getContext('2d');

    const cdepartmentNames = <?php echo json_encode($cdepartmentNames); ?>;
    const cticketCounts = <?php echo json_encode($cticketCounts); ?>;


    new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: cdepartmentNames,
            datasets: [{
                data: cticketCounts,
                backgroundColor: ['#99ff66','#ff6699','#66ccff','#ffff4d','#ff33cc','#ffad33'],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'MAS CARE CLOSE TICKETS BY DEPARTMENT',
                    position: 'bottom',
                    font: {
                        size: 12
                    }
                },
                datalabels: {
                    display: true,
                    color: '#000000', 
                    font: {
                        size: 12, 
                        weight: 'bold' 
                    },
                    formatter: function (value, context) {
                        var label = cdepartmentNames[context.dataIndex]; 
                        return value > 0 ? label + ': ' + value : ''; 
                    },
                    filter: {
                        enabled: true, 
                        function: function(value, index, values) {
                            return value > 0; 
                        }
                    }
                }
            }
        },
        plugins:[ChartDataLabels]
    });
</script>

<script>
    const ctx4 = document.getElementById('open_ticket_by_department').getContext('2d');

    const odepartmentNames = <?php echo json_encode($odepartmentNames); ?>;
    const oticketCounts = <?php echo json_encode($oticketCounts); ?>;


    new Chart(ctx4, {
        type: 'doughnut',
        data: {
            labels: odepartmentNames,
            datasets: [{
                data: oticketCounts,
                backgroundColor: ['#99ff66','#ff6699','#66ccff','#ffff4d','#ff33cc','#ffad33'],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'MAS CARE OPEN TICKETS BY DEPARTMENT',
                    position: 'bottom',
                    font: {
                        size: 12
                    }
                },
                datalabels: {
                    display: true,
                    color: '#000000', 
                    font: {
                        size: 12, 
                        weight: 'bold' 
                    },
                    formatter: function (value, context) {
                        var label = odepartmentNames[context.dataIndex]; 
                        return value > 0 ? label + ': ' + value : ''; 
                    },
                    filter: {
                        enabled: true, 
                        function: function(value, index, values) {
                            return value > 0; 
                        }
                    }
                }
            }
        },
        plugins:[ChartDataLabels]
    });
</script>


