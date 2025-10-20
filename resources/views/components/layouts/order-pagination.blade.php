@props([
    'createText' => '',
    'module' => null,
    'min'=> 1,
    'max'=> 4261,
    'rangeMode' => false,
    'data' => null,
])

<style>
    .rango-form {
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: Inter, Helvetica, sans-serif;
        font-size: 12px;
        flex-wrap: wrap;
    }

    .rango-label {
        font-weight: 600;
        color: #333;
    }

    .rango-input {
        width: 60px;
        padding: 5px 6px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 12px;
    }

    .rango-button {
        color: #fff;
        font-size: 12px;
        background-color: #000;
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .rango-button:hover {
        background-color: #222;
    }
</style>

<form method="GET" action="{{ route('marketplace.order_marketplace.index') }}" class="rango-form">
    <label for="range_start" class="rango-label">De:</label>
    <input type="number" name="range_start" min="{{ $min }}" max="{{ $max }}" class="rango-input">

    <label for="range_end" class="rango-label">A:</label>
    <input type="number" name="range_end" min="{{ $min }}" max="{{ $max }}" class="rango-input">

    <button type="submit" class="rango-button">Ver productos</button>
</form>


