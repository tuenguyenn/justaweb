<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\LanguageServiceInterface as LanguageService;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;

use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Requests\StoreTranslateRequest;

class LanguageController extends Controller
{
    protected $languageService;
    protected $languageRepository;

    public function __construct(
        LanguageService $languageService,
        LanguageRepository $languageRepository
    ) {
        $this->languageService = $languageService;
        $this->languageRepository = $languageRepository;
    }

    public function index(Request $request)
    {   
        $this->authorize('module','languages.index');

        $languages = $this->languageService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        $config['seo'] = (__('messages.language'));
        $template = 'backend.language.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'languages'
        ));
    }

    public function create()
    {
        $this->authorize('module','languages.create');

        $config = [
            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ],
        ];
        $template = 'backend.language.form';
        $config['seo'] = (__('messages.language'));
        $config['method'] = 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'config'
        ));
    }

    public function store(StoreLanguageRequest $request)
    {
        // Create a new catalogue entry using the service
        if ($this->languageService->create($request)) {
            return redirect()->route('language.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('language.index')->with('error', 'Thêm mới không thành công');
    }

    public function edit($id)
    {   
        $this->authorize('module','languages.update');

        $config = [
            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ],
        ];
        $language = $this->languageRepository->findById($id);
        $template = 'backend.language.form';
        $config['seo'] = (__('messages.language'));
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'language'
        ));
    }

    public function update($id, UpdateLanguageRequest $request)
    {
        if ($this->languageService->update($id, $request)) {
            return redirect()->route('language.index')->with('success', 'Cập nhật thành công');
        }

        return redirect()->route('language.index')->with('error', 'Cập nhật không thành công');
    }

    public function destroy($id)
    {   
        $this->authorize('module','languages.destroy');

        if ($this->languageService->destroy($id)) {
            return redirect()->route('language.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('language.index')->with('error', 'Xoá không thành công');
    }
    public function switchBackendLanguage($id){
        $language = $this->languageRepository->findById($id);
        if( $this->languageService->switch($id)){
            session(['app_locale'=>$language->canonical]);
            \App::setLocale($language->canonical);
        }
        return back();
    }
    public function translate($model ='',$id, $languageId= 0 ){
      
        $repositoryInstance = $this->repositoryInstance($model);
        $languageInstance = $this->repositoryInstance('Language');
        $currentLanguage =$languageInstance->findByCondition([
            ['canonical','=',session('app_locale')]]);
        $method = 'get'.$model.'ById';
        $object = $repositoryInstance->{$method}($id,$currentLanguage->id);
        $objectTranslate= $repositoryInstance->{$method}($id,$languageId);
        $option =[
            'id'=> $id,
            'languageId'=> $languageId,
            'model'=> $model,
        ];
        $this->authorize('module','languages.translate');

        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',

                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js'
            ],
        ];
        $template = 'backend.language.translate';
        $config['seo'] = (__('messages.language'));

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'object',
            'objectTranslate',
            'option'
        ));
    }
    private function repositoryInstance($model){
        $repositoryNamespace ='\App\Repositories\\'.ucfirst($model).'Repository';
        if(class_exists($repositoryNamespace)){
            $repositoryInstance= app($repositoryNamespace);
        }
        return $repositoryInstance;
    }

    public function storeTranslate(StoreTranslateRequest $request){
        $option = $request->input('option');

        if ($this->languageService->saveTranslate($option,$request)) {
            return redirect()->back()->with('success', 'Thêm mới bản dịch thành công');
        }
        return redirect()->back()->with('error', 'Thêm mới bản dịch không thành công');
    }
}
