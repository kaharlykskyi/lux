<?php

namespace App\Http\Controllers\Admin;

use App\AllCategoryTree;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AllCategoryTreeController extends Controller
{
    protected $tecdoc;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    public function index(Request $request){
        $parent = isset($request->parent_category)?$request->parent_category:null;
        if (isset($parent)){
            $categories = $this->tecdoc->getAllCategoryTree($parent, $request->level);
        } else{
            $categories = $this->tecdoc->getAllCategoryTree();
        }

        $categories = $this->arrayPaginator($categories,$request,100);
        return view('admin.all_category_tree.index',compact('categories','parent'));
    }

    public function edit(Request $request){

        if ($request->isMethod('post')){
            $data = $request->post();
            $data['level'] = isset($data['level'])?(int)$data['level']:0;
            $data['show'] = isset($data['show'])?1:0;
            $data['hurl'] = isset($data['hurl'])?$data['hurl']:str_replace([' ',','],'_',$this->transliterateRU($data['name'])) .'_'.$data['level'];

            $category = DB::connection('mysql_tecdoc')
                ->table('prd')
                ->where('assemblygroupdescription',$data['parent'])
                ->orWhere('normalizeddescription',$data['parent'])
                ->orWhere('usagedescription',$data['parent'])
                ->first();

            switch ($data['level']){
                case 1:
                    $parent = AllCategoryTree::where('tecdoc_name',$category->assemblygroupdescription)
                        ->where('level',$data['level'] - 1)->first();
                    break;
                case 2:
                    $parent = AllCategoryTree::where('tecdoc_name',$category->normalizeddescription)
                        ->where('level',$data['level'] - 1)->first();
                    break;
                default:
                    $parent = null;
            }

            $category_save = AllCategoryTree::where('tecdoc_name',$data['tecdoc_name'])
                ->where('level',$data['level'])->first();

            if ($request->hasFile('logo')){

                if (isset($category_save->image) && file_exists(public_path() . '/images/catalog/' . $category_save->image)){
                    unlink(public_path() . '/images/catalog/' . $category_save->image);
                }

                $file = $request->file('logo');
                $file_name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path() . '/images/catalog/',$file_name);
            }

            if (!isset($category_save)){
                $save_category = new AllCategoryTree();
                $save_category->fill([
                    'parent_category' => isset($parent)?$parent->id:$parent,
                    'hurl' => $data['hurl'],
                    'name' => $data['name'],
                    'image' => isset($file_name)?$file_name:null,
                    'show' => $data['show'],
                    'tecdoc_name' => $data['tecdoc_name'],
                    'level' => $data['level']
                ]);
                $save_category->save();
            } else{
                AllCategoryTree::where('tecdoc_name',$data['tecdoc_name'])
                    ->where('level',$data['level'])
                    ->update([
                        'name' => $data['name'],
                        'image' => isset($file_name)?$file_name:$category_save->image,
                        'show' => $data['show'],
                    ]);
            }

            return redirect()->back()->with('status','Данные не сохранены');
        }

        $search_category = trim($request->category);
        $category = DB::connection('mysql_tecdoc')
            ->table('prd')
            ->where('assemblygroupdescription',$search_category)
            ->orWhere('normalizeddescription',$search_category)
            ->orWhere('usagedescription',$search_category)
            ->first();

        $save_category = AllCategoryTree::where('tecdoc_name',$search_category)
            ->where('level',isset($request->level)?(int)$request->level:0)->first();

        return view('admin.all_category_tree.edit',compact('category','search_category','save_category'));
    }
}
