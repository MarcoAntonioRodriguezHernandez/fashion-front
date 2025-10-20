<x-layouts.master-layout title="Enlace privado de registro" cardTitle="Enlace privado de registro">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="invitationLink" class="form-label">El enlace de registro es:</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="invitationLink" value="{{ session('invitation_link', 'Error al mostrar el enlace') }}" readonly>
                                <button class="btn btn-primary" type="button" onclick="copyLink()">Copiar Enlace</button>
                            </div>
                        </div>

                        <div class="alert alert-dismissible bg-light-success border border-success border-dashed d-flex flex-column flex-sm-row w-100 p-5 my-10">
                            <!--begin::Icon-->
                            <i class="ki-duotone ki-user-tick fs-2hx text-success me-4 mb-5 mb-sm-0">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <!--end::Icon-->

                            <!--begin::Content-->
                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                <h5 class="mb-1">Se ha enviado la invitatión al correo electrónico</h5>
                                <span>También puede copiar el enlace que aparece arriba. Recuerde que el enlace sólo puede usarse una vez.</span>
                                <span>Sólo podrá ver este enlace ahora</span>
                            </div>
                            <!--end::Content-->

                            <!--begin::Close-->
                            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                <i class="ki-duotone ki-cross fs-1 text-success"><span class="path1"></span><span class="path2"></span></i> </button>
                            <!--end::Close-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyLink() {
            var copyText = document.getElementById("invitationLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Enlace copiado al portapapeles',
                    showConfirmButton: false,
                    timer: 2000
                });
            }).catch((err) => {
                console.error('Error al copiar el enlace: ', err);
            });
        }
    </script>
</x-layouts.master-layout>
