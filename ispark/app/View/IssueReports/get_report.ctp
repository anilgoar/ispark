<?php 
//print_r($data);?>

<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
					<tr align="center">
                    	<td>Ticket No.</td>
						<td>Branch Name</td>
                        <td>Process Name</td>
                        <td>Process Desc</td>
                        <td>Process Type</td>
                        <td>Requirement Desc</td>
                        <td>Requirement Type</td>
						<td>Ticket Desc</td>
                    	<td>Date</td>
                        <td>Status</td>  
					</tr>
                    <?php foreach($data1 as $post){?>
						<tr align="center">
                           <td><?php  echo $post['IssueParticular']['ticket_no'];?></td>
							<td><?php echo $post['IssueParticular']['branch_name'];  ?></td>
                            <td><?php echo $post['IssueParticular']['process_name'];  ?></td>
                            <td><?php echo $post['IssueParticular']['process_desc'];  ?></td>
                            <td><?php echo $post['IssueParticular']['process_type'];  ?></td>
                            <td><?php echo $post['IssueParticular']['requirement_desc'];  ?></td>
                            <td><?php if($post['IssueParticular']['requirment_type'] =='0'){echo "Upgrade";}?>
                                <?php if($post['IssueParticular']['requirment_type'] =='1'){echo "New";}?>
                                <?php if($post['IssueParticular']['requirment_type'] =='2'){echo "Modification";}?>
                                <?php if($post['IssueParticular']['requirment_type'] =='3'){echo "Error";}?></td>
                        	<td><?php echo $post['IssueParticular']['ticket_desc'];?></td>
							<td><?php echo $post['IssueParticular']['createdate']; ?></td>
                            <td>
								<?php if($post['IssueParticular']['issue_status'] =='1'){echo "In-progress";}?>
                                <?php if($post['IssueParticular']['issue_status'] =='2'){echo "On-hold";}?>
                                <?php if($post['IssueParticular']['issue_status'] =='0'){echo "open";}?>
                                <?php if($post['IssueParticular']['issue_status'] =='3'){echo "close";}?>
                                
                           	</td>
                        </tr>
					<?php }?>
				</table>
