<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Obat extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'obat';

    protected $guarded =['id'];
}
