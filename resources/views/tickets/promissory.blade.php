@extends('layouts.ticket-master')

@section('content')
    <div class="ticket-container">
        <p>
            BUENO POR $ <span class="underlined">&nbsp;&nbsp;&nbsp;&nbsp;{{ $data->amountTotal }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </p>

        <p>
            Por el presente pagaré reconozco deber y me obligo a pagar incondicionalmente
            a la orden de <span style="text-transform: uppercase">{{ config('app.name') }}</span>
            S.A. DE C.V. en la ciudad de Guadalajara Jalisco, o en cualquier otra que se me
            requiera de pago, la cantidad de $7,000.00 (siete mil pesos 00/100 moneda nacional),
            misma que he recibido a mi entera satisfacción. El valor de este pagare se cubrirá
            a su vencimiento el <span class="underlined">{{ $data->deadline }}</span>. Y para en
            caso de incurrir en mora el aceptante se obligara a pagar moratorios a razón del 8%
            (ocho por ciento) de interés mensual, hasta la total liquidación de este pagaré.
        </p>

        <p>
            El aceptante señala como domicilio para el cumplimiento de las obligaciones que
            emanan del presente documento el ubicado en:
        </p>

        <p>
        <div>Domicilio: _______________________________________</div>
        <div>Colonia: _________________________________________</div>
        <div>Ciudad: __________________________________________</div>
        </p>

        <p>
            El aceptante y su acreedor convienen que para su interpretación y cumplimiento, el
            presente pagaré lo sujetan a la Ley General de Títulos y Operaciones de Crédito, al
            Código de Comercio y supletoriamente en lo que corresponda, al Código de Procedimiento
            Civiles del Estado de Jalisco, asi como a la jurisdicción y competencia de los
            tribunales del partido judicial que corresponde a la ciudad de Guadalajara, Jalisco, México.
        </p>

        <p>
            Guadalajara, Jalisco a <span class="underlined">{{ $data->currentDate }}</span>
        </p>

        <p>
        <div class="underlined"></div>

        Nombre completo y firma del ACEPTANTE
        </p>
    </div>
@endsection
