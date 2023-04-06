
                
    
                <div class="form-group">
                    <label class="col-sm-2 control-label">Client</label>
                    <div class="col-sm-3">
 <select name="Client" id="Client" class="form-control"  >
                        <option value="">Select</option>
                            <?php foreach($branch_data as $bc){ ?>
                               <option value="<?php echo $bc['Actdata']['Client'] ?>" ><?php echo $bc['Actdata']['Client'] ?></option>
                          <?php   } ?> </select>
                    </div>

                    <label class="col-sm-2 control-label">Project</label>
                    <div class="col-sm-3">
                       <select name="Project" id="Project" class="form-control"  >
                        <option value="">Select</option>
                            <?php foreach($branch_data as $bc){ ?>
                               <option value="<?php echo $bc['Actdata']['Project'] ?>" ><?php echo $bc['Actdata']['Project'] ?></option>
                          <?php   } ?> </select>
                    </div>
 </div>

               <div class="form-group">
                    <label class="col-sm-2 control-label">Module</label>
                    <div class="col-sm-3">
                       <select name="Module" id="Module" class="form-control"  >
                        <option value="">Select</option>
                            <?php foreach($branch_data as $bc){ ?>
                               <option value="<?php echo $bc['Actdata']['Module'] ?>" ><?php echo $bc['Actdata']['Module'] ?></option>
                          <?php   } ?> </select>
                    </div>

                    <label class="col-sm-2 control-label">Activity</label>
                    <div class="col-sm-3">
                        <select name="Activity" id="Activity" class="form-control"  >
                        <option value="">Select</option>
                            <?php foreach($branch_data as $bc){ ?>
                               <option value="<?php echo $bc['Actdata']['Activity'] ?>" ><?php echo $bc['Actdata']['Activity'] ?></option>
                          <?php   } ?> </select>
                    </div>
                 </div>
    
                
                