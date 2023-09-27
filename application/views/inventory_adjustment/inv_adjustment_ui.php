<!-- <style>
    .selected-row { background-color: rgba(29, 150, 255, 0.55) !important; }
    .nav-link { color: black; }
    #border-bottom{
      border-bottom: 4px solid #00d300;
    }
</style>

<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills">
                <li class="active">
                    <a class="nav-link" href="<?php echo base_url('data_view'); ?>"><i class="fa fa-align-right"></i>&nbsp;&nbsp; Adjustments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('history_view'); ?>"><i class="fa fa-file"></i>&nbsp;&nbsp; History Adjustments </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="inventoryTable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th id="border-bottom" style="text-align: center">Item Code</th>
                            <th id="border-bottom" style="text-align: center">Design Name</th>
                            <th id="border-bottom" style="text-align: center">Category Type</th>
                            <th id="border-bottom" style="text-align: center">Item Size</th>
                            <th id="border-bottom" style="text-align: center">Gender Type</th>
                            <th id="border-bottom" style="text-align: center">Stock</th>
                            <th id="border-bottom" style="text-align: center">Batch Stock</th>
                            <th id="border-bottom" style="text-align: center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    
    let selectedGroup = JSON.parse(localStorage.getItem("selected_group")) || {group_id: 1};
    var dataTable = $('#inventoryTable').DataTable({
        deferRender: true,
        serverSide: true,
        stateSave: true,
        scrollCollapse: true,
        scrollY: '50vh',
        scrollX: true,
        order: [],
        pagingType: 'full_numbers',
        lengthMenu: [ [5, 10, 25, 50, 100, 10000], [5, 10, 25, 50, 100, "Max"] ],
        pageLength: 5,
        language: {
            emptyTable: "No matching records found",
        },
        ajax: {
            url: "<?php echo base_url('get_inventory_data'); ?>",
            type: "POST",
            data: function(d) {
                d.group_id = selectedGroup.group_id;
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
                render: (data, type, row) => `<button class="btn btn-sm btn-circle btn-success inventory-modal" data-item-code="${row.item_code}"><i class="fa fa-check"></i></button>`
            }
        ],
        columnDefs: [{
            targets: "_all",
            className: "text-center",
        }],

        initComplete: (settings, json) => {
            var selectOption = $('<select class="selectpicker"></select>');
            selectOption.html(json.selectOptions);

            selectOption.val(selectedGroup.group_id);
            selectOption.css({
                'font-size': '13px',
                'width': 'auto',
                'height': '31px',
                'margin-left': '10px'
            });

            selectOption.appendTo($('#inventoryTable_filter'));
            selectOption.on('change', function () {
                selectedGroup.group_id = selectOption.val();
                selectedGroup.group_name = selectOption.find('option:selected').text();
                localStorage.setItem("selected_group", JSON.stringify(selectedGroup));
                dataTable.ajax.reload();
            });
        },
    })

    $('#group, #inventoryTable tbody').on('change', () => {
        selectedGroup.group_id = $('#group').val();
        selectedGroup.group_name = $('#group option:selected').text();
        localStorage.setItem("selected_group", JSON.stringify(selectedGroup));
        dataTable.ajax.reload();
    });

    $('#inventoryTable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected-row')) {
            $(this).removeClass('selected-row');
        } else {
            dataTable.$('tr.selected-row').removeClass('selected-row');
            $(this).addClass('selected-row');
        }
    });

    $('#inventoryTable tbody').on('click', 'button.inventory-modal', function() {
        var row = dataTable.row($(this).closest('tr')).data();
        
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
            var $fieldElement = $(`#${field}`);
            var fieldValue = $fieldElement.val().trim();

            if (fieldValue === '') {
                addTooltip($fieldElement, 'Please fill in this field...');
                $fieldElement.tooltip('show');
                isValid = false;
            } else {
                $fieldElement.tooltip('hide');
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
        var groupSelect = $('#inventoryTable_filter').find('select.selectpicker');
        var selectedGroupId = groupSelect.val();
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
            adj_new_groupid: selectedGroupId,
        };
        $.ajax({
            url: "<?php echo base_url('update_data'); ?>",
            type: "POST",
            data: data,
            dataType: 'json',
            success: (response) => {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        toast: true,
                        title: 'Adjustment successfully updated.',
                        footer: '<a>Adjustment is ready for approval!</a>',
                        showConfirmButton: false,
			            timer: 1500,
                        position: 'top-end',
                        timerProgressBar: true
                    }).then(() => {
                        $('#myModal').modal('hide');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        toast: true,
                        title: 'Error updating adjustment. Please try again.',
                        footer: '<a>Data not save!</a>',
                        showConfirmButton: false,
			            timer: 1500,
                        position: 'top-end',
                        timerProgressBar: true
                    });
                }
            },
        });
    });
});
</script> -->


