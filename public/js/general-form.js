'use strict';
var GeneralForm = function () {
    var f, t;
    return {
        init: function (formId, fields, errorMessage) {
            (f = document.getElementById(formId)) &&
            (f.querySelector('button[type="submit"]').addEventListener('click', function (e) {
                if (!t) return;

                e.preventDefault();

                t.validate().then(function (s) {
                    if (s == 'Valid') {
                        f.submit();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            text: errorMessage ||= 'Sorry, looks like there are some errors detected, please try again',
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            background: localStorage.getItem("data-bs-theme") == 'dark' ? '#1e1e2d' : '#ffffff',
                        });
                    }
                });
            }),
            t = FormValidation.formValidation(f, {
                fields: fields,
                plugins: {
                    trigger: new FormValidation.plugins.Trigger,
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }),
            $(f.querySelectorAll('select')).on('change', (function (event) {
                t.revalidateField(event.target.name);
            })))
        }
    }
}();
