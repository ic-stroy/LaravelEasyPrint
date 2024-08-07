<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Color;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Constants;
use App\Models\Products;


class ProductController extends Controller
{
    /**
     * bu funksiya frontga product list(shablonlar ro'yxati) ni berishda   qo'llaniladi sartirofka qilinadi
     */
    public function index(Request $request)
    {
        $language=$request->header('language');
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }

        // $products_ = Products::select('id','name')->with('discount')->get();
        $products_ = DB::table('products')
        ->select('id','name')
        ->get();

        $colors = DB::table('colors')
        ->select('id','code','name')
        ->get();

        $sizes = DB::table('sizes')
        ->select('id','name')
            ->where('status', 1)
        ->get();

        foreach ($products_ as $product_) {
            $translate_name=table_translate($product_,'product',$language);
            $products[] = [
                'id' => $product_->id,
                'name' => $translate_name
            ];
        }
        $data=[
            'product_list'=>$products,
            'colors'=>$colors,
            'sizes'=>$sizes
        ];
        $message=translate_api('success',$language);
        return $this->success($message, 200,$data);

    }

    /**
     * bu funksiya frontga getWarehouses(Kompaniyalarga tegishli tavarlar  ro'yxati) ni  va slide_show active bo'lgan product(shablonlar) ni berishda qo'llaniladi
     */
    public function getWarehouses(Request $request)
    {
        $language = $request->header('language');
        $warehouse_products_id = DB::table('warehouses')
//            ->select(DB::raw('MAX(id) as id')) // assuming 'id' is the primary key
            ->groupBy('company_id', 'product_id')
            ->whereNull('deleted_at')
            ->where('type', Constants::WAREHOUSE_TYPE)
            ->where('quantity', '>', 0)
            ->pluck(DB::raw('MIN(id) as id'))
            ->all();
        $warehouse_anime_products_id = DB::table('warehouses')
            ->whereNull('deleted_at')
            ->where('type', Constants::PRINT_TYPE)
            ->where('quantity', '>', 0)
            ->pluck('id')
            ->all();
        $warehouse_products = [];
        $warehouse_products_ = Warehouse::whereIn('id', array_merge($warehouse_products_id, $warehouse_anime_products_id))->get();
//        dd($warehouse_products_id, $warehouse_anime_products_id);
        foreach ($warehouse_products_ as $warehouse_product_) {
            $translate_category_name = '';
            if($warehouse_product_->type == Constants::WAREHOUSE_TYPE){
                if (count($this->getImages($warehouse_product_, 'warehouse'))>0) {
                    $warehouseProducts = $this->getImages($warehouse_product_, 'warehouse');
                } else {
                    $parentProduct = Products::find($warehouse_product_->product_id);
                    if($parentProduct){
                        $warehouseProducts = $this->getImages($parentProduct, 'product');
                    }
                }
            }else{
                $warehouseProducts = [];
                if (!$warehouse_product_->image_front) {
                    $warehouse_product_->image_front = 'no';
                }
                $model_image_front = storage_path('app/public/warehouse/'.$warehouse_product_->image_front);
                if (!$warehouse_product_->image_back) {
                    $warehouse_product_->image_back = 'no';
                }
                $model_image_back = storage_path('app/public/warehouse/'.$warehouse_product_->image_back);
                if(file_exists($model_image_front)){
                    $warehouseProducts[] = asset("/storage/warehouse/$warehouse_product_->image_front");
                }
                if(file_exists($model_image_back)){
                    $warehouseProducts[] = asset("/storage/warehouse/$warehouse_product_->image_back");
                }
            }
            $warehouse__name = '';
            $translate_name=table_translate($warehouse_product_,'warehouse_category', $language);
            if($translate_name){
                $warehouse__name = $translate_name;
            }elseif($warehouse_product_->name){
                $warehouse__name = $warehouse_product_->name;
            }elseif($warehouse_product_->product->name){
                $warehouse__name = $warehouse_product_->product->name;
            }
            if($warehouse_product_->product){
                if($warehouse_product_->product->getCategory){
                    $category__ = $warehouse_product_->product->getCategory;
                    if($category__->step == 0){
                        $translate_category_name = table_translate($category__, 'category', $language);
                    }elseif($category__->step == 1){
                        if($category__->category){
                            $translate_category_name = table_translate($category__->category, 'category', $language);
                        }
                    }
                }
            }
            //  join qilish kere
            $warehouse_products[] = [
                // 'product_id' => $warehouse_product_->product_id,
                'id' => $warehouse_product_->id,
                'name' => $warehouse__name,
                'category_name'=>$translate_category_name,
                'price' => $warehouse_product_->price,
                'discount' => $warehouse_product_->product_discount ? $warehouse_product_->product_discount->percent : NULL,
                'price_discount' => $warehouse_product_->product_discount ? $warehouse_product_->price - ($warehouse_product_->price / 100 * $warehouse_product_->product_discount->percent) : NULL,
                'images' => $warehouseProducts
            ];
        }
        $product_ = Products::where('name', 'Футболка')->first();
        $product = [];
        $translate_product_category_name = '';
        if($product_){
            if($warehouse_product_->getCategory){
                $category___ = $warehouse_product_->getCategory;
                if($category___->step == 0){
                    $translate_product_category_name = table_translate($category___, 'category', $language);
                }elseif($category___->step == 1){
                    if($category___->category){
                        $translate_product_category_name = table_translate($category__->category, 'category', $language);
                    }
                }
            }
            $product[] = [
                'id' => $product_->id,
                'name' => $product_->name,
                'category_name'=>$translate_product_category_name,
                'price' => $product_->price,
                'discount' => $product_->discount ? $product_->discount->percent : NULL,
                'price_discount' => $product_->discount ? $product_->price - ($product_->price / 100 * $product_->discount->percent) : NULL,
                'images' => $this->getImages($product_, 'product')
            ];
        }

        $data = [
            'product_list' => $product,
            'warehouse_product_list' => $warehouse_products
        ];

        $message = translate_api('success', $language);
        return $this->success($message, 200, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * bu funksiya frontga Kompaniyaga tegishli bitta productni show qilingandagi malumotlarni berishda qo'llaniladi
     */
    public function show(Request $request)
    {
        $language = $request->header('language');
        if ($language == null) {
            $language=env("DEFAULT_LANGUAGE", 'ru');
        }
        $warehouse_product_id = $request->warehouse_product_id;
        if ($warehouse_product_id) {
            $warehouse_product = DB::table('warehouses as dt2')
            ->join('sizes as dt3', 'dt3.id', '=', 'dt2.size_id')
            ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
            ->join('products as dt5', 'dt5.id', '=', 'dt2.product_id')
                ->leftJoin('discounts as dt6', function($join){
                    $join->on(function ($join){
                        $join->on([
                            ['dt6.warehouse_id', '=', 'dt2.id'],
                            ['dt6.company_id', 'is', DB::raw('NULL')]
                        ])
                        ->orOn(function ($join){
                            $join->on([
                                ['dt6.warehouse_id', '=', 'dt2.id'],
                                ['dt6.company_id', 'is not', DB::raw('NULL')],
                                ['dt2.company_id', '=', 'dt6.company_id']
                            ]);
                        });
                    })
                    ->where('dt6.type', '=', Constants::DISCOUNT_WAREHOUSE_TYPE)
                    ->where('start_date', '<=', date('Y-m-d H:i:s'))
                    ->where('end_date', '>=', date('Y-m-d H:i:s'))
                    ->orOn(function ($join){
                        $join->on([
                            ['dt6.product_id', '=', 'dt2.product_id'],
                            ['dt6.product_id', 'is not', DB::raw('NULL')],
                            ['dt6.company_id', 'is', DB::raw('NULL')]
                        ])
                        ->orOn(function ($join){
                            $join->on([
                                ['dt6.product_id', '=', 'dt2.product_id'],
                                ['dt6.product_id', 'is not', DB::raw('NULL')],
                                ['dt6.company_id', 'is not', DB::raw('NULL')],
                                ['dt2.company_id', '=', 'dt6.company_id'],
                            ]);
                        });
                    })
                    ->where('dt6.type', '=', Constants::DISCOUNT_PRODUCT_TYPE)
                    ->where('start_date', '<=', date('Y-m-d H:i:s'))
                    ->where('end_date', '>=', date('Y-m-d H:i:s'));
                })
            ->leftJoin('materials as dt7', 'dt7.id', '=','dt2.material_id')
            ->join('companies as dt8', 'dt8.id', '=','dt2.company_id')
            // ->leftJoin('coupons as dt5', 'dt5.warehouse_product_id', '=', 'dt2.id')
            ->where('dt2.id' , $warehouse_product_id)
            ->where('dt2.quantity' , '>', 0)
            ->select('dt2.id as warehouse_product_id','dt2.name as warehouse_product_name','dt2.quantity as quantity', 'dt2.images as images', 'dt2.description as description',
                'dt2.product_id as product_id', 'dt2.company_id as company_id', 'dt2.price as price','dt2.material_composition','dt2.manufacturer_country',
                'dt2.type as type', 'dt2.image_front as image_front', 'dt2.image_back as image_back', 'dt2.type as type',
                'dt8.name as company_name','dt7.name as material_name', 'dt3.id as size_id',
                'dt3.name as size_name','dt4.id as color_id','dt4.name as color_name','dt4.code as color_code',
                'dt5.name as product_name', 'dt5.category_id as category_id', 'dt5.images as product_images', 'dt5.description as product_description',
                'dt5.material_composition as product_material_composition','dt5.manufacturer_country as product_manufacturer_country','dt6.percent AS discount')
            ->first();
            $warehouse_products = [];
            $images = [];
            if($warehouse_product){
                $product__category = Category::find($warehouse_product->category_id);
                if($product__category->step == 0){
                    $product_category_translate_name = table_translate($product__category,'category', $language);
                    $product_category = [
                        'id'=>$product__category->id,
                        'name'=>$product_category_translate_name,
                    ];
                    $product_sub_category = NULL;
                }else{
                    $product_sub_category_translate_name = table_translate($product__category,'category', $language);
                    $product_sub_category = [
                        'id'=>$product__category->id,
                        'name'=>$product_sub_category_translate_name,
                    ];
                    $product_category_translate_name = table_translate($product__category->category,'category', $language);
                    $product_category = [
                        'id'=>$product__category->category->id,
                        'name'=>$product_category_translate_name,
                    ];
                }

                $warehouseProducts = $this->getWarehouseImages($warehouse_product);
//                if($warehouse_product->type == Constants::WAREHOUSE_TYPE){
//                    if (count($this->getImages($warehouse_product, 'warehouse'))>0) {
//                        $warehouseProducts = $this->getImages($warehouse_product, 'warehouse');
//                    } else {
//                        $parentProduct = Products::find($warehouse_product->product_id);
//                        if($parentProduct){
//                            $warehouseProducts = $this->getImages($parentProduct, 'product');
//                        }
//                    }
//                }else{
//                    $warehouseProducts = [];
//                    if (!$warehouse_product->image_front) {
//                        $warehouse_product->image_front = 'no';
//                    }
//                    $model_image_front = storage_path('app/public/warehouse/'.$warehouse_product->image_front);
//                    if (!$warehouse_product->image_back) {
//                        $warehouse_product->image_back = 'no';
//                    }
//                    $model_image_back = storage_path('app/public/warehouse/'.$warehouse_product->image_back);
//                    if(file_exists($model_image_front)){
//                        $warehouseProducts[] = asset("/storage/warehouse/$warehouse_product->image_front");
//                    }
//                    if(file_exists($model_image_back)){
//                        $warehouseProducts[] = asset("/storage/warehouse/$warehouse_product->image_back");
//                    }
//                }

                if ($warehouse_product->product_id) {
                    $sizes = DB::table('warehouses as dt1')
                        ->join('sizes as dt3', 'dt3.id', '=', 'dt1.size_id')
                        // ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                        ->where('dt1.product_id', $warehouse_product->product_id)
                        ->where('dt1.company_id', $warehouse_product->company_id)
                        ->where('dt1.type', $warehouse_product->type)
                        ->where('dt1.quantity' , '>', 0)
                        ->select('dt1.id as id','dt3.id as size_id', 'dt3.name as size_name')
                        ->distinct('size_id')
                        ->get();

                    $size_list=[];
                    $get_sizes=[];
                    foreach ($sizes as $size) {
                        $colors = DB::table('warehouses as dt1')
                            ->join('colors as dt4', 'dt4.id', '=', 'dt1.color_id')
                            ->leftJoin('discounts as dt6', function($join){
                                $join->on(function ($join){
                                    $join->on([
                                        ['dt6.warehouse_id', '=', 'dt1.id'],
                                        ['dt6.company_id', 'is', DB::raw('NULL')]
                                    ])
                                    ->orOn(function ($join){
                                        $join->on([
                                            ['dt6.warehouse_id', '=', 'dt1.id'],
                                            ['dt6.company_id', 'is not', DB::raw('NULL')],
                                            ['dt1.company_id', '=', 'dt6.company_id']
                                        ]);
                                    });
                                })
                                ->where('dt6.type', '=', Constants::DISCOUNT_WAREHOUSE_TYPE)
                                ->where('start_date', '<=', date('Y-m-d H:i:s'))
                                ->where('end_date', '>=', date('Y-m-d H:i:s'))
                                ->orOn(function ($join){
                                    $join->on([
                                        ['dt6.product_id', '=', 'dt1.product_id'],
                                        ['dt6.product_id', 'is not', DB::raw('NULL')],
                                        ['dt6.company_id', 'is', DB::raw('NULL')]
                                    ])
                                    ->orOn(function ($join){
                                        $join->on([
                                            ['dt6.product_id', '=', 'dt1.product_id'],
                                            ['dt6.product_id', 'is not', DB::raw('NULL')],
                                            ['dt6.company_id', 'is not', DB::raw('NULL')],
                                            ['dt1.company_id', '=', 'dt6.company_id'],
                                        ]);
                                    });
                                })
                                ->where('dt6.type', '=', Constants::DISCOUNT_PRODUCT_TYPE)
                                ->where('start_date', '<=', date('Y-m-d H:i:s'))
                                ->where('end_date', '>=', date('Y-m-d H:i:s'));
                            })
                            ->join('products as dt5', 'dt5.id', '=', 'dt1.product_id')
                            ->where('dt1.product_id', $warehouse_product->product_id)
                            ->where('dt1.company_id', $warehouse_product->company_id)
                            ->where('dt1.type', $warehouse_product->type)
                            ->where('dt1.size_id', $size->size_id)
                            ->where('dt1.quantity' , '>', 0)
                            ->select('dt1.description', 'dt1.id', 'dt4.id as color_id','dt4.code as color_code', 'dt4.name as color_name',
                                'dt1.images as images','dt1.price as price','dt1.name as name','dt1.quantity as quantity',
                                'dt5.material_composition','dt5.manufacturer_country','dt1.type as type',
                                'dt1.image_front as image_front', 'dt1.image_back as image_back', 'dt1.product_id as product_id',
                                'dt6.percent AS discount', 'dt5.name as product_name', 'dt5.description as product_description',
                                'dt5.material_composition as product_material_composition','dt5.manufacturer_country as product_manufacturer_country',)
                            ->distinct('color_id')
                            ->get();

                        $color_list=[];

                        foreach ($colors as $color) {
                            $colorWarehouseImages = $this->getWarehouseImages($color);
                            $aa_color = [
                                'id' => $color->color_id,
                                'code' => $color->color_code,
                                'product'=>[
                                    'id'=>$color->id,
                                    'name'=> $color->name?$color->name:$color->product_name,
                                    'quantity'=>$color->quantity,
                                    "price" => (int)$color->price,
                                    "img"=>$colorWarehouseImages,
                                    'discount' => $color->discount?$color->discount : NULL,
                                    'price_discount' => $color->discount ? $color->price - ($color->price / 100 * $color->discount) : NULL,
                                    'description' => $color->description?$color->description:$color->product_description,
                                    'material_composition' => $color->material_composition?$color->material_composition:$color->product_material_composition,
                                    'manufacturer_country' => $color->manufacturer_country?$color->manufacturer_country:$color->product_manufacturer_country
                                ],
                                'name' => $color->color_name,
                            ];
                            array_push($color_list,$aa_color);
                        }

                        $aa_size = [
                            'id' => $size->size_id,
                            'name' => $size->size_name,
                            'color' => $color_list
                        ];
                        $bb_size = [
                            'id' => $size->size_id,
                            'name' => $size->size_name
                        ];

                        array_push($size_list, $aa_size);
                        array_push($get_sizes, $bb_size);
                    }
                    $colors = DB::table('warehouses as dt1')
                        ->join('colors as dt3', 'dt3.id', '=', 'dt1.color_id')
                        // ->join('colors as dt4', 'dt4.id', '=', 'dt2.color_id')
                        ->where('dt1.product_id', $warehouse_product->product_id)
                        ->where('dt1.company_id', $warehouse_product->company_id)
                        ->where('dt1.type', $warehouse_product->type)
                        ->where('dt1.quantity' , '>', 0)
                        ->select('dt1.id as id','dt3.id as color_id','dt3.code as color_code', 'dt3.name as color_name')
                        ->distinct('color_id')
                        ->get();

                    $aaa_color_list = [];
                    $get_colors = [];
                    foreach ($colors as $color) {
                        $sizes = DB::table('warehouses as dt1')
                            ->join('sizes as dt4', 'dt4.id', '=', 'dt1.size_id')
                            ->leftJoin('discounts as dt6', function($join){
                                $join->on(function ($join){
                                    $join->on([
                                        ['dt6.warehouse_id', '=', 'dt1.id'],
                                        ['dt6.company_id', 'is', DB::raw('NULL')]
                                    ])
                                    ->orOn(function ($join){
                                        $join->on([
                                            ['dt6.warehouse_id', '=', 'dt1.id'],
                                            ['dt6.company_id', 'is not', DB::raw('NULL')],
                                            ['dt1.company_id', '=', 'dt6.company_id']
                                        ]);
                                    });
                                })
                                ->where('dt6.type', '=', Constants::DISCOUNT_WAREHOUSE_TYPE)
                                ->where('start_date', '<=', date('Y-m-d H:i:s'))
                                ->where('end_date', '>=', date('Y-m-d H:i:s'))
                                ->orOn(function ($join){
                                    $join->on([
                                        ['dt6.product_id', '=', 'dt1.product_id'],
                                        ['dt6.product_id', 'is not', DB::raw('NULL')],
                                        ['dt6.company_id', 'is', DB::raw('NULL')]
                                    ])
                                    ->orOn(function ($join){
                                        $join->on([
                                            ['dt6.product_id', '=', 'dt1.product_id'],
                                            ['dt6.product_id', 'is not', DB::raw('NULL')],
                                            ['dt6.company_id', 'is not', DB::raw('NULL')],
                                            ['dt1.company_id', '=', 'dt6.company_id'],
                                        ]);
                                    });
                                })
                                ->where('dt6.type', '=', Constants::DISCOUNT_PRODUCT_TYPE)
                                ->where('start_date', '<=', date('Y-m-d H:i:s'))
                                ->where('end_date', '>=', date('Y-m-d H:i:s'));
                            })
                            ->join('products as dt5', 'dt5.id', '=', 'dt1.product_id')
                            ->where('dt1.product_id', $warehouse_product->product_id)
                            ->where('dt1.company_id', $warehouse_product->company_id)
                            ->where('dt1.type', $warehouse_product->type)
                            ->where('dt1.color_id', $color->color_id)
                            ->where('dt1.quantity' , '>', 0)
                            ->select('dt1.description', 'dt1.id', 'dt4.id as size_id','dt4.name as size_name',
                                'dt1.images as images','dt1.price as price','dt1.name as name','dt1.quantity as quantity','dt1.type as type',
                                'dt1.image_front as image_front', 'dt1.image_back as image_back', 'dt1.product_id as product_id',
                                'dt6.percent AS discount', 'dt5.name as product_name', 'dt5.description as product_description',
                                'dt5.material_composition as product_material_composition','dt5.manufacturer_country as product_manufacturer_country',
                                'dt1.material_composition','dt1.manufacturer_country')
                            ->distinct('size_id')
                            ->get();

                        $aaa_size_list = [];
                        foreach ($sizes as $size) {
                            $sizeWarehouseImages = $this->getWarehouseImages($size);
                            $aas_size = [
                                'id' => $size->size_id,
                                'name' => $size->size_name,
                                'product'=>[
                                    'id'=>$size->id,
                                    'name'=> $size->name?$size->name:$size->product_name,
                                    'quantity'=>$size->quantity,
                                    "price" => (int)$size->price,
                                    "img"=>$sizeWarehouseImages,
                                    'discount' => $size->discount?$size->discount : NULL,
                                    'price_discount' => $size->discount ? $size->price - ($size->price / 100 * $size->discount) : NULL,
                                    'description' => $size->description?$size->description:$size->product_description,
                                    'material_composition' => $size->material_composition?$size->material_composition:$size->product_material_composition,
                                    'manufacturer_country' => $size->manufacturer_country?$size->manufacturer_country:$size->product_manufacturer_country
                                ],
                                'description'=>$size->description,
                            ];
                            array_push($aaa_size_list,$aas_size);
                        }

                        $aaa_color = [
                            'id' => $color->color_id,
                            'code' => $color->color_code,
                            'name' => $color->color_name,
                            'sizes' => $aaa_size_list
                        ];
                        $bbb_color = [
                            'id' => $color->color_id,
                            'code' => $color->color_code,
                            'name' => $color->color_name
                        ];

                        array_push($aaa_color_list,$aaa_color);
                        array_push($get_colors,$bbb_color);
                    }

                } else {
                    $aaa_color_list = [];
                    $size_list = [];
                }


                if ($warehouse_product->warehouse_product_id) {
                    if($warehouse_product->color_id) {
                        $warehouse_color = Color::select('id', 'name')->find($warehouse_product->color_id);
                        $color_translate_name=table_translate($warehouse_color,'color', $language);
                    }
                    if($warehouse_product->warehouse_product_id) {
                        $warehouse_translate_name=table_translate($warehouse_product,'warehouse', $language);
                    }
                    if($warehouse_product->material_composition){
                        $material_composition = $warehouse_product->material_composition;
                    }elseif($warehouse_product->product_material_composition){
                        $material_composition = $warehouse_product->product_material_composition;
                    }else{
                        $material_composition = '';
                    }
                    if($warehouse_product->manufacturer_country){
                        $manufacturer_country = $warehouse_product->manufacturer_country;
                    }elseif($warehouse_product->product_manufacturer_country){
                        $manufacturer_country = $warehouse_product->product_manufacturer_country;
                    }else{
                        $manufacturer_country = '';
                    }


                    $list = [
                        "id" => $warehouse_product->warehouse_product_id,
                        "name" => $warehouse_translate_name ?? $warehouse_product->product_name,
                        // "relation_id" => $relation_id,
                        "price" => $warehouse_product->price,
                        'discount' => $warehouse_product->discount ? $warehouse_product->discount : NULL,
                        'price_discount' => $warehouse_product->discount ? $warehouse_product->price - ($warehouse_product->price / 100 * $warehouse_product->discount) : NULL,
                        // "discounts" => $warehouse_product->price,
                        "quantity" => $warehouse_product->quantity??NULL,
                        // "max_quantity" => $warehouse_product->max_quantity,
                        // "material_name"=>$warehouse_product->material_name,
                        "company_name"=>$warehouse_product->company_name,
                        "company_id"=>$warehouse_product->company_id,
                         "manufacturer_country"=>$manufacturer_country,
                         "material_composition"=>$material_composition,
                        "description" => $warehouse_product->description ?? $warehouse_product->product_description,
                        "images" => $warehouseProducts,
                        "color" => [
                            "id" => $warehouse_product->color_id,
                            "code" => $warehouse_product->color_code,
                            "name" => $color_translate_name??'',
                        ],
                        "size" => [
                            "id" => $warehouse_product->size_id,
                            "name" => $warehouse_product->size_name,
                        ],
                        "size_by_color" => $aaa_color_list,
                        "get_colors"=>$get_colors,
                        "color_by_size" => $size_list,
                        "get_sizes"=>$get_sizes,
                        "product_category"=>$product_category,
                        "product_sub_category"=>$product_sub_category
                    ];
                } else {
                    $list = [];
                }

                $message = translate_api('success', $language);
                return $this->success($message, 200, $list);

            }else{
                $message = translate_api("There is no warehouse for this id $warehouse_product_id", $language);
                return $this->error($message, 200);
            }
        }
    }

    public function getWarehouseImages($model){
        $modelWarehouseImages = [];
        if($model->type == Constants::WAREHOUSE_TYPE){
            if (count($this->getImages($model, 'warehouse'))>0) {
                $modelWarehouseImages = $this->getImages($model, 'warehouse');
            } else {
                $modelParentProduct = Products::find($model->product_id);
                if($modelParentProduct){
                    $modelWarehouseImages = $this->getImages($modelParentProduct, 'product');
                }
            }
        }else{
            $modelWarehouseImages = [];
            if (!$model->image_front) {
                $model->image_front = 'no';
            }
            $model_model_image_front = storage_path('app/public/warehouse/'.$model->image_front);
            if (!$model->image_back) {
                $model->image_back = 'no';
            }
            $model_model_image_back = storage_path('app/public/warehouse/'.$model->image_back);
            if(file_exists($model_model_image_front)){
                $modelWarehouseImages[] = asset("/storage/warehouse/$model->image_front");
            }
            if(file_exists($model_model_image_back)){
                $modelWarehouseImages[] = asset("/storage/warehouse/$model->image_back");
            }
        }
        return $modelWarehouseImages;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getProduct(Request $request)
    {
        $language = $request->header('language');
        $product = Products::find($request->id);
        if ($product && $product->warehouse) {
            $colors_array = [];
            $sizes_array = [];
            foreach ($product->warehouse as $warehouse_) {
                $colors_array[] = $warehouse_->color->id;
                $sizes_array[] = $warehouse_->size->id;
                if($colors_array[0] == $warehouse_->color->id){
                    $firstColorProducts[] = [
                        'id'=>$warehouse_->id,
                        'size'=>$warehouse_->size ? $warehouse_->size->name:'',
                        'quantity' => $warehouse_->quantity,
                        'images' => $this->getImages($warehouse_, 'warehouse'),
                    ];
                }
            }
            foreach (array_unique($colors_array) as $color) {
                $productsByColor = [];
                foreach ($product->warehouse as $warehouse){
                    if($color == $warehouse->color->id){
                        $colorModel = $warehouse->color;
                        $productsByColor[] = [
                            'id' => $warehouse->id,
                            'size' => $warehouse->size ? $warehouse->size->name:'',
                            'price' => $warehouse->price,
                            'quantity' => $warehouse->quantity,
                            'images' => $this->getImages($warehouse, 'warehouse'),
                        ];
                    }
                }
                $categorizedByColor[] = [
                    'color'=>$colorModel,
                    'products'=>$productsByColor
                ];
            }
            foreach (array_unique($sizes_array) as $size) {
                $productsBySize = [];
                foreach ($product->warehouse as $warehouse){
                    if($size == $warehouse->size->id){
                        $sizeModel = $warehouse->size;
                        $productsBySize[] = [
                            'id' => $warehouse->id,
                            'color' => $warehouse->color ? $warehouse->color:'',
                            'price' => $warehouse->price,
                            'quantity' => $warehouse->quantity,
                            'images' => $this->getImages($warehouse, 'warehouse'),
                        ];
                    }
                }
                $categorizedBySize[] = [
                    'size'=>$sizeModel,
                    'products'=>$productsBySize
                ];
            }
        }
        $good = [];
        if($product){
            $images_ = json_decode($product->images);
            $images = [];
            foreach ($images_ as $image_){
                $images[] = asset('storage/products/'.$image_);
            }
            $good['id'] = $product->id;
            $good['name'] = $product->name??null;
            if($product->subCategory->name){
                $good['subcategory'] = $product->subCategory->name;
            }else{
                $good['subcategory'] = null;
            }
            $current_category = $this->getProductCategory($product);
            $good['category'] = $current_category->name??null;
            $good['description'] = $product->description ?? null;
            $good['price'] = $product->price??null;
            $good['company'] = $product->company??null;
            $good['characters'] = $categorizedByColor??[];
            $good['characters_by_size'] = $categorizedBySize??[];
            $good['first_color_products'] = $firstColorProducts??[];
            $good['images'] = $images??[];
            $good['basket_button'] = false;
            $good['created_at'] = $product->created_at??null;
            $good['updated_at'] = $product->updated_at??null;
        }
        $message = translate_api('Success', $language);
        return $this->success($message, 200, $good);
    }

    public function getProductCategory($product){
        if($product->subSubCategory){
            $category_product = $product->subSubCategory;
            $is_category = 3;
        }elseif($product->subCategory){
            $category_product = $product->subCategory;
            $is_category = 2;
        }elseif($product->category){
            $category_product = $product->category;
            $is_category = 1;
        }else{
            $category_product = 'no';
            $is_category = 0;
        }
        switch ($is_category){
            case 1:
                $current_category = $category_product;
                break;
            case 2:
                $current_category = $category_product->category?$category_product->category:'no';
                break;
            case 3:
                $current_category = $category_product->sub_category->category?$category_product->sub_category->category:'no';
                break;
            default:
                $current_category = 'no';
        }
        return $current_category;
    }

    /**
     * bu funksiya warehouse va product ga tegishli images ni yig'ib beradi
     */

    public function getImages($model, $text){
        if($model->images){
            $images_ = json_decode($model->images);
            $images = [];
            foreach ($images_ as $image_){
                if($text == 'warehouse'){
                    $images[] = asset('storage/warehouses/'.$image_);
                }elseif($text == 'product'){
                    $images[] = asset('storage/products/'.$image_);
                }
            }
        }else{
            $images = [];
        }
        return $images;
    }

    /**
     * bu yerda adminka uchun api qilindadi
     */

    public function deleteProductImage(Request $request){

        $product = Products::find($request->id);
        if($product->images && !is_array($product->images)){
            $product_images_base = json_decode($product->images);
        }else{
            $product_images_base = [];
        }
        if(is_array($product_images_base)){
            if(isset($request->product_name)){
                $selected_product_key = array_search($request->product_name, $product_images_base);
                $product_main = storage_path('app/public/products/'.$request->product_name);
                if(file_exists($product_main)){
                    unlink($product_main);
                }
                unset($product_images_base[$selected_product_key]);
            }
            $product->images = json_encode(array_values($product_images_base));
            $product->save();
        }
        return response()->json([
            'status'=>true,
            'message'=>'Success'
        ], 200);
    }
}
