const previewThumbnail = document.getElementById('image-preview-thumbnail');
const imageContainer = document.getElementById('image-container');
var imageCount = 0;

KTUtil.onDOMContentLoaded(function () {
    $('#color_id_image').on('change', function (event) {
        document.getElementById('image-inputs__color').value = event.target.value;
    });
    $('#view_id_image').on('change', function (event) {
        document.getElementById('image-inputs__view').value = event.target.value;
    });
    $('#color_id_image').on('change', function (event) {
        document.getElementById('image-info__circle').style.backgroundColor = getSelectedOption('color_id_image').getAttribute('data-hex');
        document.getElementById('image-info__name').textContent = getSelectedOption('color_id_image').textContent;
    });
});

const setViewTab = (view) => {
    $('#view_id_image').val(view);
    $('#view_id_image').select2().trigger('change');
}

function addImageValue() {
    if (!imageFormIsFilled()) {
        swal.fire({
            text: 'Por favor, llene todos los campos y asegúrese de haber elegido una imagen.',
            icon: 'warning',
            buttonsStyling: false,
            confirmButtonText: 'Ok, lo tengo!',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        });
        return;
    }

    const imageInputs = document.getElementById('image-inputs').cloneNode(true);

    imageInputs.id = 'image-inputs-' + imageCount;
    imageInputs.querySelector('label[data-kt-image-input-action="change"]').classList =
        'd-none'; // Hide edition view
    // Change input names
    imageInputs.querySelector('#image-inputs__image').name = 'images[' + imageCount + '][image]';
    imageInputs.querySelector('#image-inputs__color').name = 'images[' + imageCount + '][color_id]';
    imageInputs.querySelector('#image-inputs__view').name = 'images[' + imageCount + '][camera_perspective]';

    // Show button 'remove image'
    imageInputs.querySelector('#remove-image-btn').classList.remove('d-none');

    imageInputs.addEventListener('click', () => previewImage(imageInputs));

    imageInputs.dispatchEvent(new Event('click')); // Show preview

    imageCount++;

    resetImageInputs();

    const selectedView = getSelectedOption('view_id_image').value;

    document.getElementById('image-container-' + selectedView).appendChild(imageInputs);
}

const resetImageInputs = () => {
    const imageInputs = document.getElementById('image-inputs');

    // Reset names
    imageInputs.querySelector('#image-inputs__color').name = '';
    imageInputs.querySelector('#image-inputs__view').name = '';
    imageInputs.querySelector('#image-inputs__image').name = '';

    // Reset values
    imageInputs.querySelector('#image-inputs__image').value = null;
}

const previewImage = (element, showEdit = false) => {
    // Get the background content of this element
    let backgroundImage = element.querySelector('.image-input-wrapper').style.backgroundImage;

    // Extract the url from the image
    let imageUrl = backgroundImage.replace('url(', '').replace(')', '').replace(/\"/gi, "");

    // Set the new background on the target
    previewThumbnail.style.backgroundImage = "url('" + imageUrl + "')";

    if (showEdit) {
        document.getElementById('edit-image-btn').classList.remove('d-none');
    } else {
        document.getElementById('edit-image-btn').classList.add('d-none');
    }

    // Disble or enable inputs
    $("#color_id_image").attr('disabled', !showEdit);
}

const imageFormIsFilled = () => {
    const selectedColor = getSelectedOption('color_id_image').value != '-- Elige una opción --';
    const selectedView = getSelectedOption('view_id_image').value != '-- Elige una opción --';
    const selectedFile = document.getElementById('image-inputs__image').files.length > 0;

    return selectedColor && selectedView && selectedFile;
}

const getSelectedOption = (selectId) => {
    return document.getElementById(selectId).querySelector('option:checked');
}

function initImageHandling() {
    // simular click en el boton id="first_view_side" al cargar la pagina
    window.addEventListener('load', () => {
        document.getElementById('first_view_side').click();
    });
}

document.getElementById('btn-add-image').addEventListener('click', addImageValue);
