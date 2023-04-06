<table   id="table_id" border="2">
<?php  $i=1; ?>
    <thead>
        <tr><th colspan="4" align="center" onclick="print_grn()"><font color="blue"><u>Print</u></font></th></tr>
        <tr><th colspan="4" align="center"><?php echo $dis['0']['dis']['EnvelopeName']; ?></th></tr>
        <tr><th colspan="4"  align="center"><?php echo $dis['0']['bm1']['branch_name'].' To '.$dis['0']['bm2']['branch_name'] ;  ?></th></tr>
    <tr class="active">
        <td align="center"><b>Sr. No.</b></td>
        <td align="center"><b>GRN No</b></td>
        <td align="center"><b>Expense Head</b></td>
        <td align="center"><b>Expense Sub Head</b></td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($grn as $post): ?>
    <tr>
            <td align="center"><?php echo $i++; ?></td>
            <td align="center"><?php echo $post['eem']['GrnNo']; ?></td>
            <td align="center"><?php echo $post['head']['HeadingDesc']; ?></td>
            <td align="center"><?php echo $post['subhead']['SubHeadingDesc']; ?></td>
    </tr>
    <?php endforeach; ?>
    <?php unset($grn); ?>
    </tbody>
</table>
<script>
function print_grn() {
    window.print();
}
</script>