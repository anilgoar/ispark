<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left">
        </ol>
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
            <div class="form-horizontal">
                <div class="col-md-12">

                    <!-- Icon Cards-->
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                        <div class="inforide">
                            <div class="row">
                                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                                    <h4 class="box-content">Total No Of Active Employee in Process <span style="color:blue"> - <?php echo $active_emp['active_emp'];?></span></h4>
                                    <br>
                                    <h4 class="box-content">Total No Of Employee Absent From 4 Days <span style="color:blue"> - <?php echo $four_absent_emp['absent_emp'];?></span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                    </div>

                    <div class="col-lg-6 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                        <div class="inforide">
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-4 col-4 ridethree">
                                </div>
                                <div class="box-content col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                                    <h4>Total No OF tickets for Bio metric ID Deletion <span style="color:blue"> - <?php echo $total_dlt['bio_deletion'];?></span></h4>
                                    <br>
                                    <h4>Total No OF Tickets For AD deletion <span style="color:blue"> - <?php echo $total_dlt['ad_deletion'];?></span></h4>
                                    <br>
                                    <h4>Total No OF Tickets For BGV <span style="color:blue"> - <?php echo $total_bgv['bgv_tic'];?></span></h4>
                                    <br>
                                    <h4>Tickets Pending more than 4 hours <span style="color:blue"> - <?php echo $four_hour['four_hour'];?></span></h4>
                                    <br>
                                    <h4>Tickets Pending more than 8 Hours <span style="color:blue"> - <?php echo $eight_hour['eight_hour'];?></span></h4>
                                    <br>
                                    <h4>Tickets Pending more than 24 Hours <span style="color:blue"> - <?php echo $twentyfour_hour['twenty_four_hour'];?></span></h4>
                                    <br>
                                    <h4>Total No Email Sent to client for partner ID deletion in last 24 hours <span style="color:blue"> - 20</span></h4>
                                    <br>
                                    <h4>Total No Bio metric IDS deleted in Calendar month <span style="color:blue">- <?php echo $total_month_deletion['bio_del_month'];?></span></h4>
                                    <br>
                                    <h4>Total No AD IDS deleted in Calendar month <span style="color:blue">-<?php echo $total_month_deletion['ad_del_month'];?></span></h4>
                                    <br>
                                    <h4>Total No Email Sent to client for partner ID deletion in Calendar month <span style="color:blue">-<?php echo $total_month_deletion['partner_del_month'];?></span></h4>
                                    <br>
                                    <h4>Total No BGV Initiated in Calendar month <span style="color:blue"> - <?php echo $total_month_creation['bgv_create_month'];?></span></h4>
                                    <br>
                                    <h4>Total no. of BGV received <span style="color:blue"> - <?php echo $bgv_api['tot_bgv'];?></span></h4>
                                    <br>
                                    <h4>Total no. of BGV in Green <span style="color:blue"> - <?php echo $bgv_green['bgv_green'];?> </span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>