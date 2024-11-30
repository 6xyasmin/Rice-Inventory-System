function confirmLogout() {
    var userConfirmed = confirm('Are you sure you want to logout?');
    if (userConfirmed) {
        location.href = '/rice/logout.php';
    }
}
