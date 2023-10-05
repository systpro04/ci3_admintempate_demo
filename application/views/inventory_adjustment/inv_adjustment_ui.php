<style>
    .bounce {border: none; cursor: pointer;animation: bounce 1.2s infinite;}
    .bounce:hover {animation: none;}
    #border-bottom{ border-bottom: 3px solid #00d300; }
    @keyframes bounce {
    0%,
    100% {transform: translateY(0);}
    50% {transform: translateY(-3px);}}
    #page-wrap { padding: 0 15px; min-height: 568px; }
    @media(min-width:768px) {
        #page-wrap { position: inherit; margin: 0 0 0 250px; padding: 0 30px;}
    }    
    table td, table tr, table th, thead{
        background: transparent !important;
    }
    select {
        appearance:none;
        background: #eeebeb;
        border: 0 !important;
        flex: 1;
        padding: 0 .2em;
        color:#000000;
        cursor:pointer;
    }
    select::-ms-expand {
        display: none;
    }
    .select {
        position: relative;
        display: flex;
        align-items: center;
        width: 20em;
        height: 2.5em;
        line-height: 3;
        margin-left: 2.5px;
        background: #00ffaa;
        overflow: hidden;
        border-radius: .25em;
    }
    .select::after {
        content: '\25BC';
        position: absolute;
        top: 0;
        right: 0;
        padding: 0 1em;
        background: #d4d4d4;
        cursor:pointer;
        pointer-events:none;
        transition:.25s all ease;
    }
    .select:hover::after {
        color: #23b42f;
    }

</style>
<div class="content-wrapper" id="content-wrapper" >
    <div class="container-fluid ">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary mt-2 card-outline shadow" style="background-color: rgba(245, 245, 245, 0.57)"  >
                    <div class="card-header bg-dark">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#inventory" role="tab">
                                    <i class="fa fa-align-right"></i>&nbsp;&nbsp; Adjustments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#adjust" role="tab">
                                    <i class="fa fa-file"></i>&nbsp;&nbsp; History Adjustments
                                    <span class="badge badge-danger" id="record"></span>
                                </a>
                            </li>
                            <li>
                                <div class="select">
                                    <select id="group" class="selectpicker" aria-label="Choose Group Name">
                                        <option selected disabled>Choose Group Name</option>
                                        <option value="1">Corporate</option>
                                        <option value="2">Alturas Mall</option>
                                        <option value="3">Plaza Marcela</option>
                                        <option value="4">Island City Mall</option>
                                        <option value="6">Alturas Talibon</option>
                                        <option value="7">Alturas Tubigon</option>
                                        <option value="8">Central Distribution Center</option>
                                        <option value="11">Ubay Distribution Center</option>
                                        <option value="12">Cortes Construction</option>
                                        <option value="13">Ubay Dressing Plant</option>
                                    </select>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="inventory" role="tabpanel">
                                <table class="table table-striped shadow table-hover" id="adjustTable" width="100%">
                                    <thead class="bg-primary text-center">
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Design Name</th>
                                            <th>Category Type</th>
                                            <th>Item Size</th>
                                            <th>Gender Type</th>
                                            <th>Quantity</th>
                                            <th>Batch Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="adjust" role="tabpanel">
                                <table class="table table-striped shadow table-hover" id="adjustHistTable" width="100%">
                                    <thead class="bg-primary text-center">
                                        <tr>
                                            <th><i class="fas fa-folder"></i></th>
                                            <th>Item Code</th>
                                            <th>Design Name</th>
                                            <th>Item Size</th>
                                            <th>Quantity</th>
                                            <th>New Quantity</th>
                                            <th>Batch Quantity</th>
                                            <th>New B-Quantity</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap.min.css"></script>
