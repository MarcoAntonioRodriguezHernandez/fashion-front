@props([
    'createRoute' => '',
    'createText' => '',
    'module' => null,
    'searchBy' => 'search',
    'debounce' => false,
    'showCreateButton' => true
])

<div class="card-header border-0 pt-6 mb-3">
    <div class="card-title">
        <div class="d-flex align-items-center position-relative my-1" style="display: flex; justify-content: space-between;">
            @if ($searchBy)
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <form action="{{ Request::url() }}" method="GET" id="searchForm">
                    @foreach(request()->except('search') as $name => $value)
                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                    @endforeach

                    <input type="text" class="form-control form-control-solid w-300px ps-13" placeholder="Buscar" name="{{ $searchBy }}" value="{{ request($searchBy) }}" id="searchInput"/>
                </form>
            @else
                <div></div>
            @endif

            @if ($createRoute && $showCreateButton)
                @permission($module, PermissionTypes::CREATE)
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <a class="btn btn-primary" href="{{ $createRoute }}">{{ $createText }}</a>
                    </div>
                @endpermission
            @endif
        </div>
    </div>
</div>

@if($debounce)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        let debounceTimer;

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                searchForm.submit();
            }, 500);
        });
    });
</script>
@endif