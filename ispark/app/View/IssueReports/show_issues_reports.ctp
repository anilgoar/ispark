<?php 
//print_r($data);?>

<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
					<tr>
                                            <th align="center">SrNo</th>
                    	<th align="center">Ticket No.</th>
						<th align="center">Branch Name</th>
                        <th align="center">Process Name</th>

                        <th align="center">Requirement Desc</th>
                        <th align="center">Requirement Type</th>
						<th align="center">Ticket Desc</th>
                    	<th align="center">Date</th>
                        <th align="center">Status</th>
                        <!--
                          <th align="center">Submitted By</th>
                          -->
                          <th align="center">Handled By</th>
					</tr>
                    <?php $i=1; foreach($data as $post){?>
						<tr align="center">
                                                    <td><?php echo $i++;?></td>
                           <td><?php  echo $post['ui']['ticket_number'];?></td>
							<td><?php echo $post['iss']['branch_name'];  ?></td>
                            <td><?php echo $post['iss']['process_name'];  ?></td>
                        
                            <td><?php echo $post['iss']['requirement_desc'];  ?></td>
                            <td><?php if($post['iss']['requirment_type'] =='0'){echo "Upgrade";}?>
                                <?php if($post['iss']['requirment_type'] =='1'){echo "New";}?>
                                <?php if($post['iss']['requirment_type'] =='2'){echo "Modification";}?>
                                <?php if($post['iss']['requirment_type'] =='3'){echo "Error";}?></td>
                        	<td><?php echo $post['iss']['ticket_desc'];?></td>
							<td><?php echo $post['iss']['createdate']; ?></td>
                            <td>
                                <?php if($post['ui']['issue_status'] =='0'){echo "open";}?>
				<?php if($post['ui']['issue_status'] =='1'){echo "On-hold";}?>
                                <?php if($post['ui']['issue_status'] =='2'){echo "In-progress";}?>
                                <?php if($post['ui']['issue_status'] =='3'){echo "close";}?>
                                <?php if($post['ui']['issue_status'] =='4'){echo "Re-Open";}?>
                                <?php if($post['ui']['issue_status'] =='5'){echo "Reject";}?>
                           	</td>
                                <!--
                            <td><?php echo $post['iss']['submitted by'];  ?></td>
                            -->
                            <td><?php echo $post['tu']['handled by'];  ?></td>
                        </tr>
					<?php }?>
				</table>
