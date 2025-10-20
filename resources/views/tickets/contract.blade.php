@extends('layouts.ticket-master')

@section('content')
    <div class="ticket-container">
        <p>
            Carátula Contrato de Arrendamiento que celebran {{ config('app.name') }} S.A.
            de C.V. y {{ $client->full_name }}
        </p>

        <p>
            Acepto que de no entregar el o los artículos en la fecha prevista se generará
            un recargo de 250 (Doscientos cincuenta pesos) por día hasta un máximo de 10
            días hasta donde se hará efectivo el pagaré más el costo de los recargos.
        </p>

        <p class="fw-bold">
            Se me ofreció el seguro el cuál cubre los siguientes daños:
        </p>

        <p class="text-start">
        <ul class="text-start">
            <li>Lentejuelas caídas</li>
            <li>Daños en la bastilla con un máximo de 1.5 cm. desde el piso hasta el daño</li>
            <li>Cierres y botones descompuestos</li>
            <li>Rasgaduras en las costuras que no sobrepasen el 1 cm. desde la costura</li>
        </ul>
        </p>

        <p class="fw-bold" style="margin-bottom: 0px;">
            Compré el seguro
        </p>

        <p style="margin-top: 0px;">
        <div class="circle-wrapper">
            <div class="circle-container" style="float: left; direction: rtl;">
                <div class="circle">Si</div>
            </div>
            <div class="circle-container" style="float: right;">
                <div class="circle">No</div>
            </div>
        </div>
        </p>

        <p>
            Reconozco que en caso de que el o los artículos sufran cualquier otro daño
            tal es el caso de una quemadura de cigarro, desgarre, taconazo fuera de 1.5
            del suelo hacia el vestido o mancha imposible de limpiar, {{ config('app.name') }}
            estará en su derecho de cobrar la totalidad del vestido.
        </p>

        <p>
        <div class="underlined"></div>

        Iniciales
        </p>

        <p>
            Reconozco que en caso de haber contratado los servicios de ajuste y/o bastilla,
            estos serán hechos a mano de forma no permanente y no podrán ser planchados
            (con el fin de que el vestido no sufra marcas permanentes) y estás puntadas
            podrán ser notorias a la vista.
        </p>

        <p>
            Revisé y recibí el o los artículos a mi entera satisfacción.
        </p>

        <p class="fw-bold">
            Uso de redes sociales
        </p>

        <p>
            Autorizo expresamente a {{ config('app.name') }}, S.A. de C.V. a la utilización
            de las imágenes del articulo arrendado en redes sociales del arrendador en las
            cuales aparezcan el articulo arrendado y el arrendatario.
        </p>

        <p>
        <div class="underlined"></div>

        Nombre completo y firma
        </p>

        <p>
            Notas:
        </p>

        <p></p>
    </div>
@endsection
