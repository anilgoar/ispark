<?php

?>
<style type="text/css">
<!--
/* Terence Ordona, portal[AT]imaputz[DOT]com         */
/* http://creativecommons.org/licenses/by-sa/2.0/    */

/* begin some basic styling here                     */
body {
	background: #FFF;
	
	font: normal normal 12px Verdana, Geneva, Arial, Helvetica, sans-serif;
	margin: 10px;
	padding: 0
}

table, td, a {
	
	font: normal normal 12px Verdana, Geneva, Arial, Helvetica, sans-serif
}

h1 {
	font: normal normal 18px Verdana, Geneva, Arial, Helvetica, sans-serif;
	margin: 0 0 5px 0
}

h2 {
	font: normal normal 16px Verdana, Geneva, Arial, Helvetica, sans-serif;
	margin: 0 0 5px 0
}

h3 {
	font: normal normal 13px Verdana, Geneva, Arial, Helvetica, sans-serif;
	
	margin: 0 0 15px 0
}
/* end basic styling                                 */

/* define height and width of scrollable area. Add 16px to width for scrollbar          */
div.tableContainer {
	clear: both;
	height: 460px;
	overflow: auto;
	width: 140%
}

/* Reset overflow value to hidden for all non-IE browsers. */
html>body div.tableContainer {
	overflow: hidden;
	width: 100%
}

/* define width of table. IE browsers only                 */
div.tableContainer table {
	float: left;
	width: 100%
}

/* define width of table. Add 16px to width for scrollbar.           */
/* All other non-IE browsers.                                        */
html>body div.tableContainer table {
	width: 100%
}

/* set table header to a fixed position. WinIE 6.x only                                       */
/* In WinIE 6.x, any element with a position property set to relative and is a child of       */
/* an element that has an overflow property set, the relative value translates into fixed.    */
/* Ex: parent element DIV with a class of tableContainer has an overflow property set to auto */
thead.fixedHeader tr {
	position: relative
}

/* set THEAD element to have block level attributes. All other non-IE browsers            */
/* this enables overflow to work on TBODY element. All other non-IE, non-Mozilla browsers */
html>body thead.fixedHeader tr {
	display: block
}

/* make the TH elements pretty */
thead.fixedHeader th {
	
	border-left: 1px solid #EB8;
	border-right: 1px solid #B74;
	border-top: 1px solid #EB8;
	font-weight: 100%;
	padding: 4px 3px;
	text-align: left
}

/* make the A elements pretty. makes for nice clickable headers                */
thead.fixedHeader a, thead.fixedHeader a:link, thead.fixedHeader a:visited {
	
	display: block;
	text-decoration: none;
	width: 100%
}

/* make the A elements pretty. makes for nice clickable headers                */
/* WARNING: swapping the background on hover may cause problems in WinIE 6.x   */
thead.fixedHeader a:hover {
	
	display: block;
	text-decoration: underline;
	width: 100%
}

/* define the table content to be scrollable                                              */
/* set TBODY element to have block level attributes. All other non-IE browsers            */
/* this enables overflow to work on TBODY element. All other non-IE, non-Mozilla browsers */
/* induced side effect is that child TDs no longer accept width: auto                     */
html>body tbody.scrollContent {
	display: block;
	height: 400px;
	overflow: scroll;
	width: 100%
            
}

/* make TD elements pretty. Provide alternating classes for striping the table */
/* http://www.alistapart.com/articles/zebratables/                             */
tbody.scrollContent td, tbody.scrollContent tr.normalRow td {
	background: #FFF;
	border-bottom: none;
	border-left: none;
	border-right: 1px solid #CCC;
	border-top: 1px solid #DDD;
	padding: 2px 3px 3px 4px
}

tbody.scrollContent tr.alternateRow td {
	background: #EEE;
	border-bottom: none;
	border-left: none;
	border-right: 1px solid #CCC;
	border-top: 1px solid #DDD;
	padding: 2px 3px 3px 4px;
}

/* define width of TH elements: 1st, 2nd, and 3rd respectively.          */
/* Add 16px to last TH for scrollbar padding. All other non-IE browsers. */
/* http://www.w3.org/TR/REC-CSS2/selector.html#adjacent-selectors        */
html>body thead.fixedHeader th {
	width: 200px
}

html>body thead.fixedHeader th + th {
	width: 240px
}

