<x-layouts.master-layout title="Crear link privado" cardTitle="Crear link privado">
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('base.temporary.generate_link') }}" method="POST">
                    @csrf
                    <div class="row row-cols-1 row-cols-md-2">
                        <div class="col mb-4">
                            <div class="form-group mb-4">
                                <label for="invitation_type" class="form-label">Tipo de invitación</label>
                                <select name="invitation_type" id="invitation_type" class="form-select form-control"
                                    data-control="select2" data-hide-search="true" data-placeholder="">
                                    @foreach (InvitationTypes::getAllNames() as $value => $name)
                                        <option value="{{ $value }}" @selected($value == old('invitation_type'))>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col mb-4">
                            <div class="form-group mb-4">
                                <label for="expiration" class="form-label">Tiempo de expiración</label>
                                <select name="expiration" id="expiration" class="form-select form-control"
                                    data-control="select2" data-hide-search="true" data-placeholder="">
                                    <option selected hidden disabled>Elige una opción</option>
                                    @foreach (App\Models\Base\TemporaryInvitation::EXPIRATION_TIMES as $label => $expTime)
                                        <option value="{{ $expTime }}">{{ __($label) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col mb-4">
                            <div class="form-group mb-4">
                                <label for="store_id" class="form-label">Tienda del Usuario</label>
                                <select name="store_id" id="store_id" class="form-select form-control"
                                    data-control="select2" data-hide-search="true" data-placeholder="">
                                    <option selected hidden disabled>Elige una opción</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col mb-4 mt-2">
                            <div class="form-group mb-4">
                                <label for="email">Correo electrónico</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="example@email.com">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="roles" class="form-label">Asignar roles</label>
                        <div class="row">
                            @foreach ($roles as $role)
                                <div class="col-12 col-md-6 col-lg-3 mb-2">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                            name="roles[{{ $role->id }}]" value="{{ $role->id }}"
                                            id="role_{{ $role->id }}">
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group mb-4" id="customExpirationGroup" style="display: none;">
                        <label for="custom_expiration" class="form-label">Expiración personalizada</label>
                        <input type="date" class="form-control" id="custom_expiration" name="custom_expiration"
                            value="{{ old('custom_expiration', date('Y-m-d')) }}">
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Generar código</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.master-layout>
