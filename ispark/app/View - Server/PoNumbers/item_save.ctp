<script>
    function item_total()
    {
        var qty =document.getElementById("ItemItemQty").value;
        var rate =document.getElementById("ItemItemRate").value;
        document.getElementById("ItemItemTotal").value = qty*rate;
    }
    function myItemDelete(id)
    {
        $.post("delete_item",{id:id},function(data)
    {$("#cost_center").html(data);location.reload();});
    }
    function isDecimalKey(evt)
    {
	var charCode = (evt.which) ? evt.which : event.keyCode
	
	if ((charCode> 31 && (charCode < 48 || charCode > 57)) && charCode!=46)
		return false;
	
	return true;
    }
    function validate_item()
    {
        if(document.getElementById("ItemItemName").value=='')
        {alert("Item Name is Blank");return false;}
        else if(document.getElementById("ItemItemQty").value=='')
        {
            alert("Item Qty is Blank");
            return false;
        }
        else if(document.getElementById("ItemItemRate").value=='')
        {
            alert("Item Rate is Blank");
            return false;
        }
        else if(document.getElementById("ItemItemTotal").value=='')
        {
            alert("Item Total is Blank");
            return false;
        }
        return true;
    }
</script>    
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
	
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Item Entry</span>
		</div>
            <div class="box-icons">
            	<a class="collapse-link">
		<i class="fa fa-chevron-up"></i>
		</a>
                <a class="expand-link">
		<i class="fa fa-expand"></i>
		</a>
		<a class="close-link">
		<i class="fa fa-times"></i>
		</a>
            </div>
            <div class="no-move"></div>
            </div>
            <div class="box-content">
            <?php echo $this->Form->create('Item',array('class'=>'form-horizontal')); ?>
            <table class="table table-hover table-bordered" border="1">
		<tr>
                    <th>Item</th>
                    <td>
                        <?php echo $this->Form->input('item_name',array('label'=>false,'type'=>'text','class'=>'form-control')); ?>
                    </td>
                    <th>Qty</th>
                    <td>
                        <?php echo $this->Form->input('item_qty',array('label'=>false,'type'=>'text','onKeyPress'=>'return isDecimalKey(event)','class'=>'form-control')); ?>
                    </td>
                    <th>Rate</th>
                    <td>
                        <?php echo $this->Form->input('item_rate',array('label'=>false,'type'=>'text','onKeyPress'=>'return isDecimalKey(event)','onBlur'=>"item_total()",'class'=>'form-control')); ?>
                    </td>
                    <th>Total</th>
                    <td>
                        <?php echo $this->Form->input('item_total',array('label'=>false,'type'=>'text','readonly'=>true,'class'=>'form-control')); ?>
                    </td>
                    <td><button class="btn btn-info" name="Add" value="Add" onclick="validate_item();">Add</button></td>
                </tr>
                </table>
                <table class="table table-hover table-bordered" border="1">
                <tr>
                    <th>S.No.</th>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
                <?php $i = 1;
                    foreach($data as $d)
                    {
                        echo "<tr>";
                            echo "<td>".$i++."</td>";
                            echo "<td>".$d['Item']['item_name']."</td>";
                            echo "<td>".$d['Item']['item_qty']."</td>";
                            echo "<td>".$d['Item']['item_rate']."</td>";
                            echo "<td>".$d['Item']['item_total']."</td>";
                            echo "<td><div id='id".$d['Item']['id']."' onClick=\"myItemDelete('".$d['Item']['id']."')\"><a href='#' class='btn btn-info'>Delete</a></div></td>";
                        echo "</tr>";
                        $total += $d['Item']['item_total'];
                    }
                    echo "<tr><th colspan='4'>Total</th><td>";
                    echo $this->Form->input('Total',array('label'=>false,'type'=>'text','value'=>$total,'readonly'=>true,'class'=>'form-control'));
                    echo "</td></tr>";
                ?>
                
                </table>
                <input type="submit" name="submit" value="submit" class="btn btn-info">
            <?php echo $this->Form->end(); ?>
            </div>
	</div>
    </div>
</div>