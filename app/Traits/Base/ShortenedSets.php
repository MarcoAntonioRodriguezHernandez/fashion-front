<?php

namespace App\Traits\Base;

use InvalidArgumentException;
use Illuminate\Support\Str;

trait ShortenedSets
{

    public function encodeShortenArray(array $values)
    {
        $encoded = collect();

        foreach ($values as $value) {
            if (!is_numeric($value))
                throw new InvalidArgumentException('Every value must be numeric, received ' . $value);

            $encoded->push(base_convert($value, 10, $this->getToBase()));
        }

        return $encoded->join($this->getDelimiter()) ?: null;
    }

    public function decodeShortenArray(string $values)
    {
        $decoded = Str::of($values)->explode($this->getDelimiter());

        foreach ($decoded as $key => $value) {
            $value = intval($value, $this->getToBase());

            if (!is_numeric($value))
                throw new InvalidArgumentException('Every value must be numeric, received ' . $value);

            $decoded->put($key, $value);
        }

        return $decoded;
    }

    protected function getDelimiter()
    {
        return '-';
    }

    protected function getToBase()
    {
        return 36;
    }
}