html>body thead.fixedHeader th + th + th {
	width: 316px
}

/* define width of TD elements: 1st, 2nd, and 3rd respectively.          */
/* All other non-IE browsers.                                            */
/* http://www.w3.org/TR/REC-CSS2/selector.html#adjacent-selectors        */
html>body tbody.scrollContent td {
	width: 200px
}

html>body tbody.scrollContent td + td {
	width: 240px
}

html>body tbody.scrollContent td + td + td {
	width: 300px
}
-->
</style>


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
            <div class="box-header">
                <div class="box-name">
                    
                    <span>Prospect Report</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                
		<div class="form-group has-success has-feedback">
                 <?php echo $this->Form->create('prospects',array('class'=>'form-horizontal')); ?>   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Product Name</label>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('ProductId',array('label' => false,'class'=>'form-control','class'=>'form-control js-example-basic-single','options'=>$product_master,'empty'=>'Select')); ?>
                        </div>
                        <label class="col-sm-2 control-label">Date From</label>
                        <div class="col-sm-2">
                                <?php	
                                    echo $this->Form->input('DateFrom', array('label'=>false,'class'=>'form-control','value' => '','placeholder' => 'DateFrom','onClick'=>"displayDatePicker('data[prospects][DateFrom]')",'required'=>true));
                                ?>
                        </div> 
                        <label class="col-sm-2 control-label">Date To</label>
                        <div class="col-sm-2">
                                <?php	
                                    echo $this->Form->input('DateTo', array('label'=>false,'class'=>'form-control','value' => '','placeholder' => 'To','id'=>'DateTo','onClick'=>"displayDatePicker('data[prospects][DateTo]')",'required'=>true));
                                ?>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="col-sm-12 control-label">&nbsp;</label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-1">
                           <button class="btn btn-info btn-label-left" >Show</button>
                        </div>
                        <div class="col-sm-1">
                           <button class="btn btn-info btn-label-left" onClick="return prospect_validate('Export');">Export</button>
                        </div>
                    </div>
                    
                   <?php echo $this->Form->end(); ?> 
                    </div>
		
		<div class="clearfix"></div>
		<div class="form-group">
                    
		</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <span>Details</span>
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content" id="data" >
                <div id="tableContainer" class="tableContainer" style="overflow:scroll" >
                <table border="2" class="scrollTable" >
                    <thead>
                        <tr>
                            <th>S.No.</th>
            <th>Company</th>
            <th>Branch</th>
            <th>Product</th>
            <th>Lead Source</th>
            <th>Client Name</th>
            <th>Contact No.</th>
            <th>Email</th>
            <th>Address</th>
            <th>Introduction</th>
            <th>Email To</th>
            <th>Email CC</th>
            <th>Email Sub</th>
            <th>Lead Source</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody >
                        <?php $i=1; //print_r($data); exit;
                            foreach($data as $exp)
                            {
                                echo "<tr>";
                                    echo "<td>".$i++."</td>";
                        echo "<td>".$exp['0']['company']."</td>";
                        echo "<td>".$exp['0']['branch']."</td>";
                        echo "<td>".$exp['pp']['ProductName']."</td>";
                        echo "<td>".$exp['pls']['LeadSource']."</td>";
                        echo "<td>".'<a href="view_report_prospect_wise?Id='.$exp['pc']['Id'].'">'.$exp['pc']['ClientName']."</a></td>";
                        echo "<td>".$exp['pc']['ContactNo']."</td>";
                        echo "<td>".$exp['pc']['Email']."</td>";
                        echo "<td>".$exp['pc']['Address1']."</td>";
                        echo "<td>".$exp['pc']['Introduction']."</td>";
                        echo "<td>".$exp['pc']['EmailTo']."</td>";
                        echo "<td>".$exp['pc']['EmailCC']."</td>";
                        echo "<td>".$exp['pc']['EmailSub']."</td>";
                        echo "<td>".$exp['pls']['LeadSource']."</td>";
                        
                        
                        
                                    echo "<td>".$this->Html->link(__('PDF'), array('controller'=>'prospects','action' => 'view_pdf','?'=>array('Id'=>$exp['pc']['Id']), 'ext' => 'pdf', 'DownloadPdf'))."</td>"; 
                                echo "</tr>";
                            } 
                        ?>
                    </tbody>
                </table>   
                </div>     
            <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

