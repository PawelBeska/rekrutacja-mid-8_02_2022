<?php

namespace App\Models;

use App\Http\Traits\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @property mixed $content
 * @property mixed $author
 * @property mixed $title
 */
class Message extends Model
{
    use HasFactory, Notifiable, HasUuidPrimaryKey;

    protected $primaryKey = 'uuid';



}
