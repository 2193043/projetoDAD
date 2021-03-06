<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
class Movements extends Model
{
    protected $fillable = [
        'wallet_id', 'type', 'transfer', 'transfer_movement_id', 'transfer_wallet_id', 'type_payment', 
        'category_id', 'iban', 'mb_entity_code', 'mb_payment_reference', 'description', 'source_description', 
        'date', 'start_balance', 'end_balance', 'value'];
}

