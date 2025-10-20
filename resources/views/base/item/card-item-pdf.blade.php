<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta property="og:type" content="article" />

    <title>Fichas de los productos</title>

    <style>
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 200;
            src: url('./font-pdf/Montserrat-Regular') format('ttf');
            unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
        }

        body {
            font-family: 'Montserrat', sans-serif;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        .border {
            border: 0.9px solid #000000;
        }

        .border-light {
            border-color: #9b9b9b !important;
        }

        .border-dark {
            border-color: #000000 !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .p-0 {
            padding: 0 !important;
        }

        .m-0 {
            margin: 0 !important;
        }

        .mt-3 {
            margin: 1em !important;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .col-12 {
            width: 100%;
        }

        .col-8 {
            width: 66.66666667%;
        }

        .col-6 {
            width: 50%;
        }

        .col-4 {
            width: 33.33333333%;
        }

        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1* var(--bs-gutter-y));
        }

        .text-center {
            text-align: center !important;
        }

        .bg-light {
            background-color: #f8f3f0;
        }

        .bg-white {
            background-color: #ffffff;
        }

        .circle {
            position: absolute;
            bottom: 3em;
            left: 50%;
            transform: translateX(75%);
            width: 3.9em;
            height: 3.9em;
            background-color: #ffffff;
            border-radius: 50%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            z-index: 999;
        }

        @page {
            margin: 4em 2em;
        }

        body {
            margin: 0;
        }
    </style>
</head>

<body>
    @foreach ($data as $item)
        @if ($loop->index % 2 == 0)
            <table>
                <tbody>
                    <tr>
        @endif

        <td class="border border-light" style="padding: 0.3em 0.7em; height: 42.5em; width: 22em;">
            <table cellspacing="0">
                <tbody>
                    <tr>
                        <td colspan="100%" class="p-0">
                            <div class="border border-light bg-light" style="position: relative; height: 2.5em;">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="col-8 p-0">
                            <div class="border border-light text-center" style="position: relative; height: 4.8em;">
                                <div class="circle" style="width: 50px; height: 50px; border-radius: 50%; background-color: #ffffff;">
                                    <img src="{{ $logoImage }}" alt="Logo" style="max-width: 100%; max-height: 100%; border-radius: 50%;">
                                </div>
                                <div style="margin-top: 1em;">
                                    <p class="mb-0 fw-bold">Marca</p>
                                    <p class="mb-0">{{ $item->designer }}</p>
                                </div>
                            </div>
                            <div class="border border-light text-center" style="height: 22.4em;">
                                <img style="padding: 3% auto; max-width: 100%;" height="94%" src="{{ $item->product_image }}">
                            </div>
                            <div class="border border-light text-center" style="height: 3.6em;">
                                <div style="margin-top: 6px">
                                    <p class="mb-0 fw-bold">Precio de Venta</p>
                                    <p class="mb-0">$ {{ $item->prices['sale'] }}</p>
                                </div>
                            </div>
                            <div class="border border-light text-center" style="height: 3.6em;">
                                <div style="margin-top: 6px">
                                    <p class="mb-0 fw-bold">Renta 8 Días</p>
                                    <p class="mb-0">$ {{ $item->prices['rent_8_days'] }}</p>
                                </div>
                            </div>
                            <div class="border border-light text-center bg-light" style="height: 3.6em;">
                                <div style="margin-top: 6px">
                                    <p class="mb-0 fw-bold">Renta 4 Días</p>
                                    <p class="mb-0">$ {{ $item->prices['rent_4_days'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="col-4 p-0">
                            <div class="border border-light text-center" style="background-color: {{ $item->size['color'] }}; height: 7.2em;">
                                <div style="margin-top: 1em">
                                    <p class="mb-0 fw-bold">Talla</p>
                                    @if (preg_match('/[a-zA-Z]/', $item->size['name']))
                                        <p class="mb-0" style="font-size: 1em">
                                    @else
                                        <p class="mb-0" style="font-size: 3em">
                                    @endif
                                        {{ $item->size['name'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="border border-light text-center" style="height: 6.2em;">
                                <div style="margin-top: 2em;">
                                    <p class="mb-0 fw-bold">Nombre</p>
                                    <p class="mb-0">{{ $item->name }}</p>
                                </div>
                            </div>
                            <div class="border border-light text-center" style="height: 8.4em;">
                                <div style="margin-top: 1.5em">
                                    <p class="mb-0 fw-bold">Color</p>
                                    @if ($item->color['texture']) 
                                        <div class="mt-3 border border-dark" 
                                            style="width: 1.5rem; height: 1.5rem; border-radius: 50%; display: inline-block; 
                                            background-image: url('{{ $item->color['texture'] }}'); background-size: cover; background-position: center;">
                                        </div>
                                    @else 
                                        <div class="mt-3 border border-dark" 
                                            style="width: 1.5rem; height: 1.5rem; background-color: {{ $item->color['hexadecimal'] }}; border-radius: 50%; display: inline-block;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="border border-light text-center" style="height: 8.9em;">
                                <div style="margin-top: 2em; margin-left: 1.8em;">
                                    <p class="mb-0 fw-bold">{!! $item->qrcode_image !!}</p>
                                </div>
                            </div>
                            <div class="border border-light text-center" style="height: 7.3em;">
                                <div style="margin-top: 2.5em; margin-left: 1em;">
                                    <p class="mb-0 fw-bold">{!! $item->barcode_image !!}</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>

        @if ($loop->even)
            </tr>
            </tbody>
            </table>
        @endif
    @endforeach
</body>

</html>
