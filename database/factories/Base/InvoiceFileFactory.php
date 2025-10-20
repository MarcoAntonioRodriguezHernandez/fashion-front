<?php

namespace Database\Factories\Base;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceFile>
 */
class InvoiceFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(){

     //Generate a random file name
        $fileName = fake()->word . '.' . fake()->fileExtension;

        // Storing a dummy file on the local storage disk
        Storage::disk('local')->put($fileName, '');
    {
        return [
                'file' => $fileName,
        ];
    }
}
}