<!-- <script>
    $(document).ready(function() {
    let selectedGroup = JSON.parse(localStorage.getItem("selected_group")) || { group_id: 1 };
    var loaderTimeout;
    var adjustTable = $('#adjustTable').DataTable({
        deferRender: true,
        serverSide: true,
        // stateSave: true,
        language: {
            "processing": '<div id="loader"><i class="fa fa-spinner fa-spin" style="font-size: 4rem;color:rgb(95, 245, 75);"></i></div>'
        },
        processing: true,
        scrollCollapse: true,
        scrollY: '65vh',
        scrollX: true,
        order: [],
        pagingType: 'full_numbers',
        lengthMenu: [ [10, 25, 50, 100, 10000], [10, 25, 50, 100, "Max"] ],
        pageLength: 10,
        ajax: {
            url: "<?php echo base_url('get_inventory_data'); ?>",
            type: "POST",
            data: function(d) {
                d.group_id = selectedGroup.group_id;
            },
            beforeSend: function () {
                $('#loader').show();
                loaderTimeout = setTimeout(function () {
                    $('#loader').hide();
                },200);
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
                render: (data, type, row) => `<button class="btn btn-sm btn-circle btn-success inventory-modal" data-item-code="${row.item_code}"><i class="fa fa-check"></i></button>`
            }
        ],
        columnDefs: [{
            targets: "_all",
            className: "text-center",
        }],
        initComplete: (settings, json) => {
            var selectOption = $('<select class="selectpicker"></select>');
            selectOption.html(json.selectOptions);

            selectOption.val(selectedGroup.group_id);
            selectOption.css({
                'font-size': '13px',
                'width': 'auto',
                'height': '31px',
                'margin-left': '10px'
            });

            selectOption.appendTo($('#adjustTable_filter'));
            selectOption.on('change', function () {
                selectedGroup.group_id = selectOption.val();
                selectedGroup.group_name = selectOption.find('option:selected').text();
                localStorage.setItem("selected_group", JSON.stringify(selectedGroup));
                adjustTable.ajax.reload();
            });
        },
    })

    $('#group, #adjustTable tbody').on('change', () => {
        selectedGroup.group_id = $('#group').val();
        selectedGroup.group_name = $('#group option:selected').text();
        localStorage.setItem("selected_group", JSON.stringify(selectedGroup));
        adjustTable.ajax.reload();
    });

    $('#adjustTable tbody').on('click', 'button.inventory-modal', function() {
        var row = adjustTable.row($(this).closest('tr')).data();
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
        var groupSelect = $('#adjustTable_filter').find('select.selectpicker');
        var selectedGroupId = groupSelect.val();
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
            adj_new_groupid: selectedGroupId,
        };
        $.ajax({
            url: "<?php echo base_url('update_data'); ?>",
            type: "POST",
            data: data,
            dataType: 'json',
            success: (response) => {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        toast: true,
                        title: 'Adjustment successfully updated.',
                        footer: '<a>Adjustment is ready for approval!</a>',
                        showConfirmButton: false,
			            timer: 1500,
                        position: 'top-end',
                        timerProgressBar: true,
                        width: '300px',
                        height: '50px',
                        padding: '2em',
                    }).then(() => {
                        $('#myModal').modal('hide');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        toast: true,
                        title: 'Error updating adjustment. Please try again.',
                        footer: '<a>Data not save!</a>',
                        showConfirmButton: false,
			            timer: 1500,
                        position: 'top-end',
                        timerProgressBar: true,
                        width: '300px',
                        height: '50px',
                        padding: '2em',
                    });
                }
            },
        });
    });
});
</script> -->


