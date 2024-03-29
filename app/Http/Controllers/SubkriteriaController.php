<?php

namespace App\Http\Controllers;

use DB;
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


class SubkriteriaController extends Controller
{
  /* Display a listing of the resource */
  public function index() {
    if(isset(auth()->user()->id_user)) {
      $level_user = auth()->user()->level;
      if($level_user == 'Admin') {
        // $data = Subkriteria::orderBy('created_at', 'desc')->get();
        $data = DB::table('tbl_subkriteria')
                ->join('tbl_kriteria', 'tbl_subkriteria.id_kriteria', '=', 'tbl_kriteria.id_kriteria')
                ->select('tbl_subkriteria.*', 'tbl_kriteria.nama as namakriteria', 'tbl_kriteria.tipe', 'tbl_kriteria.bobot')
                ->get();
        return view('admin.subkriteria.index', compact(
          'data'
        ));
      } else {
        return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
      }
    } else {
      return redirect('/');
    }
  }

  /* Show the form for creating a new resource */
  public function create() {
    if(isset(auth()->user()->id_user)) {
      $level_user = auth()->user()->level;
      if($level_user == 'Admin') {
        $data = new Subkriteria;
        $kriterias = Kriteria::all();
        return view('admin.subkriteria.create', compact(
          'data', 'kriterias'
        ));
      } else {
        return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
      }
    } else {
      return redirect('/');
    }
  }

  /* Store a newly created resource in storage */
  public function store(Request $request) {
    if(isset(auth()->user()->id_user)) {
      $level_user = auth()->user()->level;
      if($level_user == 'Admin') {
        /* validasi data */
        $validatedData = $request->validate([
          'id_kriteria' => 'required|min:1|max:225',
          'nama' => 'required|min:3|max:225'
        ]);
        /* input data */
        $data = new Subkriteria;
        $data->id_kriteria = $request->id_kriteria;
        $data->nama = $request->nama;
        $data->save();
        return redirect('admin/subkriteria')->with('success','Berhasil menambah data.');
      } else {
        return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
      }
    } else {
      return redirect('/');
    }
  }

  /* Display the specified resource */
  public function show($id_subkriteria) {
    if(isset(auth()->user()->id_user)) {
      $level_user = auth()->user()->level;
      if($level_user == 'Admin') {
        $data = Subkriteria::find($id_subkriteria);
        return view('admin.subkriteria.show', compact(
          'data'
        ));
      } else {
        return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
      }
    } else {
      return redirect('/');
    }
  }

  /* Show the form for editing the specified resource */
  public function edit($id_subkriteria) {
    if(isset(auth()->user()->id_user)) {
      $level_user = auth()->user()->level;
      if($level_user == 'Admin') {
        $data = Subkriteria::find($id_subkriteria);
        $kriterias = Kriteria::all();
        return view('admin.subkriteria.edit', compact(
          'data', 'kriterias'
        ));
      } else {
        return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
      }
    } else {
      return redirect('/');
    }
  }

  /* Update the specified resource in storage */
  public function update(Request $request, $id_subkriteria) {
    if(isset(auth()->user()->id_user)) {
      $level_user = auth()->user()->level;
      if($level_user == 'Admin') {
        /* validasi data */
        $validatedData = $request->validate([
          'id_kriteria' => 'required|min:1|max:225',
          'nama' => 'required|min:3|max:225'
        ]);
        /* input data */
        $data = Subkriteria::find($id_subkriteria);
        $data->id_kriteria = $request->id_kriteria;
        $data->nama = $request->nama;
        $data->save();
        return redirect('admin/subkriteria')->with('success','Berhasil mengubah data.');
      } else {
        return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
      }
    } else {
      return redirect('/');
    }
  }

  /* Remove the specified resource from storage */
  public function destroy($id_subkriteria) {
    if(isset(auth()->user()->id_user)) {
      $level_user = auth()->user()->level;
      if($level_user == 'Admin') {
        $data = Subkriteria::find($id_subkriteria);
        $data->delete();
        return redirect('admin/subkriteria')->with('success','Berhasil menghapus data.');
      } else {
        return redirect('admin/login')->with('error', 'Maaf, anda bukan admin.');
      }
    } else {
      return redirect('/');
    }
  }
}
