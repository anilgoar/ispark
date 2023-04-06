<?php
    ob_start();
    $DirectoryNo="check";
    $track = $_GET['DataId'];
    include(dirname(__FILE__).'/res/example000.php');
    $content = ob_get_clean();
    require_once(dirname(__FILE__).'/../html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($DirectoryNo.'.pdf');
    }
  catch(HTML2PDF_exception $e) { echo $e;  exit; }
  /*
  header("Content-Type: application/pdf; name='excel'");
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=".$DirectoryNo.".pdf");
  header("Pragma: no-cache");
  header("Expires: 0");
  */
  exit;
?>