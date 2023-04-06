
                    <label class="col-sm-2 control-label">Group</label>
                    <div class="col-sm-3">
                        <select name="Group" id="EmpType" class="form-control"  >
                            <option value="">Select</option>
                            <?php foreach($branch_data as $bc){ ?>
                               <option value="<?php echo $bc['Actdata']['Group'] ?>" ><?php echo $bc['Actdata']['Group'] ?></option>
                          <?php   } ?>
                        </select>
                    