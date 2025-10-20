<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta property="og:type" content="article" />
    <title>Distribución {{ $supply?->code }}</title>
    <style>
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 200;
            src: url('./font-pdf/Montserrat-Regular') format('ttf');
            unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
        }

        strong {
            font-weight: 400;
            font-family: 'Montserrat', sans-serif;
        }

        th,
        address,
        td,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .text-tr {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            text-align: center;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        tr,
        td {
            border-top: 1px solid #ddd;
        }

        /* badge rounded */

        .badge {
            border-radius: 15px;
            padding: 1px 5px;
            font-size: 1em;
            border: 1px solid;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        .alert-primary {
            color: #004085;
            background-color: #cce5ff;
            border-color: #b8daff;
        }

        .alert-secondary {
            color: #383d41;
            background-color: #e2e3e5;
            border-color: #d6d8db;
        }

        .panel {
            border: 1px solid #d3d3d3;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        }

        .logo {
            position: absolute;
            right: 0;
            top: 0;
        }

        .boeing {
            position: absolute;
            right: 0;
            top: 165px;
        }

        hr {
            border: 0;
            height: 1px;
            background: #e3e3e3;
            background-image: linear-gradient(to right, #ccc, #e0e0e0, #ccc);
        }

        .panel-heading {
            border-bottom: 1px solid transparent;
            border-top-right-radius: 3px;
            border-top-left-radius: 3px;
            color: #333;
            background-color: #f5f5f5;
            border-color: #ddd;
        }

        .panel-body {
            background-color: #fff;

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="invoice-title">
                    <div>
                        <h1>Resumen de movimientos</h1>
                        <img class="logo" height="70" src="{{ $logoImage }}" alt="">
                    </div>
                </div>
                <hr>
                @if($supply != null)
                    <div class="row">
                        <div class="col-xs-12">
                            <div style="display: flex; justify-content: space-between;">
                                <h3>
                                    <strong style="font-weight: 700;">Código:</strong> {{ $supply->code }}
                                </h3>
                                <h4>
                                    <strong style="font-weight: 700;">Emisor:</strong> <br> {{ $supply->sender->full_name }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-xs-6 text-right">
                        </div>
                    </div>
                    <div class="boeing">
                        <div class="col-xs-12 text-right">
                            <address>
                                <strong style="font-weight: 700;">Fecha de Creación</strong><br>
                                {{ strftime('%d de %B del %Y', strtotime($supply->created_at)) }}
                            </address>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @foreach ($supplyTransfers as $transfer)
                    <div style="page-break-inside: avoid; margin-bottom: 1em;">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 style="padding-left: 0.5em;" class="panel-title">
                                    <strong>
                                        Distribución {{ $transfer->origin->name }}
                                        <img height="15" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0yMS44ODMgMTJsLTcuNTI3IDYuMjM1LjY0NC43NjUgOS03LjUyMS05LTcuNDc5LS42NDUuNzY0IDcuNTI5IDYuMjM2aC0yMS44ODR2MWgyMS44ODN6Ii8+PC9zdmc+">
                                        {{ $transfer->destination->name }}
                                    </strong>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-condensed" style="margin-bottom: 0 !important;">
                                        <thead>
                                            <tr>
                                                <th style="text-align: left !important;" class="text-tr">Nombre</th>
                                                <th class="text-tr">Variante</th>
                                                <th class="text-tr">Notas</th>
                                                <th class="text-tr">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($transfer->suppliedItems as $suppliedItem)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $suppliedItem->item->product->full_name }}</td>
                                                    <td>
                                                        <center>
                                                            <x-common.common-badge backgroundColor="{{ $suppliedItem->item->variant->color->hexadecimal }}">
                                                                {{ $suppliedItem->item->variant->size->full_name }}
                                                            </x-common.common-badge>
                                                        </center>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ Str::limit($suppliedItem->details, 50) ?: 'Ninguna Nota' }}
                                                    </td>
                                                    <td>
                                                        <span class="badge alert-{{ $suppliedItem->statusColor }} cursor-default">
                                                            {{ $suppliedItem->statusName }}
                                                            @if ($suppliedItem->integrity)
                                                                : {{ $suppliedItem->integrity_name }}
                                                            @endif
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%" class="text-center">
                                                        Sin artículos en esta transferencia
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
</body>

</html>
