<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Category;



class CategoryController extends Controller
{
    public function index(Request $request)
    {
        
       
        if ($request->ajax()) {
           $mainQuery = DB::table('categories')->whereNull('categories.deleted_at')
           ->select([
               'categories.id',
               'categories.category_title',
               'categories.description',
               'categories.created_at',
               'categories.updated_at',   
           ]);
           $data = $mainQuery->get();
           return Datatables::of($data)
                    ->setRowId(function ($row) {
                        return $row->id;
                    })
                    ->addColumn('index', function ($row) {
                        static $index = 0;
                        return ++$index;
                    })
                    ->addColumn('category_title', function ($row) {
                        return $row->category_title;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="' . route("category.edit", ["id" => $row->id]) . '">
                        <i class="mdi me-2 mdi-account-edit" style="font-size:24px;color:green;"></i></a>';

                        $btn .=  '<a  class="delete-button" data-id="' . $row->id .'"><i class="mdi me-2 mdi-delete" style="font-size:24px;"></i></a>';

                         return $btn;
                    })
                    ->rawColumns(['action','category_title','status','index'])
                    ->make(true);
        }
        return view('admin.category.index', [
        ]);
    }


    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "category_title" => 'required',
            "description" => 'required',
        ]);
        if($validator->passes()){
            $ProductCreate = new Category();
            $ProductCreate->category_title = $request->category_title;
            $ProductCreate->description = $request->description;
            $ProductCreate->save();
            return response()->json([
                'status' => true,
                'message' => "Category created successfully",
            ], 201);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }
    }

    public function edit($id)
    {
        $edit = Category::whereNull('deleted_at')
            ->where('id', $id)
            ->first();
        return view('admin.category.update', compact('edit'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "category_title" => 'required',
            "description" => 'required',
        ]);
        if($validator->passes()){
            $ProductUpdate = Category::find($request->category_id);
            $ProductUpdate->category_title = $request->category_title;
            $ProductUpdate->description = $request->description;
            $ProductUpdate->save();
            return response()->json([
                'status' => true,
                'message' => "Category update successfully",
            ], 201);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
             ]);
        
         }
    }

    public function delete(Request $request)
    {

            $id = $request->id;
            $model = Category::find($id);
            $check_category = DB::table('products')->where('category_id', $id)->count();
            if ($check_category >=1) {
                echo 'false';
                die; 
            }else{
                $model->delete();
                echo 'removed';
                die; 
            }
    
        
    }
}
