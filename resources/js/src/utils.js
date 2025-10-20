import { usePage } from '@inertiajs/vue3';

const page = usePage();

export const url = (path) => {
    return [
        page.props.baseUrl,
        path,
    ]
        .join('/')
        .replace(/([^:])\/{2,}/g, '$1/')
        .toString();
}

export const createBoards = (data) =>
    data.reduce((acc, board) => {
        acc[board.id] = {
            id: board.id,
            title: board.title,
            class: 'primary',
            item: board.items.map(item => ({
                title: createItemElement(item.product_name, item.first_image, item.enabled, item.primaryData, item.secondaryData, item.tertiaryData),
                id: item.id,
                class: item.enabled ? ['cursor-grab'] : ['bg-disabled', 'cursor-not-allowed'],
                origin_id: board.id,
                origin_name: board.title,
            })),
        };

        return acc;
    }, {});

export const createItemElement = (title, imgSrc, enabled, primaryData, secondaryData, tertiaryData) => {
    primaryData['colorText'] = getContrastingTextColor(primaryData.color);
    secondaryData['colorText'] = getContrastingTextColor(secondaryData.color);

    return `
        <div class="d-flex align-items-center overflow-auto item-element ${enabled ? 'item_handle' : ''}">
            <div class="symbol symbol-success me-3">
                <img alt="Img" src="${imgSrc}" style="object-fit: cover;" class="w-45px h-80px">
            </div>
            <div class="d-flex flex-column flex-grow-1">
                <span class="text-gray-900-50 fw-bold mb-1">${title}</span>
                <div class="d-flex flex-row justify-content-between flex-wrap mb-1">
                    <span class="badge" style="background-color: ${primaryData.color}; color: ${primaryData.colorText}";>${primaryData.text}</span>
                    <span class="badge" style="background-color: ${secondaryData.color}; color: ${secondaryData.colorText};">${secondaryData.text}</span>
                </div>
                <span class="badge mb-1 px-0" style="background-color: ${tertiaryData.color}; color: ${tertiaryData.colorText};">${tertiaryData.text}</span>
            </div>
        </div>
        `;
}

export const fullAlert = (alertOptions) => {
    return Swal.fire(alertOptions);
}

export const simpleAlert = (title, message, icon) => {
    Swal.fire({
        title: title,
        text: message,
        icon: icon,
        confirmButtonText: 'Ok',
    });
};

export const isDarkColor = (color) => {
    const hexToRgb = (hex) =>
        hex.match(/[A-Za-z0-9]{2}/g).map((v) => parseInt(v, 16));
    const [r, g, b] = hexToRgb(color);
    const brightness = (r * 299 + g * 587 + b * 114) / 1000;
    return brightness < 128;
}

export const getContrastingTextColor = (color) => {
    return isDarkColor(color) ? '#FFFFFF' : '#000000';
}

export const setChangedStyle = (element, changed) => {
    if (changed == true) {
        element.classList.add('border', 'border-3', 'border-warning');
    } else {
        element.classList.remove('border', 'border-3', 'border-warning');
    }
}

export const getCurrentDateTime = () => {
    let date = new Date();
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());

    return date.toISOString().slice(0,16);
}

export const currentLocalDate = (date = new Date(), locale = 'en-ES', timezone = 'America/Mexico_City') => {
    let dateString = typeof date === 'string' ?
        new Date(date).toLocaleString(locale, { timeZone: timezone }) :
        date.toLocaleString(locale, { timeZone: timezone });

    return new Date(dateString);
}

export const dateDiffInDays = (a, b) => {
    const _MS_PER_DAY = 1000 * 60 * 60 * 24;

    const utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
    const utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());

    return Math.floor((utc2 - utc1) / _MS_PER_DAY);
}

export const checkDateBetween = (check, from, to) => {
    if (!from || !to) return false;

    return (check.getTime() <= to.getTime() && check.getTime() >= from.getTime());
}

export const normalizedDate = (date) => {
    const normalized = new Date(date);
    normalized.setHours(0, 0, 0, 0);

    return normalized;
}

export const randomString = (length) => {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let randomString = '';

    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        randomString += characters.charAt(randomIndex);
    }

    return randomString;
}

