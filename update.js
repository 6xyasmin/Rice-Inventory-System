function confirmUpdate() {
    var userConfirmed = confirm('Are you sure you want to update the rice?');
    if (userConfirmed) {
        return true; 
    }
    return false;
}
