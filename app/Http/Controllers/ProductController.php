<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;



class ProductController extends Controller
{
    public function index(Request $request)
    {
        
       
        if ($request->ajax()) {
           $mainQuery = DB::table('products')->whereNull('products.deleted_at')
           ->select([
               'products.id',
               'products.image',
               'products.name',
               'products.price',
               'products.category_id',
               'products.description',
               'products.created_at',
               'products.updated_at', 
               'categories.category_title',  
           ])
           
           ->leftJoin('categories', 'categories.id', 'products.category_id');
           $data = $mainQuery->get();
           return Datatables::of($data)
                    // ->setRowId(function ($row) {
                    //     return $row->id;
                    // })

                    ->editColumn('image', function ($row) {
                        $ImagePath = $row->image ? $row->image : 'no_image.png';
                        return '<img src="' . url('uploads/product/' . $ImagePath) . '" width="32" height="32" class="bg-light my-n1"
                        alt="' . $row->name . '">';
                    })
                    ->addColumn('index', function ($row) {
                        static $index = 0;
                        return ++$index;
                    })
                    ->addColumn('category_title', function ($row) {
                        return $row->category_title;
                    })
                    ->addColumn('name', function ($row) {
                        return $row->name;
                    })
                    ->addColumn('price', function ($row) {
                        return $row->price;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="' . route("product.edit", ["id" => $row->id]) . '">
                        <i class="mdi me-2 mdi-account-edit" style="font-size:24px;color:green;"></i></a>';

                        $btn .=  '<a  class="delete-button" data-id="' . $row->id .'"><i class="mdi me-2 mdi-delete" style="font-size:24px;"></i></a>';

                         return $btn;
                    })
                    ->rawColumns(['action','name','price','status','image'])
                    ->make(true);
        }
        return view('admin.product.index', [
        ]);
    }


    public function create()
    {
       $categorys = Category::get();
        return view('admin.product.create', compact('categorys'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "category_id" => 'required',
            "name" => 'required',
            "price" => 'required',
            "description" => 'required',
            'image' => 'required|image|mimes:png,jpg,webp|max:250',
        ]);
        if($validator->passes()){

            if ($image = $request->file('image')) {
                $destinationPath = 'uploads/product/';
                $originalname = $image->hashName();
                $imageName = "plan_" . date('Ymd') . '_' . $originalname;
                $image->move($destinationPath, $imageName);
            }
            
            $ProductCreate = new Product();
            $ProductCreate->name = $request->name;
            $ProductCreate->price = $request->price;
            $ProductCreate->category_id = $request->category_id;
            $ProductCreate->description = $request->description;
            $ProductCreate->image = $imageName;
            $ProductCreate->save();
            return response()->json([
                'status' => true,
                'message' => "Product created successfully",
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
        $edit = Product::whereNull('deleted_at')
            ->where('id', $id)
            ->first();
         $categorys = Category::get();

        return view('admin.product.update', compact('edit','categorys'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "category_id" => 'required',
            "name" => 'required',
            "price" => 'required',
            "description" => 'required',
        ]);
        if($validator->passes()){


            if ($image = $request->file('image')) {
                $destinationPath = 'uploads/product/';
                $originalname = $image->hashName();
                $imageName = "plan_" . date('Ymd') . '_' . $originalname;
                $image->move($destinationPath, $imageName);
                $image_path = $destinationPath . $request->old_image;
                @unlink($image_path);
            }else {
                $imageName = $request->old_image;
            }

            $ProductUpdate = Product::find($request->product_id);
            $ProductUpdate->name = $request->name;
            $ProductUpdate->price = $request->price;
            $ProductUpdate->category_id = $request->category_id;
            $ProductUpdate->description = $request->description;
            $ProductUpdate->image = $imageName;
            $ProductUpdate->save();
            return response()->json([
                'status' => true,
                'message' => "Product update successfully",
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
            $model = Product::find($id);
            $model->delete();
            echo 'removed';
            die; 
    }
}
