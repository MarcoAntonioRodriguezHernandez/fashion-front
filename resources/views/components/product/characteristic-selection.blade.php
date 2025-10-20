@props([
    'characteristics' => [],
    'selected' => [],
])

<div class="row">
    @foreach ($characteristics as $parent)
    <div id="characteristic-selection-{{ $parent->id }}" class="col-md-6 col-12 mb-3">
        <div class="mb-10 fv-row">
            <!--begin::Label-->
            <label class="required form-label">{{ $parent->name }}</label>
            <!--end::Label-->
            <!--begin::Input-->
            <div class="d-flex gap-3">
                <select class="form-select select2" data-control="select2" data-tags="true" multiple name="characteristics[{{ $parent->id }}][]" onchange="updateDisplayedSizes()">
                    <option hidden disabled>-- Elige una opción --</option>
                    @foreach ($parent->children as $child)
                        <option value="{{ $child->id }}" @selected(in_array($child->id, old('characteristics.' . $parent->id, $selected)))>
                            {{ $child->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!--end::Input-->
        </div>
    </div>
    @endforeach
</div>
