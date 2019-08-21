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
        parent::__construct();
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    public function index(Request $request){
        $parent = null;
        if (isset($request->parent_category)){
            $parent = AllCategoryTree::findOrFail((int)$request->parent_category);
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
            }

            return redirect()->back()->with('status','Данные не сохранены');
        }

        $search_category = trim($request->category);

        if (isset($request->id)) $filter[] = ['id','=',(int)$request->id];
        else $filter[] = ['assemblygroupdescription','=',$search_category];
        $category = DB::connection('mysql_tecdoc')->table('prd')
            ->where('id','=',(int)$request->id)->first();

        $filter = [['level','=',isset($request->level)?(int)$request->level:0]];
        if (isset($request->id)) $filter[] = ['tecdoc_id','=',(int)$request->id];
        else $filter[] = ['tecdoc_name','=',$search_category];
        $save_category = AllCategoryTree::where($filter)->first();

        if ($request->has('id')){
            $search_category = "{$category->normalizeddescription} - $category->usagedescription";
        }

        return view('admin.all_category_tree.edit',compact('category','search_category','save_category'));
    }
}
