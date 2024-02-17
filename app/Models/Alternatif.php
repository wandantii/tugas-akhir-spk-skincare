<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model {
  use HasFactory;

  protected $table = "tbl_alternatif";
  protected $primaryKey = "id_alternatif";
}