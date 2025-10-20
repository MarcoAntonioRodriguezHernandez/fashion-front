<?php


namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'file'
    ];

    public function getFileAttribute($value)
    {
        $baseUrl = config('filesystems.disks.do.url');
        return $value ? rtrim($baseUrl, '/') . '/' . ltrim($value, '/') : null;
    }
}
