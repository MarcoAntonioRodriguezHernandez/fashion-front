<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $ticket->title }}</title>

    @yield('styles')
</head>

<body>
    <div class="logo-container">
        <img src="{{ 'media/logos/cmLogo.png' }}" alt="{{ config('app.name') }}" width="120">
    </div>

    <div class="ticket-title">{{ $ticket->title }}</div>

    @if ($ticket->notes)
        <div class="ticket-notes">{!! $ticket->notes !!}</div>
    @endif

    @yield('content')
        
</body>

</html>
