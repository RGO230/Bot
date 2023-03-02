<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tguser extends Model
{
    protected $fillable=['isvip','user_id','username','counter'];
    use HasFactory;
    public const VIP = [0,1];
}