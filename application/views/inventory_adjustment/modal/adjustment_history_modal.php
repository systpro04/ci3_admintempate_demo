
<script src="<?php echo base_url('assets/js/jquery3-5-1.js'); ?>"></script>

<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-check"></i> Approved Adjustment</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box" style="padding: 0px;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-4 col-xs-4">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
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
                                    <div class="col-sm-4 col-xs-4">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
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
                                                        <label for="adj_new_itemtype">Item Type :</label>
                                                        <input type="text" class="form-control" id="adj_new_itemtype" name="adj_new_itemtype" readonly>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-xs-4">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                               <form>
                                                    <div class="form-group">
                                                        <label for="adj_new_quantity">New Stock :</label>
                                                        <input type="number" class="form-control" id="adj_new_quantity" name="adj_new_quantity" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="adj_new_batchqty">New Batch Stock :</label>
                                                        <input type="number" class="form-control" id="adj_new_batchqty" name="adj_new_batchqty" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="adj_new_date">Adjust Date :</label>
                                                        <input type="datetime-local" class="form-control" id="adj_new_date" name="adj_new_date" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="adj_new_reason">Reason :</label>
                                                        <textarea class="form-control" id="adj_new_reason" name="adj_new_reason" style="height: 34px; max-width: 256px;" readonly></textarea>
                                                    </div>
                                               </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-circle btn-success btn-lg status-button" data-status="APPROVED"><i class="fa fa-thumbs-up" data-toggle="tooltip" title="Approved"></i></button>
                <button class="btn btn-circle btn-primary btn-lg  status-button" data-status="DISAPPROVED"><i class="fa fa-thumbs-down" data-toggle="tooltip" title="Disapproved"></i></button>
                <button class="btn btn-circle btn-secondary btn-lg" data-dismiss="modal"><i class="fa fa-times" data-toggle="tooltip" title="Close"></i></button>
            </div>
        </div>
    </div>
</div>