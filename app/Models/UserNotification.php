<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $fillable = ['user_id', 'order_id', 'title', 'message', 'is_read'];
}