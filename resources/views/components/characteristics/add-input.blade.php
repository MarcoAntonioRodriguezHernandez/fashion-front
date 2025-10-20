@props([
    'containerId' => '',
    'inputFactoryId' => '',
])

<script>
    const inputContainer = document.getElementById('{{ $containerId }}');
    const inputFactory = document.getElementById('{{ $inputFactoryId }}');

    function createNewInput(value = '') {
        var newInput = inputFactory.cloneNode(true);
        var inputCounter = inputContainer.children.length + 1;

        newInput.id = "characteristic" + inputCounter;

        // Eliminar cualquier botón de eliminación existente antes de agregar uno nuevo
        var existingDeleteButton = newInput.querySelector(".btn-danger");
        if (existingDeleteButton) {
            newInput.removeChild(existingDeleteButton);
        }

        // Crear un nuevo botón de eliminación con el nuevo SVG
        var removeButton = document.createElement("button");
        removeButton.className = "btn btn-icon btn-color-muted btn-active-color-primary w-25px h-25px bg-body mt-3 mx-2";
        removeButton.setAttribute("data-kt-image-input-action", "remove");
        removeButton.setAttribute("data-bs-toggle", "tooltip");
        removeButton.setAttribute("data-bs-dismiss", "click");
        removeButton.setAttribute("title", "Remover input");
        removeButton.setAttribute("type", "button");

        var duotoneIcon = document.createElement("i");
        duotoneIcon.className = "ki-duotone ki-trash-square fs-1 text-danger";

        // Añadir las cuatro span con clases path1, path2, path3, path4
        for (var i = 1; i <= 4; i++) {
            var pathSpan = document.createElement("span");
            pathSpan.className = "path" + i;
            duotoneIcon.appendChild(pathSpan);
        }

        removeButton.appendChild(duotoneIcon);

        removeButton.onclick = function() {
            removeInput(this.parentNode);
        };
        newInput.id = "characteristic" + inputCounter;
        newInput.querySelector('input').value = value; // Establecer el valor del input en la cadena sleccionada

        newInput.appendChild(removeButton);

        inputContainer.appendChild(newInput);
    }

    function removeInput(element) {
        var inputContainer = document.getElementById("inputContainer");

        // Asegurarse de que siempre haya al menos un input disponible
        if (inputContainer.children.length > 1) {
            inputContainer.removeChild(element);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("add").addEventListener("click", function() {
            setupInputChangeEvent();
            updateSubmitButtonStatus();
        });

        setupInputChangeEvent();
        updateSubmitButtonStatus();

        var nameInput = document.getElementById("name");
        nameInput.addEventListener("input", function() {
            resetDynamicInputsColor();
            updateSubmitButtonStatus();
        });
    });

    function setupInputChangeEvent() {
        var nameInput = document.getElementById("name");
        var dynamicInputs = document.querySelectorAll('[name^="children"]');

        dynamicInputs.forEach(function(input) {
            input.addEventListener("input", function() {
                if (input.value === nameInput.value) {
                    input.style.color = "red";
                    input.setAttribute("data-bs-toggle", "tooltip");
                    input.setAttribute("data-bs-placement", "top");
                    input.setAttribute("title", "No debe tener el mismo texto que Nombre");
                    initializeTooltip(input);
                } else {
                    input.style.color = "";
                    input.removeAttribute("data-bs-toggle");
                    input.removeAttribute("data-bs-placement");
                    input.removeAttribute("title");
                    destroyTooltip(input);
                }
                updateSubmitButtonStatus();
            });
        });
    }

    function resetDynamicInputsColor() {
        var dynamicInputs = document.querySelectorAll('[name^="children"]');
        dynamicInputs.forEach(function(input) {
            input.style.color = "";
            destroyTooltip(input);
        });
    }

    function updateSubmitButtonStatus() {
        var nameInput = document.getElementById("name");
        var dynamicInputs = document.querySelectorAll('[name^="children"]');
        var submitButton = document.querySelector('button[type="submit"]');
        var inputContainer = document.getElementById("inputContainer");

        var hasRedText = Array.from(dynamicInputs).some(function(input) {
            return input.style.color === "red";
        });

        var isNameInputEmpty = nameInput.value === "";
        submitButton.disabled = hasRedText || isNameInputEmpty;

        // Deshabilitar/habilitar inputs en inputContainer según el estado de nameInput
        dynamicInputs.forEach(function(input) {
            input.disabled = isNameInputEmpty;
        });
    }

    function initializeTooltip(input) {
        $(input).tooltip('show');
    }

    function destroyTooltip(input) {
        $(input).tooltip('dispose');
    }
</script>
