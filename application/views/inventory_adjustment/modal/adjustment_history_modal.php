<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><i class="fa fa-check"></i> Approved Adjustment</h4>
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
                                            <label for="adj_new_itemcode">Item Code :</label>
                                            <input type="text" class="form-control" id="adj_new_itemcode" name="adj_new_itemcode" readonly>
                                            <input type="hidden" class="form-control" id="adj_new_groupid" name="adj_new_groupid" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="adj_new_cat_type">Category Type :</label>
                                            <input type="text" class="form-control" id="adj_new_cat_type" name="adj_new_cat_type" readonly>
                                            <input type="hidden" class="form-control" id="adj_new_status" name="adj_new_status" readonly>
                                            <input type="hidden" class="form-control" id="adj_new_itemtype" name="adj_new_itemtype" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="adj_new_design_name">Uniform Design :</label>
                                            <input type="text" class="form-control" id="adj_new_design_name" name="adj_new_design_name" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="adj_new_itemsize">Uniform Size :</label>
                                            <input type="text" class="form-control" id="adj_new_itemsize" name="adj_new_itemsize" readonly>
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
                                            <label for="adj_new_quantity">Old Stock :</label>
                                            <input type="number" class="form-control" id="adj_old_quantity" name="adj_old_quantity" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="adj_new_batchqty">Old Batch Stock :</label>
                                            <input type="number" class="form-control" id="adj_old_batchqty" name="adj_old_batchqty" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="adj_new_gender_status">Gender Type :</label>
                                            <input type="text" class="form-control" id="adj_new_gender_status" name="adj_new_gender_status" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="reason">Reason :</label>
                                            <textarea class="form-control" id="reason" name="adj_new_reason" style="height: 38px; max-width: 256px;" readonly></textarea>
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
                                            <label for="newquantity">New Stock :</label>
                                            <input type="number" class="form-control" id="newquantity" name="adj_new_quantity" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="batchqty">New Batch Stock :</label>
                                            <input type="number" class="form-control" id="batchqty" name="adj_new_batchqty" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="date">Adjust Date :</label>
                                            <input type="datetime-local" class="form-control" id="date" name="adj_new_date" readonly>
                                        </div>                                                   
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success btn-sm status-button" data-status="APPROVED"><i class="fa fa-thumbs-up"></i>&nbsp; Approved</button>
                <button class="btn btn-primary btn-sm  status-button" data-status="DISAPPROVED"><i class="fa fa-thumbs-down"></i>&nbsp; Disapproved</button>
                <button class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
            </div>
        </div>
    </div>
</div>