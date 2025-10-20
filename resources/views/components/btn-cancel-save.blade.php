@props([
'routeCancel' => '',
'isShow' => '',
'routeEdit' => '',
'module' => null,
])
<div class="d-flex justify-content-end">
    <!--begin::Button-->
    <a href=
    @permission($module, PermissionTypes::READ) "{{$routeCancel}}" @else "{{ route('dashboard') }}" @endpermission 
    id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancelar</a>

    <!--end::Button-->
    
    {{ $slot }}

    <!--begin::Button-->
    @permission($module, PermissionTypes::UPDATE)
    @if ($isShow == 'true')
    @if($routeEdit)
    <a class="btn btn-primary" href="{{$routeEdit}}">
        <span class="indicator-label">Editar</span>
    </a>
    @endif
    @else
    <button type="submit" class="btn btn-primary">
        <span class="indicator-label">Guardar</span>
    </button>
    @endif
    @endpermission
    <!--end::Button-->
</div>