<script>
function checkAllBox()
{
    if($("#checkAll").prop('checked'))
    $('input:checkbox').add().prop('checked','checked');
    else
     $('input:checkbox').add().prop('checked',false);   
}

function getGrnNos()
{
    var CompId = $('#CompanyId').val();
    var FinanceYear = $('#FinanceYear').val();
    var FinanceMonth = $('#FinanceMonth').val();
    var GrnNo = $('#grn_no').val();
    
    $.post("get_grn_no",
            {
             CompId: CompId,
             FinanceYear:FinanceYear,
             FinanceMonth:FinanceMonth,
             GrnNo:GrnNo
            },
            function(data,status)
            {
                $("#grnNoIds").empty();
                $("#grnNoIds").html(data);
            });
}

</script>
<style>
    .textClass{ text-shadow: 5px 5px 5px #5a8db6;}
</style>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left">
        </ol>
        <div id="social" class="pull-right">
                <a href="#"><i class="fa fa-google-plus"></i></a>
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
                <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>


<div class="box-content" style="background-color:#ffffff">
    <h4 class="page-header textClass">Pending Grn No</h4>
    <?php
        if(!empty($data))
        {
            echo "<table>";
            echo "<tr>";
                echo "<th>GRN No."."</th>";
            echo "</tr>";
            foreach($data as $d)
            {
                echo "<tr>";
                    echo "<td>".$d['em']['GrnNo']."</td>";
                echo "</tr>";
            }
        }
    
    ?>
        <div class="clearfix"></div>
</div>