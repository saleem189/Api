<?php

namespace App\Models;

use App\Transformers\TransactionTransformerClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;


      /**
     * defining/linking Transforers in model 
     */

     public $transformer = TransactionTransformerClass::class;

  


     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id'    
    ];

    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

  
    public function buyer(){
        return $this->belongsTo(Buyer::class);
    }

    public function products(){
        return $this->belongsTo(Product::class);
    }
}
