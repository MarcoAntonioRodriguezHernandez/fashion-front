<div>
    @extends('layouts.app') <!-- Ajusta el nombre de tu layout principal -->

    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Confirmación de Cancelación de Orden</div>

                        <div class="card-body">
                            <p>Su orden ha sido cancelada con éxito.</p>

                            <p>Por favor, confirme la cancelación ingresando el siguiente código:</p>

                            <form method="POST" action="{{ route('orders.confirm', $orderMarketplace->id) }}">
                                @csrf
                                <div class="form-group">
                                    <label for="confirmation_code">Código de Confirmación:</label>
                                    <input type="text" name="confirmation_code" class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Confirmar Cancelación</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

</div>
