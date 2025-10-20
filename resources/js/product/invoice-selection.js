let invoiceNumberInput = null;
let invoiceIdInput = null;

let invoiceInputs = {
    buyerId: null,
    paymentStatus: null,
    issuanceDate: null,
    paymentTypeId: null,
    exchangeRate: null,
    file: null,
    file_view: null,
}

window.initInvoiceInputs = () => {
    invoiceNumberInput = document.getElementById('invoice_number');
    invoiceIdInput = document.getElementById('invoice_id');
    invoiceInputs.buyerId = document.getElementById('invoice_buyer_id');
    invoiceInputs.paymentStatus = document.getElementById('invoice_payment_status');
    invoiceInputs.issuanceDate = document.getElementById('invoice_issuance_date');
    invoiceInputs.paymentTypeId = document.getElementById('invoice_payment_type_id');
    invoiceInputs.exchangeRate = document.getElementById('invoice_exchange_rate');
    invoiceInputs.file = document.getElementById('invoice_file');
    invoiceInputs.file_view = document.getElementById('invoice_file_view');

    invoiceNumberInput.addEventListener('input', () => {
        invoiceNumberInput.name = 'invoice[invoice_number]';
    })
}

window.searchInvoiceByNumber = (route, fieldPlaceholder = ':number') => {
    let invoiceNumber = invoiceNumberInput.value;

    if (!invoiceNumber) {
        Swal.fire({
            icon: 'info',
            title: 'Introduzca un número de factura',
            position: 'top-end',
            toast: true,
            showConfirmButton: false,
            timer: 4000,
        });

        return;
    }

    fetch(route.replace(fieldPlaceholder, invoiceNumber))
        .then(response => response.json())
        .then((data) => {
            if (data.error) {
                throw data.error;
            }

            invoiceNumberInput.value = data.invoice_number;
            invoiceIdInput.value = data.id;
            invoiceInputs.buyerId.value = data.buyer;
            invoiceInputs.paymentStatus.value = data.payment_status;
            invoiceInputs.issuanceDate.value = data.issuance_date;
            invoiceInputs.paymentTypeId.value = data.payment_type_id;
            invoiceInputs.exchangeRate.value = data.exchange_rate;
            invoiceInputs.file.classList.add('d-none');
            invoiceInputs.file_view.classList.remove('d-none');
            invoiceInputs.file_view.querySelector('a').href = data.invoice_file.file;

            Object.values(invoiceInputs).forEach(e => {
                e.dispatchEvent(new Event('change'));
                e.disabled = true;
            });
            invoiceNumberInput.name = '';
            invoiceIdInput.disabled = false;
        }).catch(() => {
            invoiceIdInput.disabled = true;

            Object.values(invoiceInputs).forEach(e => {
                e.dispatchEvent(new Event('change'));
                e.disabled = false;
            });
            invoiceInputs.file.classList.remove('d-none');
            invoiceInputs.file_view.classList.add('d-none');
            invoiceInputs.file_view.querySelector('a').href = null;

            Swal.fire({
                icon: 'info',
                title: 'El número de factura no existe',
                text: 'Si deseas ingresar un nuevo número de factura, completa los campos de facturación. En caso contrario, inténtalo nuevamente.',
                position: 'top-end',
                toast: true,
                showConfirmButton: false,
                timer: 4000,
            });
        });
}

window.addEventListener('load', initInvoiceInputs);
