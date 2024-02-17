<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\DB;

class Review extends Model {
  use HasFactory;

  protected $table = "tbl_review";
  protected $primaryKey = "id_review";

  public function getUser() { 
    return $this->join('tbl_user', 'tbl_review.id_user', '=', 'tbl_user.id_user')->select('tbl_review.*', 'tbl_user.namalengkap', 'tbl_user.tipekulit', 'tbl_user.jeniskelamin', 'tbl_user.usia');
  }
}