<!-- <div id="page-wrap">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default"  style="margin-top: 5%;">
                <div class="panel-heading" style="background-color: transparent">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="active"><a class="nav-link" href="#inventory" role="tab" data-toggle="tab"><i class="fa fa-align-right icon"></i>&nbsp;&nbsp; Adjustments</a></li>
                        <li class="nav-item"><a class="nav-link" href="#adjust" role="tab" data-toggle="tab"><i class="fa fa-file icon"></i>&nbsp;&nbsp; History Adjustments</a></li>
                    </ul>
                </div>
                <div class="col-md-4" style="margin-top: 10px;">
                    <div class="form-group">
                        <select class="form-control" id="group">
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
                </div>
                <div class="panel-body">
                    <div class="tab-content pt-2">
                        <div class="tab-pane fade in active" id="inventory">
                            <table class="table table-striped table-bordered" id="adjustTable" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">Item Code</th>
                                        <th style="text-align: center">Design Name</th>
                                        <th style="text-align: center">Category Type</th>
                                        <th style="text-align: center">Item Size</th>
                                        <th style="text-align: center">Gender Type</th>
                                        <th style="text-align: center">Stock</th>
                                        <th style="text-align: center">Batch Stock</th>
                                        <th style="text-align: center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="adjust">
                            <table class="table table-striped table-bordered" id="adjustHistTable" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">Item Code</th>
                                        <th style="text-align: center">Design Name</th>
                                        <th style="text-align: center">Item Size</th>
                                        <th style="text-align: center">Old Stock</th>
                                        <th style="text-align: center">New Stock</th>
                                        <th style="text-align: center">Old Batch Stock</th>
                                        <th style="text-align: center">New Batch Stock</th>
                                        <th style="text-align: center">Status</th>
                                        <th style="text-align: center">Date Adjust</th>
                                        <th style="text-align: center; width: 10%;">Action</th>
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
<script>
$(document).ready(function () {
    let selectedGroup = JSON.parse(localStorage.getItem("selected_group")) || { group_id: 1 };
    var loaderTimeout;
    var adjustTable = $('#adjustTable').DataTable({
        deferRender: true,
        serverSide: true,
        language: {
            "processing": '<div id="loader"><i class="fa fa-spinner fa-spin" style="font-size: 4rem;color:rgb(95, 245, 75);"></i></div>'
        },
        processing: true,
        scrollCollapse: true,
        scrollY: '65vh',
        scrollX: true,
        order: [],
        pagingType: 'full_numbers',
        lengthMenu: [[10, 25, 50, 100, 10000], [10, 25, 50, 100, "Max"]],
        pageLength: 10,
        ajax: {
            url: "<?php echo base_url('get_inventory_data'); ?>",
            type: "POST",
            data: function (d) {
                d.group_id = selectedGroup.group_id;
            },
            beforeSend: function () {
                $('#loader').show();
                loaderTimeout = setTimeout(function () {
                    $('#loader').hide();
                }, 200);
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
            {
                data: null,
                orderable: false,
                render: (data, type, row) => `<button class="btn btn-sm btn-circle btn-success inventory-modal" data-item-code="${row.item_code}"><i class="fa fa-check"></i></button>`
            }
        ],
        columnDefs: [{
            targets: "_all",
            className: "text-center",
        }],
    });

    $('#group').on('change', () => {
        selectedGroup.group_id = $('#group').val();
        selectedGroup.group_name = $('#group option:selected').text();
        localStorage.setItem("selected_group", JSON.stringify(selectedGroup));
        adjustTable.ajax.reload();

        historyTable.ajax.reload();

    });


    $('#adjustTable tbody').on('click', 'button.inventory-modal', function() {
        var row = adjustTable.row($(this).closest('tr')).data();
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
        var groupSelect = $('#adjustTable_filter').find('select.selectpicker');
        var selectedGroupId = groupSelect.val();
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
            adj_new_groupid: selectedGroupId,
        };
        $.ajax({
            url: "<?php echo base_url('update_data'); ?>",
            type: "POST",
            data: data,
            dataType: 'json',
            success: (response) => {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        toast: true,
                        title: 'Adjustment successfully updated.',
                        showConfirmButton: false,
			            timer: 1500,
                        position: 'top-end',
                        timerProgressBar: true,
                        width: '300px',
                        height: '50px',
                        padding: '2em',
                    }).then(() => {
                        $('#myModal').modal('hide');
                    historyTable.ajax.reload();

                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        toast: true,
                        title: 'Error updating adjustment. Please try again.',
                        showConfirmButton: false,
			            timer: 1500,
                        position: 'top-end',
                        timerProgressBar: true,
                        width: '300px',
                        height: '50px',
                        padding: '2em',
                    });
                }
            },
        });
    });

    var historyTable = $('#adjustHistTable').DataTable({
        deferRender: true,
        serverSide: true,
        stateSave: true,
        language: {
            "processing": '<div id="loader"><i class="fa fa-spinner fa-spin" style="font-size: 4rem;color:rgb(95, 245, 75);"></i></div>'
        },
        processing: true,
        responsive: true,
        scrollCollapse: true,
        scrollY: '65vh',
        scrollX: true,
        order: [],
        pagingType: 'full_numbers',
        lengthMenu: [ [10, 25, 50, 100, 10000], [10, 25, 50, 100, "Max"] ],
        pageLength: 10,
        ajax: {
            url: "<?php echo base_url('get_adjustment_history'); ?>",
            type: "POST",
            data: d => {
                d.group_id = selectedGroup.group_id;
            },
            beforeSend: function () {
                $('#loader').show();
                loaderTimeout = setTimeout(function () {
                    $('#loader').hide();
                }, 200);
            },
        },
        columns: [
            { data: "adj_new_itemcode" },
            { data: "adj_new_design_name" },
            { data: "adj_new_itemsize" },
            { data: "adj_old_quantity" },
            { data: "adj_new_quantity" },
            { data: "adj_old_batchqty" },    
            { data: "adj_new_batchqty" },   
            {
                data: "adj_new_status",
                render: function(data, type, row) {
                    return get_status_color(data);
                }
            }, 
            { data: "adj_new_date",
                render: function(data, type, row) {
                if (type === 'display' || type === 'filter') {
                    var dateTime = new Date(data);
                    var dateformat = ('0' + (dateTime.getMonth() + 1)).slice(-2) + '/' + (
                            '0' + dateTime.getDate()).slice(-2) + '/' + dateTime
                        .toLocaleString('default', {
                            year: '2-digit'
                        }).slice(-2) + ' ' + dateTime.toLocaleTimeString([], {
                            hour: 'numeric',
                            minute: '2-digit'
                        });
                        return dateformat;
                    }
                    return data;
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return '<button class="btn btn-circle btn-sm btn-success btn-approve" style="margin-right: 4px" data-id="' + data.adj_new_id + '"><i class="fa fa-thumbs-up"></i></button>' +
                        '<button class="btn btn-circle btn-sm btn-danger btn-disapprove" data-id="' + data.adj_new_id + '"><i class="fa fa-thumbs-down"></i></button>';
                }
            }
        ],
        columnDefs: [{
            targets: "_all",
            className: "text-center",
        }],
    });
    
    function get_status_color(adj_new_status) {
        switch (adj_new_status) {
            case 'PENDING':
                return '<div class="bounce"> <span class="label label-danger">'+ adj_new_status + '</span> </div>';
            case 'APPROVED':
                return '<span class="label label-success">'+ adj_new_status + '</span>';
            case 'DISAPPROVED':
                return '<span class="label label-primary">'+ adj_new_status + '</span>';
            default:
                return adj_new_status;
        }
    }

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
        var data = {
            adj_new_id: adj_new_id,
            adj_new_status: status
        };
        $.ajax({
            url: '<?php echo base_url('update_status'); ?>',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(response) {
                displayNotification(response.success ? 'Status changed successfully' : 'Error while updating status!!!', response.success);
                if (response.success) {
                    historyTable.ajax.reload();
                }
            },
        });
    }
    function displayNotification(message, success) {
        Swal.fire({
            icon: success ? 'success' : 'error',
            toast: true,
            title: message,
            showConfirmButton: false,
            timer: 1500,
            position: 'top-end',
            timerProgressBar: true,
            width: '300px',
            height: '50px',
            padding: '2em',
        });
    }
});
</script> -->



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
    #content-wrapper
    {
        background-image: url(<?php echo base_url('assets/img/background.jpg') ?>);
        background-attachment: fixed;
        background-repeat: no-repeat;
        background-position: top left;
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
                <div class="card mt-5" style="background-color: rgba(245, 245, 245, 0.57)"  >
                    <div class="card-header bg-dark">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#inventory" role="tab">
                                    <i class="fa fa-align-right"></i>&nbsp;&nbsp; Adjustments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#adjust" role="tab">
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
                                <table class="table table-striped table-bordered shadow table-hover" id="adjustTable" width="100%">
                                    <thead class="bg-primary text-center">
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Design Name</th>
                                            <th>Category Type</th>
                                            <th>Item Size</th>
                                            <th>Gender Type</th>
                                            <th>Stock</th>
                                            <th>Batch Stock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="adjust" role="tabpanel">
                                <table class="table table-striped table-bordered shadow table-hover" id="adjustHistTable" width="100%">
                                    <thead class="bg-primary text-center">
                                        <tr>
                                            <th></th>
                                            <th>Item Code</th>
                                            <th>Design Name</th>
                                            <th>Item Size</th>
                                            <th>Stock</th>
                                            <th>New Stock</th>
                                            <th>Batch Stock</th>
                                            <th>New B-Stock</th>
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

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<!--****************************ADJUST DATATABLE***********************  -->
<script type="text/javascript">
$(document).ready(function() {
    let select_group = JSON.parse(localStorage.getItem("select_group_id")) || { group_id: 1 };
    let select_groupId = select_group.group_id;
    $('#group').val(select_groupId);

    var adjustTable = $('#adjustTable').DataTable({
        serverSide: true,
        responsive: true,
        stateSave: true,
        pagingType: 'full_numbers',
        lengthMenu: [ [5, 10, 25, 50, 100, 10000], [5, 10, 25, 50, 100, "Max"] ],
        pageLength: 5,
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
                render: (data, type, row) => `<button type="button" class="btn btn-sm btn-success inventory-modal" data-item-code="${row.item_code}"><i class="fa fa-check"></i></button>`
            }
        ],
        columnDefs: [{
            targets: "_all",
            className: "text-center",
        }],
    })

    $('#group').on('change', function() {
        select_group.group_id = $(this).val();
        select_group.group_name = $('#group option:selected').text();
        localStorage.setItem("select_group_id", JSON.stringify(select_group));
        adjustTable.ajax.reload();
        historyTable.ajax.reload();
    });

    $('#adjustTable tbody').on('click', 'button.inventory-modal', function() {
        var row = adjustTable.row($(this).closest('tr')).data();
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
                $has_error.parent().addClass('is-invalid');
                addTooltip($has_error, 'Please fill in this required field...');
                $has_error.tooltip('show');
                isValid = false;
            } else {
                $has_error.tooltip('hide');
                $has_error.parent().removeClass('is-invalid');
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
        const select_group_Id = $('#group').val();

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
        };
        $.ajax({
            url: "<?php echo base_url('update_data'); ?>",
            type: "POST",
            data: data,
            dataType: 'json',
            success: (response) => {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        toast: true,
                        title: 'Adjustment successfully updated.',
                        showConfirmButton: false,
			            timer: 1500,
                        position: 'top-end',
                        timerProgressBar: true,
                    }).then(() => {
                        historyTable.ajax.reload();
                        $('#myModal').modal('hide');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        toast: true,
                        title: 'Error updating adjustment. Please try again.',
                        showConfirmButton: false,
			            timer: 1500,
                        position: 'top-end',
                        timerProgressBar: true,
                    });
                }
            },
        });
    });

    //****************************ADJUST DATATABLE*********************** //

    var historyTable = $('#adjustHistTable').DataTable({
        serverSide: true,
        responsive: true,
        stateSave: true,
        pagingType: 'full_numbers',
        lengthMenu: [ [5, 10, 25, 50, 100, 10000], [5, 10, 25, 50, 100, "Max"] ],
        pageLength: 5,
        ajax: {
            url: "<?php echo base_url('get_adjustment_history'); ?>",
            type: "POST",
            data: d => {
                d.group_id = select_group.group_id;
            },
        },
        columns: [
            {   data: null,
                render: (data, type, row) =>  {
                    return '<span class="badge badge-success history_modal" style="margin-left: 3px" data-id="' + data.adj_new_id + '"><i class="fa fa-info"></i></span>';
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
                            return '<div class="bounce"> <span class="badge badge-danger">'+ data + '</span> </div>';
                        case 'APPROVED':
                            return '<span class="badge badge-success">'+ data + '</span>';
                        case 'DISAPPROVED':
                            return '<span class="badge badge-primary">'+ data + '</span>';
                        default:
                            return data;
                    }
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return '<button type="button" class="btn btn-sm btn-success btn-approve" style="margin-right: 3px" data-id="' + data.adj_new_id + '"><i class="fa fa-thumbs-up"></i></button>' +
                        '<button type="button" class="btn btn-sm btn-danger btn-disapprove" data-id="' + data.adj_new_id + '"><i class="fa fa-thumbs-down"></i></button>';
                       
                }
            }
        ],
        columnDefs: [{
            targets: "_all",
            className: "text-center"
        }],
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
            success: function(response) {
                Swal.fire({
                    icon: response.success ? 'success' : 'error',
                    toast: true,
                    title: response.success ? 'Status changed successfully' : 'Error while updating status!!!',
                    showConfirmButton: false,
                    timer: 1500,
                    position: 'top-end',
                    timerProgressBar: true,
                });
                if (response.success) {
                    historyTable.page(currentPage).draw('page');
                }
            },
        });
    }

    $('#adjustHistTable tbody').on('click', 'span.history_modal', function() {
        var row = historyTable.row($(this).closest('tr')).data();
        var adj_new_id = row.adj_new_id;
        $('#adj_new_groupid').val(row.adj_new_groupid);
        $('#adj_new_itemcode').val(row.adj_new_itemcode);
        $('#reason').val(row.adj_new_reason);
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
                    historyTable.ajax.reload();
                }
                Swal.fire({
                    icon: response.success ? 'success' : 'error',
                    toast: true,
                    title: response.success ? 'Status changed successfully' : 'Error while updating status',
                    showConfirmButton: false,
                    timer: 1500,
                    position: 'top-end',
                    timerProgressBar: true,
                });
            },
        });
    }

    function organizedTabs(active_tab, table_id, dataTable) {
        if (active_tab === `#${table_id}`) {
            if ($.fn.DataTable.isDataTable(`#${table_id}`)) {
                $(`#${table_id}`).DataTable().destroy();
                dataTable.ajax.reload();
            }
        }
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var active_tab = $(e.target).attr("href");

        organizedTabs(active_tab, 'adjustTable', adjustTable);
        organizedTabs(active_tab, 'adjustHistTable', historyTable);
    });

    historyTable.on('draw', function () {
        var pageInfo = historyTable.page.info();
        var totalCount = pageInfo.recordsTotal;
        $('#record').text(totalCount);
    });
});
</script>