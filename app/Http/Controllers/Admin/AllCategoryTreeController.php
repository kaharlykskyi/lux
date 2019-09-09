<?php

namespace App\Http\Controllers\Admin;

use App\AllCategoryTree;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AllCategoryTreeController extends Controller
{
    protected $tecdoc;

    public function __construct()
    {
        parent::__construct();
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    public function index(Request $request){
        $parent = null;
        if (isset($request->parent_category)){
            $parent = AllCategoryTree::with('subCategory')->findOrFail((int)$request->parent_category);
            $categories = $this->tecdoc->getAllCategoryTree($parent->tecdoc_name, $request->level);
        } else{
            $categories = $this->tecdoc->getAllCategoryTree();
        }

        $categories = $this->arrayPaginator($categories,$request,100);
        return view('admin.all_category_tree.index',compact('categories','parent'));
    }

    public function edit(Request $request){

        if ($request->isMethod('post')){
            $data = $request->except('_token');

            if (isset($data['change_root'])){
                $count = DB::table('all_category_trees')
                    ->where('id',(int)$data['id'])
                    ->update(['parent_category' => (int)$data['parent_id']]);
                if ($count){
                    Cache::forget('tecdoc_category_info_'.$data['id']);
                    return response()->json(true);
                }else{
                    return response()->json(false);
                }
            }

            $data['level'] = isset($data['level'])?(int)$data['level']:0;
            $data['show'] = isset($data['show'])?1:0;
            $data['hurl'] = isset($data['hurl'])?$data['hurl']:str_replace([' ',',','/','-'],'_',$this->transliterateRU($data['name']));

            $filter = [['level','=',isset($data['level'])?(int)$data['level']:0]];
            if (isset($data['tecdoc_id'])) $filter[] = ['tecdoc_id','=',(int)$data['tecdoc_id']];
            else $filter[] = ['tecdoc_name','=',$data['tecdoc_name']];
            $category_save = AllCategoryTree::where($filter)->first();

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
                    'parent_category' => isset($data['parent'])?(int)$data['parent']:null,
                    'hurl' => $data['hurl'],
                    'name' => $data['name'],
                    'image' => isset($file_name)?$file_name:null,
                    'show' => $data['show'],
                    'tecdoc_name' => $data['tecdoc_name'],
                    'level' => $data['level'],
                    'tecdoc_id' => $data['tecdoc_id']
                ]);
                $save_category->save();
            } else{
                AllCategoryTree::where('id',(int)$data['id'])
                    ->update([
                        'name' => $data['name'],
                        'image' => isset($file_name)?$file_name:$category_save->image,
                        'show' => $data['show'],
                    ]);
                Cache::forget('tecdoc_category_info_'.$data['id']);
            }
            return redirect()->back()->with('status','Данные не сохранены');
        }

        $save_category = AllCategoryTree::where('id','=',(int)$request->id)->first();

        return view('admin.all_category_tree.edit',compact('save_category'));
    }
}
