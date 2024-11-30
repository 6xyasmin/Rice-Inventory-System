function confirmArchive(archiveUrl) {
    if (confirm('Are you sure you want to archive this rice entry?')) {
        location.href = archiveUrl;
    }
}