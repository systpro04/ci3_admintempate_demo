<!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><i class="fa fa-spinner fa-spin"></i> Data Adjustment :</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">                      
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <form>
                                        <div class="form-group">
                                            <label for="item_code">Item Code :</label>
                                            <input type="text" class="form-control" id="item_code" name="item_code" readonly>
                                            <input type="hidden" class="form-control" id="group_id" name="group_id" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="cat_type">Category Type :</label>
                                            <input type="text" class="form-control" id="cat_type" name="cat_type" readonly>
                                            <input type="hidden" class="form-control" id="item_type" name="item_type" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="design_name">Uniform Design :</label>
                                            <input type="text" class="form-control" id="design_name" name="design_name" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="item_size">Uniform Size :</label>
                                            <input type="text" class="form-control" id="item_size" name="item_size" readonly>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <form>
                                        <div class="form-group">
                                            <label for="quantity">Old Stock :</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="b_remain_qty">Old Batch Stock :</label>
                                            <input type="number" class="form-control" id="b_remain_qty" name="b_remain_qty" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="gender_status">Gender Type :</label>
                                            <input type="text" class="form-control" id="gender_status" name="gender_status" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="adj_new_reason">Reason :<strong style="color: red; font-size: 1rem;"> *</strong></label>
                                            <textarea class="form-control" id="adj_new_reason" name="adj_new_reason" style="height: 38px; max-width: 256px;"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <form>
                                        <div class="form-group">
                                            <label for="adj_new_quantity">New Stock :<strong style="color: red; font-size: 1rem;"> *</strong></label>
                                            <input type="number" class="form-control" id="adj_new_quantity" name="adj_new_quantity">
                                        </div>
                                        <div class="form-group">
                                            <label for="adj_new_batchqty">New Batch Stock :<strong style="color: red; font-size: 1rem;"> *</strong></label>
                                            <input type="number" class="form-control" id="adj_new_batchqty" name="adj_new_batchqty">
                                        </div>
                                        <div class="form-group">
                                            <label for="adj_new_date">Adjust Date :<strong style="color: red; font-size: 1rem;"> *</strong></label>
                                            <input type="datetime-local" class="form-control" id="adj_new_date" name="adj_new_date" autocomplete="on">
                                        </div>                                                  
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <small>Legend:</small><small style="color:red;">Required field.</small><strong style="color: red; font-size: 1rem;">*</strong>
                <button class="btn btn-success btn-sm" id="saveChangesBtn"><i class="fa fa-check"></i>&nbsp; Submit</button>
                <button class="btn btn-primary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
            </div>
        </div>
    </div>
</div> -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><i class="fa fa-spinner fa-spin"></i> Data Adjustment :</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">                      
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <form>
                                        <div class="form-group">
                                            <label for="item_code">Item Code :</label>
                                            <input type="text" class="form-control" id="item_code" name="item_code" readonly>
                                            <input type="hidden" class="form-control" id="group_id" name="group_id" readonly>
                                            <input type="hidden" id="inv_id" name="inv_id" readonly>
                                            <input type="hidden" id="batch_id" name="batch_id" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="cat_type">Category Type :</label>
                                            <input type="text" class="form-control" id="cat_type" name="cat_type" readonly>
                                            <input type="hidden" class="form-control" id="item_type" name="item_type" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="design_name">Uniform Design :</label>
                                            <input type="text" class="form-control" id="design_name" name="design_name" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="item_size">Uniform Size :</label>
                                            <input type="text" class="form-control" id="item_size" name="item_size" readonly>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <form>
                                        <div class="form-group">
                                            <label for="quantity">Old Stock :</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="b_remain_qty">Old Batch Stock :</label>
                                            <input type="number" class="form-control" id="b_remain_qty" name="b_remain_qty" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="gender_status">Gender Type :</label>
                                            <input type="text" class="form-control" id="gender_status" name="gender_status" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="adj_new_reason">Reason :<strong style="color: red; font-size: 1rem;"> *</strong></label>
                                            <textarea class="form-control" id="adj_new_reason" name="adj_new_reason" style="height: 38px; max-width: 256px;"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <form>
                                        <div class="form-group">
                                            <label for="adj_new_quantity">New Quantity :<strong style="color: red; font-size: 1rem;"> *</strong></label>
                                            <input type="number" value="0" class="form-control" id="adj_new_quantity" name="adj_new_quantity">
                                            <label>
                                                <input type="checkbox" id="copyqty" onchange="copy_qty()"> <small style="font-size: 0.7rem"><i>Same as Old Quantity?</i></small>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label for="adj_new_batchqty">New Batch Quantity :<strong style="color: red; font-size: 1rem;"> *</strong></label>
                                            <input type="number" value="0" class="form-control" id="adj_new_batchqty" name="adj_new_batchqty">
                                            <label>
                                                <input type="checkbox" id="copyRemain" onchange="copy_b_remain_qty()"> <small style="font-size: 0.7rem"><i>Same as Old Batch Quantity?</i></small>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label for="adj_new_date">Adjust Date :<strong style="color: red; font-size: 1rem;"> *</strong></label>
                                            <input type="datetime-local" class="form-control" id="adj_new_date" name="adj_new_date" autocomplete="on">
                                            <label>
                                                <input type="checkbox" id="setToday" onchange="setDateAndTime()"> <small style="font-size: 0.7rem"><i>Set Today's Date and Time</i></small>
                                            </label>
                                        </div>                                              
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <small>Legend:</small><small style="color:red;">Required field.</small><strong style="color: red; font-size: 1rem;">*</strong>
                <button class="btn btn-success btn-sm" id="saveChangesBtn"><i class="fa fa-check"></i>&nbsp; Submit</button>
                <button class="btn btn-primary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    function copy_qty() {
        const copyCheckBox = document.getElementById('copyqty');
        const oldQtyInput = document.getElementById('quantity');
        const newQtyInput = document.getElementById('adj_new_quantity');

        if (copyCheckBox.checked) {
            newQtyInput.value = oldQtyInput.value;
        } else {
            newQtyInput.value = '';
        }
    }
    
    function copy_b_remain_qty() {
        const copyCheckBox = document.getElementById('copyRemain');
        const oldBatchQtyInput = document.getElementById('b_remain_qty');
        const newBatchQtyInput = document.getElementById('adj_new_batchqty');

        if (copyCheckBox.checked) {
            newBatchQtyInput.value = oldBatchQtyInput.value;
        } else {
            newBatchQtyInput.value = '';
        }
    }

    function setDateAndTime() {
        const setTodayCheckBox = document.getElementById('setToday');
        const adjNewDateInput = document.getElementById('adj_new_date');

        if (setTodayCheckBox.checked) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const formattedDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            adjNewDateInput.value = formattedDateTime;

        } else {
            adjNewDateInput.value = '';
        }
    }
</script>