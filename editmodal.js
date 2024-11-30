function openEditModal(id, name, stocks, kilograms, price, expiration_date) {
    var modal = document.getElementById('editRiceModal');
    var editId = document.getElementById('editId');
    var editName = document.getElementById('editName');
    var editStocks = document.getElementById('editStocks');
    var editKilograms = document.getElementById('editKilograms');
    var editPrice = document.getElementById('editPrice');
    var editExpirationDate = document.getElementById('editExpirationDate');
    var editKilogramsSelect = editRiceModal.querySelector('select[name="kilograms"]');

    editId.value = id;
    editName.value = name;
    editStocks.value = stocks;
  
    if (editKilogramsSelect) {
        editKilogramsSelect.value = kilograms;
    }
    editPrice.value = price;
    editExpirationDate.value = expiration_date;

 
    modal.style.display = 'block';
}

function closeEditModal() {
    var modal = document.getElementById('editRiceModal');
    modal.style.display = 'none';
}
