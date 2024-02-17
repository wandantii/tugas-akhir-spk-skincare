<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kategoriproduk;
use App\Models\Kriteria;
use App\Models\Nilaikriteria;
use App\Models\Produk;
use App\Models\Review;
use App\Models\Subkriteria;
use App\Models\User;
use App\Models\ViewAlternatifs;
use App\Models\ViewBrands;
use App\Models\ViewKriterias;
use App\Models\ViewNilais;
use App\Models\ViewProduks;
use App\Models\ViewReviews;
use App\Models\ViewUsers;

class UserController extends Controller
{
  /* FRONT */
  /* Show data profil untuk user */
  public function profiluser() {
    if(isset(auth()->user()->id_user)) {
      $id_user = auth()->user()->id_user;
      // $data = ViewUsers::where('id_user', '=', $id_user)->first();
      // $datauser = User::find($id_user);
      $data = DB::table('tbl_user')
              ->leftJoin('tbl_subkriteria as tipekulit', 'tbl_user.tipekulit', '=', 'tipekulit.id_subkriteria')
              ->leftJoin('tbl_subkriteria as jeniskelamin', 'tbl_user.jeniskelamin', '=', 'jeniskelamin.id_subkriteria')
              ->select('tbl_user.*', 'tipekulit.nama as namatipekulit', 'jeniskelamin.nama as namajeniskelamin')
              ->where('tbl_user.id_user', $id_user)
              ->first();
      // $tipekulits = ViewKriterias::where('id_kriteria', '2')->get();
      $tipekulits = DB::table('tbl_subkriteria')
                    ->join('tbl_kriteria', 'tbl_kriteria.id_kriteria', '=', 'tbl_subkriteria.id_kriteria')
                    ->select('tbl_subkriteria.*', 'tbl_kriteria.nama as namakriteria', 'tbl_kriteria.tipe', 'tbl_kriteria.bobot')
                    ->where('tbl_subkriteria.id_kriteria', '2')
                    ->get();
      // $jeniskelamins = ViewKriterias::where('id_kriteria', '3')->get();
      $jeniskelamins = DB::table('tbl_subkriteria')
                       ->join('tbl_kriteria', 'tbl_kriteria.id_kriteria', '=', 'tbl_subkriteria.id_kriteria')
                       ->select('tbl_subkriteria.*', 'tbl_kriteria.nama as namakriteria', 'tbl_kriteria.tipe', 'tbl_kriteria.bobot')
                       ->where('tbl_subkriteria.id_kriteria', '3')
                       ->get();
      return view('front.auth.profil', compact(
        'data', 'jeniskelamins', 'tipekulits'
      ));
    } else {
      return redirect('/');
    }
  }

  /* Update data profil untuk user */
  public function updateuser(Request $request, $id_user) {
    if(isset(auth()->user()->id_user)) {
      if($request->email == $request->oldemail && $request->username == $request->oldusername) {
        $validatedData = $request->validate([
          'namalengkap' => 'required|min:5|max:225',
          'usia' => 'required|numeric|min:10|max:225'
        ]);
      } else if($request->email == $request->oldemail) {
        $validatedData = $request->validate([
          'username' => ['required','min:3','max:225','unique:tbl_user'],
          'namalengkap' => 'required|min:5|max:225',
          'usia' => 'required|numeric|min:10|max:225'
        ]);
      } else if($request->username == $request->oldusername) {
        $validatedData = $request->validate([
          'email' => 'required|email:dns|unique:tbl_user',
          'namalengkap' => 'required|min:5|max:225',
          'usia' => 'required|numeric|min:10|max:225'
        ]);
      } else {
        $validatedData = $request->validate([
          'email' => 'required|email:dns|unique:tbl_user',
          'username' => ['required','min:3','max:225','unique:tbl_user'],
          'namalengkap' => 'required|min:5|max:225',
          'usia' => 'required|numeric|min:10|max:225'
        ]);
      }
      $data = User::find($id_user);
      $data->username = $request->username;
      $data->email = $request->email;
      $data->namalengkap = $request->namalengkap;
      $data->jeniskelamin = $request->jeniskelamin;
      $data->tipekulit = $request->tipekulit;
      $data->usia = $request->usia;
      $data->save();
      // dd($data);
      return redirect('profile')->with('success','Berhasil memperbarui profile.');
    } else {
      return redirect('/');
    }
  }



  /* ADMIN */
  /* Display a listing of the resource */
  public function index() {
    if(isset(auth()->user()->id_user)) {
      $level_user = auth()->user()->level;
      if($level_user == 'Admin') {
        // $data = ViewUsers::orderBy('created_at', 'desc')->get();
        $data = DB::table('tbl_user')
                ->leftJoin('tbl_subkriteria as tipekulit', 'tbl_user.tipekulit', '=', 'tipekulit.id_subkriteria')
                ->leftJoin('tbl_subkriteria as jeniskelamin', 'tbl_user.jeniskelamin', '=', 'jeniskelamin.id_subkriteria')
                ->select('tbl_user.*', 'tipekulit.nama as namatipekulit', 'jeniskelamin.nama as namajeniskelamin')
                ->orderBy('created_at', 'desc')->get();
        return view('admin.user.index', compact(
          'data'
        ));
      } else {
        return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
      }
    } else {
      return redirect('/');
    }
    // return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
  }

