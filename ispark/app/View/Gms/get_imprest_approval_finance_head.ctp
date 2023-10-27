
    <table class="table  table-bordered table-hover table-heading no-border-bottom responstable" id="table_id">
    <?php $case=array('primary',''); $i=0; ?>
            <thead>
                <tr class="active">
                    <td>Sr. No.</td>
                    <td>User </td>
                    <td>Company</td>
                    <td>Reject At</td>
                    <td>Reject Remarks</td>
                    <td>Reject By</td>
                    <td>Amount</td>
                    <td>Approve</td>
                </tr>
            </thead>
            <tbody>
                    <?php foreach ($data as $post): ?>
                    <tr class="<?php  echo $case[$i%4]; $i++;?>">
                            <td><?php echo $i; ?></td>
                            <td><?php echo $post['tu']['username']; ?></td>
                            <td><?php echo $post['cm']['company_name']; ?></td>
                            <td><?php  if($post['eemApp']['Reject']=='2') { echo "Reject From Second Level";} else  if($post['eemApp']['Reject']=='0') { echo "Reject From First Level";} else  if($post['eemApp']['Reject']=='4') { echo "Approved From Process Head";} ?></td>
                            <td><font color="red"><?php echo $post['eemApp']['RejectRemarks']; ?></font></td>
                            <td><?php echo $post['tuR']['RejectBy']; ?></td>
                            <td><?php echo $post['eemApp']['Amount']; ?></td>
                            <?php if($post['eemApp']['Reject']=='4') { ?>
                            <td><?php echo $this->Html->link('View/Approve',array('controller'=>'Gms','action'=>'edit_imprest_approval_finance_head','?'=>array('Id'=>$post['eemApp']['Id'],'action'=>'approve'),'full_base' => true)); ?></td>
                            <?php } ?>
                            <?php if($post['eemApp']['Reject']=='0' || $post['eemApp']['Reject']=='2' || $post['eemApp']['Reject']=='3') { ?>
                            <td><?php echo $this->Html->link('Process Head Approval Waiting',array('controller'=>'Gms','action'=>'edit_imprest_approval_finance_head','?'=>array('Id'=>$post['eemApp']['Id'],'action'=>'view'),'full_base' => true)); ?></td>
                            <?php } ?>
                    </tr>
                    <?php endforeach; ?>
                    <?php unset($data); ?>
            </tbody>
    </table>
			