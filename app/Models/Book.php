<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'author',
        'category',
        'description',
        'status',
        'image',
    ];
   public function borrowings  (){
        return $this->hasMany(Borrowing::class);
    }

}
