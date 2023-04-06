<?php
//print_r($data);
foreach($data as $d)
{
    $query = "";
    foreach($d as $k=>$v)
    {
        if(!empty($query))
        {
            $query .="&";
        }
        $query .="$k=$v";
    }
    //header("http://bpsmis.ind.in/RevenueLink.aspx?".$query);
    //echo "http://bpsmis.ind.in/RevenueLink.aspx?".$query;
    echo "<script>
    window.open('http://bpsmis.ind.in/RevenueLink.aspx?$query', '_blank');
</script>";
}