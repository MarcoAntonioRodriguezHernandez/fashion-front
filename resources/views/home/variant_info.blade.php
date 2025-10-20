<table class="table table-bordered">
    <thead>
        <tr>
            <th>Color</th>
            <th>Talla</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($variants as $key => $group)
            @php
                $first = $group->first();
                $color = $first->variant->color->name;
                $talla = $first->variant->size->full_name;
                $cantidad = $group->sum(fn($pv) => $pv->items->count());
            @endphp
            <tr>
                <td>{{ $color }}</td>
                <td>{{ $talla }}</td>
                <td>{{ $cantidad }}</td>
            </tr>
        @empty
            <tr><td colspan="3">No hay variantes disponibles.</td></tr>
        @endforelse
    </tbody>
</table>
