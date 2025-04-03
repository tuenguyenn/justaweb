<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\{ModuleTemplate}ServiceInterface as {ModuleTemplate}Service;
use App\Repositories\Interfaces\{ModuleTemplate}RepositoryInterface as {ModuleTemplate}Repository;
use App\Classes\Nestedsetbie;
use App\Http\Requests\Store{ModuleTemplate}Request;
use App\Http\Requests\Update{ModuleTemplate}Request;
use App\Models\Language;

use App\Models\{ModuleTemplate};
class {ModuleTemplate}Controller extends Controller
{
    protected ${moduleTemplate}Service;
    protected ${moduleTemplate}Repository;
    protected $nestedset;
    protected $language;


    public function __construct(
        {ModuleTemplate}Service ${moduleTemplate}Service,
        {ModuleTemplate}Repository ${moduleTemplate}Repository
    ) {
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language =$language->id;
            $this->initialize();
            return $next($request);
        });
        $this->{moduleTemplate}Service = ${moduleTemplate}Service;
        $this->{moduleTemplate}Repository = ${moduleTemplate}Repository;
        $this->initialize();

       

    }
    private function initialize(){
        $this->nestedset = new NestedSetBie([
            'table'=>'{tableName}',
            'foreignkey'=> '{foreignKey}',
            'language_id'=>  $this->language,
        ]);
    }

    public function index(Request $request)
    {   
        // $this->authorize('module','{moduleView}.index');
        ${moduleTemplate}s = $this->{moduleTemplate}Service->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        $config['seo'] = __('messages.{moduleTemplate}');
        $template = 'backend.{moduleView}.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            '{moduleTemplate}s'
        ));
    }

    public function create()
    {
        // $this->authorize('module','{moduleView}.create');

        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',

                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js'

            ],
        ];
        $template = 'backend.{moduleView}.form';
        $config['seo'] = __('messages.{moduleTemplate}');
        $config['method'] = 'create';
        $dropdown =$this->nestedset->dropdown();
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }

    public function store(Store{ModuleTemplate}Request $request)
    {
        if ($this->{moduleTemplate}Service->create($request)) {
            return redirect()->route('{moduleView}.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('{moduleView}.index')->with('error', 'Thêm mới không thành công');
    }

    public function edit($id)
    {   
        // $this->authorize('module','{moduleView}.update');

        ${moduleTemplate} = $this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id, $this->language);
        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                  'backend/library/seo.js',

                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ],
        ];
        $dropdown =$this->nestedset->dropdown();

        $template = 'backend.{moduleView}.form';
        $config['seo'] = __('messages.{moduleTemplate}');
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            '{moduleTemplate}',
            'dropdown'

        ));
    }

    public function update($id, Update{ModuleTemplate}Request $request)
    {   

        if ($this->{moduleTemplate}Service->update($id, $request)) {
            return redirect()->route('{moduleView}.index')->with('success', 'Cập nhật thành công');
        }

        return redirect()->route('{moduleView}.index')->with('error', 'Cập nhật không thành công');
    }

    public function destroy($id, Request $request)
    {
        // $this->authorize('module','{moduleView}.destroy');

        if (!{ModuleTemplate}::isChildrenNode($id)) {
            return redirect()->route('{moduleView}.index')->with('error', 'Không thể xóa vì danh mục có con.');
        }
    
        if ($this->{moduleTemplate}Service->destroy($id, $request)) {
            return redirect()->route('{moduleView}.index')->with('success', 'Xoá thành công');
        }
    
        return redirect()->route('{moduleView}.index')->with('error', 'Xoá không thành công');
    }
    
    
}
