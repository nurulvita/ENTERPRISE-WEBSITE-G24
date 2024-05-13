document.getElementById('additem').addEventListener('click', function() {
    console.log('Adding new item');

    // Get table element
    var table = document.querySelector('.item-table');

    // Create a new row
    var newRow = table.insertRow();

    // Create cells for the new row
    var deleteCell = newRow.insertCell(0);
    var descriptionCell = newRow.insertCell(1);
    var rateCell = newRow.insertCell(2);
    var qtyCell = newRow.insertCell(3);
    var amountCell = newRow.insertCell(4);

    // Fill cells with content
    deleteCell.innerHTML = '<td class="delete-item-row">' +
        '<ul class="table-controls">' +
        '<li><a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="Delete">' +
        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle">' +
        '<circle cx="12" cy="12" r="10"></circle>' +
        '<line x1="15" y1="9" x2="9" y2="15"></line>' +
        '<line x1="9" y1="9" x2="15" y2="15"></line>' +
        '</svg></a></li></ul></td>';

    descriptionCell.innerHTML = '<td class="description">' +
        '<select class="form-control form-control-sm product-select" name="product-select">' +
        '<?php echo $options; ?>' +
        '</select></td>';

    rateCell.innerHTML = '<td class="rate">' +
        '<input type="text" id="price" class="form-control form-control-sm price" readonly>' +
        '</td>';

    qtyCell.innerHTML = '<td class="text-right qty">' +
        '<input type="text" class="form-control form-control-sm quantity" placeholder="Quantity">' +
        '</td>';

    amountCell.innerHTML = '<td class="text-right amount">' +
        '<span class="editable-amount"></span> ' +
        '<span class="total-amount"></span>' +
        '</td>';

    // Attach event listener to new delete button
    deleteItemRow();
});
