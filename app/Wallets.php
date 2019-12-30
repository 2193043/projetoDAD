<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
class Wallets extends Model
{
    protected $fillable = [
        'id','email', 'balance'];
}

