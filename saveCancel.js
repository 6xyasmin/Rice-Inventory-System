function confirmSave() {
    var userConfirmed = confirm('Are you sure you want to save the rice?');
    if (userConfirmed) {
        return true; 
    }
    return false;
}

function confirmCancel() {
    var userConfirmed = confirm('Are you sure you want to cancel?');
    if (userConfirmed) {
        closeModal(); 
    }
}