<!--****************************ADJUST DATATABLE***********************  -->
<script>
$(document).ready(function() {
    let select_group = JSON.parse(localStorage.getItem("select_group_id")) || { group_id: 1 };
    let select_groupId = select_group.group_id;
    $('#group').val(select_groupId);

    var adjustTable = $('#adjustTable').DataTable({
        serverSide: true,
        processing: true,
        stateSave: true,
        scrollCollapse: true,
        scrollY: '50vh',
        scrollX: true,
        lengthMenu: [ [10, 25, 50, 100, 10000], [10, 25, 50, 100, "Max"] ],
        pageLength: 10,
        ajax: {
            url: "<?php echo base_url('get_inventory_data'); ?>",
            type: "POST",
            data: function(d) {
                d.group_id = select_group.group_id;
            },
        },
        columns: [
            { data: "item_code" },
            { data: "design_name" },
            { data: "cat_type" },
            { data: "item_size" },
            { data: "gender_status" },
            { data: "quantity" },
            { data: "b_remain_qty" },
            { data: null,
                orderable: false,
                render: (data, type, row) => `<button type="button" class="btn btn-sm btn-success inventory_modal" data-item-code="${row.inv_id}" style="cursor:progress"><i class="fa fa-check"></i></button>`
            }
        ],
        columnDefs: [
            { targets: "_all", className: "text-center" },
        ],
    });

    $('#group').on('change', function() {
        select_group.group_id = $(this).val();
        select_group.group_name = $('#group option:selected').text();
        localStorage.setItem("select_group_id", JSON.stringify(select_group));
        adjustTable.draw(false);
        historyTable.draw(false);
    });

    $('#adjustTable tbody').on('click', 'button.inventory_modal', function() {
        var row = adjustTable.row($(this).closest('tr')).data();
        $('#inv_id').val(row.inv_id);
        $('#batch_id').val(row.batch_id);
        $('#item_code').val(row.item_code);
        $('#cat_type').val(row.cat_type);
        $('#item_type').val(row.item_type);
        $('#design_name').val(row.design_name);
        $('#item_size').val(row.item_size);
        $('#gender_status').val(row.gender_status);
        $('#quantity').val(row.quantity);
        $('#b_remain_qty').val(row.b_remain_qty);
        $('#adj_new_quantity').val('');
        $('#adj_new_batchqty').val('');
        $('#adj_new_reason').val('');
        $('#adj_new_date').val('');

        $('#myModal').modal('show');
    });

    function addTooltip(element, message) {
        element.tooltip({
            title: message,
            placement: 'top',
            trigger: 'manual'
        });
    }

    function validateForm() {
        var fields = ['adj_new_quantity', 'adj_new_batchqty', 'adj_new_reason', 'adj_new_date'];
        let isValid = true;
        fields.forEach(field => {
            var $has_error = $(`#${field}`);
            var fieldValue = $has_error.val().trim();

            if (fieldValue === '') {
                $has_error.parent().addClass('has-error');
                addTooltip($has_error, 'Please fill in this required field...');
                $has_error.tooltip('show');
                isValid = false;
            } else {
                $has_error.tooltip('hide');
                $has_error.parent().removeClass('has-error');
            }
        });
        return isValid;
    }

    $('#adj_new_quantity, #adj_new_batchqty, #adj_new_reason, #adj_new_date').on('input', function () {
        var $this = $(this);
        if ($this.val().trim() !== '') {
            $this.tooltip('hide');
            
        }
    });

    $('#saveChangesBtn').on('click', () => {
        if (!validateForm()) {
            return;
        }
        var select_group_Id = $('#group').val();
        var data = {
            adj_new_itemcode: $('#item_code').val(),
            adj_new_cat_type: $('#cat_type').val(),
            adj_new_itemtype: $('#item_type').val(),
            adj_new_design_name: $('#design_name').val(),
            adj_new_itemsize: $('#item_size').val(),
            adj_new_gender_status: $('#gender_status').val(),
            adj_new_date: $('#adj_new_date').val(),
            adj_old_quantity: $('#quantity').val(),
            adj_new_quantity: $('#adj_new_quantity').val(),
            adj_old_batchqty: $('#b_remain_qty').val(),
            adj_new_batchqty: $('#adj_new_batchqty').val(),
            adj_new_reason: $('#adj_new_reason').val(),
            adj_new_status: 'PENDING',
            adj_new_groupid: select_group_Id,

            inv_id: $('#inv_id').val(),
            batch_id: $('#batch_id').val(),
        };
        $.ajax({
            url: "<?php echo base_url('update_data'); ?>",
            type: "POST",
            data: data,
            dataType: 'json',
            success: (response) => {
                if (response.success) {
                    adjustTable.draw(false);
                    historyTable.draw(false);
                    $('#myModal').modal('hide');
                }
                Swal.fire({
                    icon: 'success',
                    toast: true,
                    title: 'Adjustment successfully updated.',
                    showConfirmButton: false,
                    timer: 2000,
                    position: 'top-end',
                    timerProgressBar: true,
                    width: 'auto',
                    height: '50px',
                    padding: '2em',
                });
            },
        });
    });

    //****************************ADJUST DATATABLE*********************** //

    var historyTable = $('#adjustHistTable').DataTable({
        serverSide: true,
        responsive: true,
        processing: true,
        stateSave: true,
        scrollCollapse: true,
        scrollY: '50vh',
        scrollX: true,
        lengthMenu: [ [10, 25, 50, 100, 10000], [10, 25, 50, 100, "Max"] ],
        pageLength: 10,
        ajax: {
            url: "<?php echo base_url('get_adjustment_history'); ?>",
            type: "POST",
            data: d => {
                d.group_id = select_group.group_id;
            },
        },
        columns: [
            {   data: null,
                orderable: false,
                render: (data, type, row) =>  {
                    return '<span class="badge badge-success history_modal" style="cursor: pointer;" data-id="' + data.adj_new_id + '"><i class="fa fa-info"></i></span>';
                }
            },
            { data: "adj_new_itemcode" },
            { data: "adj_new_design_name" },
            { data: "adj_new_itemsize" },
            { data: "adj_old_quantity" },
            { data: "adj_new_quantity" },
            { data: "adj_old_batchqty" },    
            { data: "adj_new_batchqty" },
            { data: "adj_new_status",
                render: function(data, type, row) {
                    switch (data) {
                        case 'PENDING':
                            return '<div class="bounce"><span class="badge badge-danger">'+ data + '</span></div>';
                        case 'APPROVED':
                            return '<span class="badge badge-success">'+ data + '</span>';
                        case 'DISAPPROVED':
                            return '<span class="badge badge-primary">'+ data + '</span>';
                        default:
                            return data;
                    }
                }
            },
            { data: null,
                orderable: false,
                render: function(data, type, row) {
                    return '<button type="button" class="btn btn-sm btn-success btn-approve" style="margin-right: 3px;" data-id="' + data.adj_new_id + '"><i class="fa fa-thumbs-up"></i></button>' +
                        '<button type="button" class="btn btn-sm btn-danger btn-disapprove" data-id="' + data.adj_new_id + '"><i class="fa fa-thumbs-down"></i></button>';
                       
                }
            }
        ],
        columnDefs: [
            { targets: "_all", className: "text-center" }
        ],
    });

    $('#adjustHistTable tbody').on('click', '.btn-approve, .btn-disapprove', function() {
        var adj_new_id = $(this).data('id');
        var status = $(this).hasClass('btn-approve') ? 'APPROVED' : 'DISAPPROVED';
        var confirmButtonColor = status === 'APPROVED' ? '#28a745' : '#dc3545';
        Swal.fire({
            icon: 'question',
            title: 'Confirmation!!!',
            text: 'Are you sure you want to ' + (status === 'APPROVED' ? 'approve' : 'disapprove') + ' the adjustment?',
            showCancelButton: true,
            confirmButtonText: 'Yes, ' + status,
            confirmButtonColor: confirmButtonColor,
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                updateStatus(adj_new_id, status);
            }
        });
    });

    function updateStatus(adj_new_id, status) {
        var currentPage = historyTable.page();
        $.ajax({
            url: '<?php echo base_url('update_status'); ?>',
            type: 'POST',
            dataType: 'json',
            data: { adj_new_id, adj_new_status: status },
            success: (response) => {
                if (response.success) {
                    historyTable.page(currentPage).draw('page');
                }
                Swal.fire({
                    icon: response.success ? 'success' : 'error',
                    toast: true,
                    title: response.success ? 'Status changed successfully' : 'Error while updating status!!!',
                    showConfirmButton: false,
                    timer: 2000,
                    position: 'top-end',
                    timerProgressBar: true,
                    width: 'auto',
                    height: '50px',
                    padding: '2em',
                });
            },
        });
    }

    $('#adjustHistTable tbody').on('click', 'span.history_modal', function() {
        var row = historyTable.row($(this).closest('tr')).data();
        var adj_new_id = row.adj_new_id;
        $('#adj_new_groupid').val(row.adj_new_groupid);
        $('#adj_new_itemcode').val(row.adj_new_itemcode);
        $('#adj_new_cat_type').val(row.adj_new_cat_type);
        $('#adj_new_itemtype').val(row.adj_new_itemtype);
        $('#adj_new_design_name').val(row.adj_new_design_name);
        $('#adj_new_itemsize').val(row.adj_new_itemsize);
        $('#adj_old_quantity').val(row.adj_old_quantity);
        $('#adj_old_batchqty').val(row.adj_old_batchqty);
        $('#adj_new_gender_status').val(row.adj_new_gender_status);
        $('#newquantity').val(row.adj_new_quantity);
        $('#batchqty').val(row.adj_new_batchqty);
        $('#date').val(row.adj_new_date);
        $('#status').val(row.adj_new_status);
        $('#reason').val(row.adj_new_reason);

        $('.status-button').data('id', adj_new_id);
        $('#historyModal').modal('show');
    });

    $('.status-button').on('click', function () {
        var adj_new_id = $(this).data('id');
        if (adj_new_id) {
            var adj_new_status = $(this).data('status');
            update_status_modal(adj_new_id, adj_new_status);
        }
    });

    function update_status_modal(adj_new_id, adj_new_status) {
        if (!adj_new_id) {
            return;
        }
        var data = {
            adj_new_id: adj_new_id,
            adj_new_status: adj_new_status,
        };
        $.ajax({
            url: '<?php echo base_url('update_status'); ?>',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: (response) => {
                if (response.success) {
                    $('#adj_new_status').val(adj_new_status);
                    $('#historyModal').modal('hide');
                    historyTable.draw(false);
                }
                Swal.fire({
                    icon: response.success ? 'success' : 'error',
                    toast: true,
                    title: response.success ? 'Status changed successfully' : 'Error while updating status',
                    showConfirmButton: false,
                    timer: 2000,
                    position: 'top-end',
                    timerProgressBar: true,
                    width: 'auto',
                    height: '50px',
                    padding: '2em',
                });
            },
        });
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var active_tab = $(e.target).attr("href");
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    historyTable.on('draw', function () {
        var pageInfo = historyTable.page.info();
        var totalCount = pageInfo.recordsTotal;
        $('#record').text(totalCount);
    });
});
</script>