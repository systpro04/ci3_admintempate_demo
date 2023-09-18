
<style>
    .selected-row { background-color: rgba(29, 150, 255, 0.55) !important; }
    .nav-item {background-color: rgba(170, 170, 170, 0.31);}
    .nav-link {color: black;}
    .bounce-button {border: none; cursor: pointer;animation: bounce 1.2s infinite;}
    .bounce-button:hover {animation: none;}
    #border-bottom{
      border-bottom: 4px solid #00d300;
    }
    @keyframes bounce {
    0%,
    100% {transform: translateY(0);}
    50% {transform: translateY(-3px);}}
</style>

<div id="page-wrapper" style="padding: 3px;">
    <div class="row" style="margin-left: auto;">
        <div class="panel-heading" style="margin-top: 5%;">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('data_view'); ?>"><i class="fa fa-align-right"></i>&nbsp;&nbsp; Adjustments</a>
                </li>
                <li class="active ">
                    <a class="nav-link" href="<?php echo base_url('history_view'); ?>"><i class="fa fa-file"></i>&nbsp;&nbsp; History Adjustments</span></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 5px;">
                <div class="table-responsive">
                    <table class="display" id="adjustTable" style="width: 100%">
                        <thead>
                            <tr>
                                <th id="border-bottom" style="text-align: center">Item Code</th>
                                <th id="border-bottom" style="text-align: center">Design Name</th>
                                <th id="border-bottom" style="text-align: center">Item Size</th>
                                <th id="border-bottom" style="text-align: center">New Stock</th>
                                <th id="border-bottom" style="text-align: center">New Batch Stock</th>
                                <th id="border-bottom" style="text-align: center">Date Adjust</th>
                                <th id="border-bottom" style="text-align: center">Reason</th>
                                <th id="border-bottom" style="text-align: center">Status</th>
                                <th id="border-bottom" style="text-align: center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    let selectedGroup = JSON.parse(localStorage.getItem("selected_group")) || {};
    const dataTable = $('#adjustTable').DataTable({
        deferRender: true,
        serverSide: true,
        stateSave: true,
        responsive: true,
        scrollCollapse: true,
        scrollY: '50vh',
        scrollX: true,
        order: [],
        pagingType: 'full_numbers',
        lengthMenu: [ [5, 10, 25, 50, 100, 10000], [5, 10, 25, 50, 100, "Max"] ],
        pageLength: 5,
        ajax: {
            url: "<?php echo base_url('get_adjustment_history'); ?>",
            type: "POST",
            data: d => {
                d.group_id = selectedGroup.group_id;
            },
        },
        columns: [
            { data: "adj_new_itemcode" },
            { data: "adj_new_design_name" },
            { data: "adj_new_itemsize" },
            { data: "adj_new_quantity" },
            { data: "adj_new_batchqty" },    
            { data: "adj_new_date",
                render: function(data, type, row) {
                if (type === 'display' || type === 'filter') {
                    const dateTime = new Date(data);
                    const dateformat = ('0' + (dateTime.getMonth() + 1)).slice(-2) + '/' + (
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
            { data: "adj_new_reason"},
            {
                data: "adj_new_status",
                render: function(data, type, row) {
                    return getStatusColor(data);
                }
            },
            {
                data: null,
                render: (data, type, row) => `<button class="btn btn-sm btn-circle btn-success history_modal" data-adj-new-id="${row.adj_new_id}"><i class="fa fa-check"></i></button>`
            },
        ],
        columnDefs: [
            {
            targets: "_all",
            className: "text-center"
        }
    ],
        initComplete: (settings, json) => {
            
            const selectOption = $('<select class="selectpicker"></select>');
            selectOption.html(json.selectOptions);

            selectOption.val(selectedGroup.group_id);
            selectOption.css({
                'font-size': '13px',
                'width': 'auto',
                'height': '31px',
                'margin-left': '10px'
            });

            selectOption.appendTo($('#adjustTable_filter'));
            selectOption.on('change', function() {
                selectedGroup.group_id = selectOption.val();
                selectedGroup.group_name = selectOption.find('option:selected').text();
                localStorage.setItem("selected_group", JSON.stringify(selectedGroup));
                dataTable.ajax.reload();
            });
        },
    });

    function getStatusColor(adj_new_status) {
        switch (adj_new_status) {
            case 'PENDING':
                return '<span style="background-color: rgb(240, 76, 76);"  class="badge badge-light bounce-button">' +
                    adj_new_status + '</span>';
            case 'APPROVED':
                return '<span style="background-color: #49a349;" class="badge badge-light">' + adj_new_status +
                    '</span>';
            case 'DISAPPROVED':
                return '<span style="background-color: rgb(56, 133, 248);" class="badge badge-light">' +
                    adj_new_status + '</span>';
            default:
                return adj_new_status;
        }
    }
    
    $('#group, #adjustTable tbody').on('change', () => {
        selectedGroup.group_id = $('#group').val();
        selectedGroup.group_name = $('#group option:selected').text();
        localStorage.setItem("selected_group", JSON.stringify(selectedGroup));
        dataTable.ajax.reload();
    });

    $('#adjustTable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected-row')) {
            $(this).removeClass('selected-row');
        } else {
            dataTable.$('tr.selected-row').removeClass('selected-row');
            $(this).addClass('selected-row');
        }
    });

    $('.status-button').on('click', function () {
        updateStatus($(this).data('status'));
    });

    $('#adjustTable tbody').on('click', 'button.history_modal', function() {
        const row = dataTable.row($(this).closest('tr')).data();
        $('#adj_new_itemcode').val(row.adj_new_itemcode);
        $('#adj_new_reason').val(row.adj_new_reason);
        $('#adj_new_cat_type').val(row.adj_new_cat_type);
        $('#adj_new_itemtype').val(row.adj_new_itemtype);
        $('#adj_new_design_name').val(row.adj_new_design_name);
        $('#adj_new_itemsize').val(row.adj_new_itemsize);
        $('#adj_old_quantity').val(row.adj_old_quantity);
        $('#adj_old_batchqty').val(row.adj_old_batchqty);
        $('#adj_new_gender_status').val(row.adj_new_gender_status);
        $('#adj_new_quantity').val(row.adj_new_quantity);
        $('#adj_new_batchqty').val(row.adj_new_batchqty);
        $('#adj_new_date').val(row.adj_new_date);
        $('#adj_new_status').val(row.adj_new_status);
        $('#adj_new_groupid').val(row.adj_new_groupid);

        $('#historyModal').modal('show');
    });

    function updateStatus(adj_new_status) {
        const adj_new_id = $('.selected-row').find('button.history_modal').data('adj-new-id');
        if (!adj_new_id) {
            return;
        }
        const data = {
            adj_new_id: adj_new_id,
            adj_new_status: adj_new_status,
        };
        $.ajax({
            url: '<?php echo base_url('update_status'); ?>',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: (response) => {
                let footerMessage = '';
                let icon = '';
                if (response.success) {
                    if (adj_new_status === 'APPROVED') {
                        footerMessage = 'Adjustment Approved!!!';
                    } else if (adj_new_status === 'DISAPPROVED') {
                        footerMessage = 'Adjustment Disapproved!!!';
                    }
                    const dataTable = $('#adjustTable').DataTable();
                    const selectedRow = $('.selected-row').closest('tr');
                    const rowData = dataTable.row(selectedRow).data();
                    rowData.adj_new_status = adj_new_status;
                    dataTable.row(selectedRow).data(rowData).draw(false);
                    $('#historyModal').modal('hide');
                } else {
                    icon = 'error';
                    footerMessage = 'Error updating status. Please try again.';
                }
                Swal.fire({
                    icon: 'success',
                    toast: true,
                    title: 'Status changed successfully',
                    footer: `<a>${footerMessage}</a>`,
                    showConfirmButton: false,
                    timer: 1500,
                    position: 'top-end',
                    timerProgressBar: true
                });
            },
        });
    }

});
</script>