export const isDragging = (e) => {
    const allBoards = document.getElementById('locations_container').querySelectorAll('.kanban-drag');
    allBoards.forEach(board => {
        // Get inner item element
        const dragItem = board.querySelector('.gu-transit');

        // Stop drag on inactive board
        if (!dragItem) {
            return;
        }

        // Get jKanban drag container
        const containerRect = board.getBoundingClientRect();

        // Get inner item size
        const itemSize = dragItem.offsetHeight;

        // Get dragging element position
        const dragMirror = document.querySelector('.gu-mirror');
        const mirrorRect = dragMirror.getBoundingClientRect();

        // Calculate drag element vs jKanban container
        const topDiff = mirrorRect.top - containerRect.top;
        const bottomDiff = containerRect.bottom - mirrorRect.bottom;

        // Scroll container
        if (topDiff <= itemSize) {
            // Scroll up if item at top of container
            board.scroll({
                top: board.scrollTop - 3,
            });
        } else if (bottomDiff <= itemSize) {
            // Scroll down if item at bottom of container
            board.scroll({
                top: board.scrollTop + 3,
            });
        } else {
            // Stop scroll if item in middle of container
            board.scroll({
                top: board.scrollTop,
            });
        }
    });
}

export const initSelectElements = (rootRef, targetForm) => {
    const elementList = $(rootRef.value.querySelectorAll('select'));

    elementList.each(function (index, select) { // Initialize non-initialized select elements
        if (!select.classList.contains('select2-hidden-accessible')) {
            $(select).select2({
                placeholder: 'Select an option'
            });
        }
    });

    elementList.change(function () {
        let val = null;

        if (this.multiple) {
            val = $(this).find("option:selected").map((index, option) => option.value).get();
        } else {
            val = $(this).find("option:selected").val();
        }

        updateNestedProperty(targetForm, this.name, val);
    });
}

export const updateNestedProperty = (obj, key, value) => {
    const keys = key.split('.');
    let current = obj;

    for (let i = 0; i < keys.length - 1; i++) {
        current = current[keys[i]];
    }

    current[keys[keys.length - 1]] = value;
}

export const updateSelectElements = (rootRef, sourceForm) => {
    $(rootRef.value.querySelectorAll('select')).each(function () {
        $(this).val(sourceForm[this.name]).trigger('change');
    });
}

export const encodeShortenedArray = (values) => {
    let encoded = [];

    values.forEach(value => {
        if (isNaN(value)) {
            throw new Error(`Every value must be numeric, received ${value}`);
        }

        encoded.push(parseInt(value).toString(36));
    });

    return encoded.join('-');
}

export const arraySymDifference = (array1, array2) => {
    return array1.filter(x => !array2.includes(x))
        .concat(array2.filter(x => !array1.includes(x)));
}

export const arrayDifference = (array1, array2) => {
    return array1.filter(x => !array2.includes(x));
}

export const arrayUnion = (array1, array2) => {
    return [...new Set([...array1, ...array2])];
}

export const arrayDeepClone = (source) => {
    return JSON.parse(JSON.stringify(source));
}

export const toggleSelection = (collection, selectionIndex) => {
    const index = collection.indexOf(selectionIndex);

    if (index === -1) {
        collection.push(selectionIndex);
    } else {
        collection.splice(index, 1);
    }
}

export const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
}

export const initGoTopButton = (elementId) => {
    let element = document.getElementById(elementId);

    window.addEventListener('scroll', () => {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            element.style.display = 'block';
        } else {
            element.style.display = 'none';
        }
    });
}

export const makeRange = (min, max) => {
    if (min > max) {
        throw 'The value ' + min + ' is greater than ' + max;
    }

    let range = [];

    for (let i = min; i <= max; i++) {
        range.push(i);
    }

    return range;
}

export const generateExcelReport = (urlGenerate, fileName, filterForm, { onSuccess = () => { }, onError = () => { } } = {}) => {
    axios.post(url(urlGenerate), filterForm, {
        responseType: 'blob'
    })
        .then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', fileName);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            onSuccess();
        })
        .catch((error) => {
            createAlert('ERROR', "No se pudo generar el reporte, intente nuevamente.", 'error');
            onError();
        });
}

export const DiscountTypes = Object.freeze({
    PERCENTAGE: 1,
    AMOUNT: 2,
});
