
<style>
    .selected-row { background-color: rgba(29, 150, 255, 0.55) !important; }
    .nav-item { background-color: rgba(170, 170, 170, 0.31); }
    .nav-link { color: black; }
    #border-bottom{
      border-bottom: 4px solid #00d300;
    }
</style>

<div id="page-wrapper" style="padding: 3px;">
    <div class="row" style="margin-left: auto;">
        <div class="panel-heading" style="margin-top: 5%;">
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
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 5px;">
                <div class="table-responsive">
                    <table class="display" id="inventoryTable" style="width: 100%;">
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
</div>
<script>
$(document).ready(function() {
    
    let selectedGroup = JSON.parse(localStorage.getItem("selected_group")) || {};
    const dataTable = $('#inventoryTable').DataTable({
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
            const selectOption = $('<select class="selectpicker"></select>');
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
        const row = dataTable.row($(this).closest('tr')).data();
        
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
        const fields = ['adj_new_quantity', 'adj_new_batchqty', 'adj_new_reason', 'adj_new_date'];
        let isValid = true;

        fields.forEach(field => {
            const $fieldElement = $(`#${field}`);
            const fieldValue = $fieldElement.val().trim();

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
        const $this = $(this);
        if ($this.val().trim() !== '') {
            $this.tooltip('hide');
        }
    });

    $('#saveChangesBtn').on('click', () => {
        if (!validateForm()) {
            return;
        }
        const groupSelect = $('#inventoryTable_filter').find('select.selectpicker');
        const selectedGroupId = groupSelect.val();
        const data = {
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
</script>

