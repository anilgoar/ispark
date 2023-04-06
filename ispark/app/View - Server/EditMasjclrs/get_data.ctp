<?php ?>


			<div class="box-content">
			
				<h4 class="page-header">Employee Data </h4>
                                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">
				<table class = "table table-striped table-bordered table-hover table-heading no-border-bottom" id="table_id">
                <thead>
                	<tr>                	
                		<th>S. No.</th>
                    	<th>Offered Latter No.</th>
                    	<th>Employee Name</th>
                        <th>Employee Type</th>
                       
                    	<th>Father/Husband name</th>
                         <th>DOB</th>
                         <th>Designation</th>
                    	<th>Department</th>
                    	
                        <th>Offered CTC	</th>
                        <th colspan="2"></th>
                        <th colspan="2"></th>                        
                	</tr>
				</thead>
                <tbody>
                <?php $i =1; $case=array('');
              // print_r($Jclr);die;
					 foreach($masJclr as $post):
//print_r($post);die;
					 echo "<tr class=\"".$case[$i%4]."\">";
					 	echo "<td>".$i++."</td>";
                                                echo "<td>".$post['mas_jclr']['Id']."</td>";
						echo "<td>".$post['mas_jclr']['EmpName']."</td>";
						echo "<td>".$post['mas_jclr']['EmpType']."</td>";
						echo "<td>".$post['mas_jclr']['FatherName']."</td>";
                                                echo "<td>".$post['mas_jclr']['DOB']."</td>";
                                                echo "<td>".$post['mas_jclr']['Designation']."</td>";
						echo "<td>".$post['mas_jclr']['Dept']."</td>";
						
                                                echo "<td>".$post['mas_jclr']['CTCOffered']."</td>";
                                                echo "<td><code>".$this->Html->link('Edit',array('controller'=>'Masjclrs','action'=>'editjclr','?'=>array('id'=>$post['mas_jclr']['Id']),'full_base' => true))."</code></td>";
                                                echo "<td><code>".$this->Html->link('JCLR Entry',array('controller'=>'Masjclrs','action'=>'newjclr','?'=>array('id'=>$post['mas_jclr']['Id']),'full_base' => true))."</code></td>";
                                                 echo "<td><code>".$this->Html->link('Delete',array('controller'=>'Masjclrs','action'=>'deleteemp','?'=>array('id'=>$post['mas_jclr']['Id']),'full_base' => true))."</code></td>";
                                                echo "<td>Print</td>";
                                               
					 echo "</tr>";
					 endforeach;
				?>
                </tbody>
				</table>
		
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
//    $(document).ready(function () {
//        $('#table_id').dataTable();
//        
//    });
//    $(document).on('click', '.actionCost', function(){var postdata = $(this).attr('class'); 
//            var postdataArray=postdata.split(" ");var cost_id = postdataArray[2]; var cost_status = postdataArray[1];$.ajax({type:"Post",cache:false,url: "disable_cost",
//                data:{cost_id:cost_id,cost_status:cost_status}, success: function(data){alert(data); }});});
        
        
        
        function myFunction() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  
  table = document.getElementById("table_id");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) >=0) {
         // alert(td.innerHTML.toUpperCase().indexOf(filter));
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
</script>