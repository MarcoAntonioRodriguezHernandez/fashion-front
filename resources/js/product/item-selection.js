const inventoryContainer = document.getElementById('inventory-container');
const inventoryTitle = document.getElementById('inventory-title');
var inventoryCount = 0;

window.addInventoryElement = () => {
    const inventoryElement = document.getElementById('inventory-element-factory').cloneNode(true);

    if (!formIsFilled(inventoryElement)) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'El formulario no está completo',
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 4000,
            background: localStorage.getItem("data-bs-theme") == 'dark' ? '#1e1e2d' : '#ffffff',
        });
        return;
    }
    
    inventoryElement.id = 'inventory-element-' + inventoryCount;
    inventoryElement.querySelectorAll('span.select2-selection').forEach((elmnt) => {
        elmnt.classList.add('bg-secondary');
        elmnt.classList.remove('form-select', 'select2-selection--single'); // Remove select styles
        elmnt.readOnly = true;
    });
    inventoryElement.querySelectorAll('input, textarea, select').forEach((elmnt) => {
        elmnt.classList.add('bg-secondary');
        elmnt.readOnly = true;
        elmnt.name = 'inventory[' + inventoryCount + '][' + elmnt.id + ']';
    });
    inventoryElement.querySelectorAll('select').forEach((elmnt) => {
        elmnt.value = getSelectedValue(elmnt);
    });

    $(inventoryElement).find('select').select2("val");
    inventoryElement.querySelector('#btn-add-inventory').remove();
    inventoryElement.querySelector('#btn-remove-inventory').classList.remove('d-none');
    inventoryElement.querySelector('#btn-remove-inventory').addEventListener('click', () => {
        inventoryElement.remove();

        toggleInventoryVisible();
    });

    inventoryCount++;

    inventoryContainer.appendChild(inventoryElement);

    toggleInventoryVisible();

    scrollToElement(inventoryElement);
}

window.toggleInventoryVisible = () => {
    if (inventoryContainer.innerHTML.trim() == '') {
        inventoryTitle.classList.remove('d-inline');
        inventoryTitle.classList.add('d-none');
    } else {
        inventoryTitle.classList.remove('d-none');
        inventoryTitle.classList.add('d-inline');
    }
}

window.scrollToElement = (element) => {
    $('html, body').animate({
        scrollTop: $(element).offset().top
    }, 1000);
}

window.formIsFilled = (formElement) => {
    let filled = true;

    formElement.querySelectorAll('select').forEach((elmnt) => {
        if (getSelectedValue(elmnt) == '-- Elige una opción --') {
            filled = false;
        }
    });
    formElement.querySelectorAll('input, textarea').forEach((elmnt) => {
        if (elmnt.value.trim() == '') {
            filled = false;
        }
    });

    return filled;
}

window.getSelectedValue = (selectElement) => {
    return $(selectElement).select2("val");
}
