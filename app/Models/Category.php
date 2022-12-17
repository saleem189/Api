<?php

namespace App\Models;

use App\Transformers\CategoryTransformerClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes ;


     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        // 'remember_token',
            'pivot'
    ];

    /**
     * defining/linking Transforers in model 
     */

    public $transformer = CategoryTransformerClass::class;


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    protected $dates=['deleted_at'];

    public function products(){
        return $this->belongsToMany(Product::class);
    }
}
