

 <?php      
                            if(!empty($Data))
        {                 
                                ?><div style="overflow: scroll;width: 100%;">
        <table class="table table-hover table-bordered" border="1">
            <tr>
                <th >Date</th>
                        <th >Status</th>     
                       
                       
                        <th >Budget</th>
                        
                       
            </tr>
            <?php
            foreach ($Data as $d)
            {
                echo'<tr>';
            echo "<td>".$d['BooksManager']['date']."</td>";
            echo "<td>".$d['BooksManager']['Status']."</td>";
            echo "<td>".$d['BooksManager']['month']."</td>";
           
             echo'</tr>';
            }
         ?>


        </table>
                                </div>

        <?php } ?>
    
 