<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $table = 'transaction_log';
    protected $fillable = ['user_id', 'transaction_id', 'transaction_table', 'transaction_data', 'transaction_activity_type_id'];
}