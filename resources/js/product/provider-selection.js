const providerSelection = document.getElementById('provider-selection');
const providerForm = document.getElementById('provider-form');

function showProviderForm() {
    // Hide the shown selection
    providerSelection.setAttribute('hidden', 'true');
    providerSelection.querySelectorAll('input, select, textarea').forEach((e) => {
        e.setAttribute('disabled', 'true');
    });

    // Show the hidden form
    providerForm.removeAttribute('hidden');
    providerForm.querySelectorAll('input, select, textarea').forEach((e) => {
        e.removeAttribute('disabled');
    });
}

function showProviderSelection() {
    // Show the hidden selection
    providerSelection.removeAttribute('hidden');
    providerSelection.querySelectorAll('input, select, textarea').forEach((e) => {
        e.removeAttribute('disabled');
    });

    // Hide the shown form
    providerForm.setAttribute('hidden', 'true');
    providerForm.querySelectorAll('input, select, textarea').forEach((e) => {
        e.setAttribute('disabled', 'true');
    });
}
