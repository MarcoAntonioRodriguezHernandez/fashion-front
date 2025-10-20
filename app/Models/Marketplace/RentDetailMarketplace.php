<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RentDetailMarketplace extends Model
{
    use HasFactory;

    /**
     * The name of the table in the database for the template `RentDetailMarketplace`.
     *
     * @var string
     */
    protected $table = 'rent_detail_marketplace';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_order_marketplace_id',
        'date_start',
        'date_end',
        'insurance_price',
        'description',
        'status',
    ];
    
    /**
    * The attributes that should be cast.
    *
    * @var array
    */
   protected $casts = [
       'date_start' => 'datetime:Y-m-d',
       'date_end' => 'datetime:Y-m-d',
   ];

    /*
    *Relationship with 'product_order_marketplace' table.
    *
    *@var string
    */
    public function itemOrderMarketplace()
    {
        return $this->belongsTo(ItemOrderMarketplace::class, 'item_order_marketplace_id');
    }
}
