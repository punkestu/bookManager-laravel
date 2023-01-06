<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        "book_name", "book_author", "book_price"    
    ];

    public function genres()
    {
        return $this->hasMany(BookGenre::class, "book_id");
    }
    public function author()
    {
        return $this->belongsTo(Author::class, "book_author");
    }
}
