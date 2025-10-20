window.toggleAll = (moduleId = null) => {
    moduleId = moduleId ? moduleId + ']' : '';

    const checks = document.querySelectorAll('input[type="checkbox"][name^="permissions[' + moduleId + '"]'); // Select all the checkboxes of a module (or all if none set)

    if (checks.length) { // If there are checkboxes
        const checked = !areAllChecked(checks);

        for (const check of checks)
            check.checked = checked;

        if (moduleId) // Only if there's is a module set
            updateAllChecked();
    }
}

window.updateAllChecked = () => {
    const checks = document.querySelectorAll('input[type="checkbox"][name^="permissions');

    document.getElementById('toggle-all-check').checked = areAllChecked(checks);
}

window.areAllChecked = (checkList) => {
    for (const check of checkList)
        if (!check.checked)
            return false; // If any input is not checked

    return true;
}
