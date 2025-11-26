<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = ['type', 'message', 'order_id', 'is_read'];
}