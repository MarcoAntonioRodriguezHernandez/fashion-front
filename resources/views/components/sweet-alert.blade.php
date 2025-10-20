@props(['title' => '', 'route' => '', 'successTitle' => '', 'successText' => '', 'errorTitle' => '', 'errorText' => ''])

<script>
    function eliminar(id) {
        Swal.fire({
            title: '{{ $title }}',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            // toast: true,
            // position: 'top-end',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false,            
            didOpen: () => {
                // En el evento didOpen, quitar la clase "swal2-height-auto" del elemento con el ID 'kt_app_body'
                document.getElementById('kt_app_body').classList.remove('swal2-height-auto');
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let url = "{{$route}}";
                axios.delete(`${url}/${id}`, {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).then((response) => {
                    Swal.fire({
                        title: '{{$successTitle}}',
                        text: "{{$successText}}",
                        icon: 'success',
                        // toast: true,
                        // position: 'top-end',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                    }).then((result) => {
                        location.reload();
                    });
                }).catch((error) => {
                    crearAlertaSimple('{{$errorTitle}}',
                        '{{$errorText}}', 'error');
                });
            }
        });
    }

    function crearAlertaSimple(titulo, mensaje, icono) {
        Swal.fire({
            title: titulo,
            text: mensaje,
            icon: icono,
            confirmButtonText: 'Ok',
        });
    }
</script>