  /* Display the specified resource */
  public function show($id_user) {
    if(isset(auth()->user()->id_user)) {
      $level_user = auth()->user()->level;
      if($level_user == 'Admin') {
        // $data = ViewUsers::where('id_user', '=', $id_user)->first();
        $data = DB::table('tbl_user')
                ->leftJoin('tbl_subkriteria as tipekulit', 'tbl_user.tipekulit', '=', 'tipekulit.id_subkriteria')
                ->leftJoin('tbl_subkriteria as jeniskelamin', 'tbl_user.jeniskelamin', '=', 'jeniskelamin.id_subkriteria')
                ->select('tbl_user.*', 'tipekulit.nama as namatipekulit', 'jeniskelamin.nama as namajeniskelamin')
                ->where('id_user', '=', $id_user)->first();
        return view('admin.user.show', compact(
          'data'
        ));
      } else {
        return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
      }
    } else {
      return redirect('/');
    }
  }

  /* Show data profil untuk admin */
  public function profiladmin() {
    if(isset(auth()->user()->id_user)) {
      $level_user = auth()->user()->level;
      if($level_user == 'Admin') {
        $id_user = auth()->user()->id_user;
        // $data = ViewUsers::where('id_user', '=', $id_user)->first();
        $data = DB::table('tbl_user')
                ->join('tbl_subkriteria as tipekulit', 'tbl_user.tipekulit', '=', 'tipekulit.id_subkriteria')
                ->join('tbl_subkriteria as jeniskelamin', 'tbl_user.jeniskelamin', '=', 'jeniskelamin.id_subkriteria')
                ->select('tbl_user.*', 'tipekulit.nama as namatipekulit', 'jeniskelamin.nama as namajeniskelamin')
                ->where('tbl_user.id_user', $id_user)
                ->first();
        $datauser = User::find($id_user);
        // $tipekulits = ViewKriterias::where('id_kriteria', '2')->get();
        // $jeniskelamins = ViewKriterias::where('id_kriteria', '3')->get();
        $tipekulits = DB::table('tbl_subkriteria')
                      ->join('tbl_kriteria', 'tbl_kriteria.id_kriteria', '=', 'tbl_subkriteria.id_kriteria')
                      ->select('tbl_subkriteria.*', 'tbl_kriteria.nama as namakriteria', 'tbl_kriteria.tipe', 'tbl_kriteria.bobot')
                      ->where('tbl_subkriteria.id_kriteria', '2')
                      ->get();
        $jeniskelamins = DB::table('tbl_subkriteria')
                         ->join('tbl_kriteria', 'tbl_kriteria.id_kriteria', '=', 'tbl_subkriteria.id_kriteria')
                         ->select('tbl_subkriteria.*', 'tbl_kriteria.nama as namakriteria', 'tbl_kriteria.tipe', 'tbl_kriteria.bobot')
                         ->where('tbl_subkriteria.id_kriteria', '3')
                         ->get();
        return view('admin.auth.profil', compact(
          'data', 'datauser', 'jeniskelamins', 'tipekulits'
        ));
      } else {
        return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
      }
    } else {
      return redirect('/');
    }
  }

  /* Update data profil untuk admin */
  public function updateadmin(Request $request, $id_user) {
    if(isset(auth()->user()->id_user)) {
      $level_user = auth()->user()->level;
      if($level_user == 'Admin') {
        if($request->email == $request->oldemail && $request->username == $request->oldusername) {
          $validatedData = $request->validate([
            'namalengkap' => 'required|min:5|max:225',
            'usia' => 'required|numeric|min:10|max:225'
          ]);
        } else if($request->email == $request->oldemail) {
          $validatedData = $request->validate([
            'username' => ['required','min:3','max:225','unique:tbl_user'],
            'namalengkap' => 'required|min:5|max:225',
            'usia' => 'required|numeric|min:10|max:225'
          ]);
        } else if($request->username == $request->oldusername) {
          $validatedData = $request->validate([
            'email' => 'required|email:dns|unique:tbl_user',
            'namalengkap' => 'required|min:5|max:225',
            'usia' => 'required|numeric|min:10|max:225'
          ]);
        } else {
          $validatedData = $request->validate([
            'email' => 'required|email:dns|unique:tbl_user',
            'username' => ['required','min:3','max:225','unique:tbl_user'],
            'namalengkap' => 'required|min:5|max:225',
            'usia' => 'required|numeric|min:10|max:225'
          ]);
        }
        $data = User::find($id_user);
        $data->username = $request->username;
        $data->email = $request->email;
        $data->namalengkap = $request->namalengkap;
        $data->jeniskelamin = $request->jeniskelamin;
        $data->tipekulit = $request->tipekulit;
        $data->usia = $request->usia;
        $data->save();
        // dd($data);
        return redirect('admin/profile')->with('success','Berhasil memperbarui profile.');
      } else {
        return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
      }
    } else {
      return redirect('/');
    }
  }
}
