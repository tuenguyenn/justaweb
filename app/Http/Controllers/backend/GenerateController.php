<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\GenerateServiceInterface as GenerateService;
use App\Repositories\Interfaces\GenerateRepositoryInterface as GenerateRepository;

use App\Http\Requests\StoreGenerateRequest;
use App\Http\Requests\UpdateGenerateRequest;
use App\Http\Requests\StoreTranslateRequest;

class GenerateController extends Controller
{
    protected $generateService;
    protected $generateRepository;

    public function __construct(
        GenerateService $generateService,
        GenerateRepository $generateRepository
    ) {
        $this->generateService = $generateService;
        $this->generateRepository = $generateRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('module','generate.index');

        $generates = $this->generateService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        $config['seo'] = __('messages.generate');
        $template = 'backend.generate.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'generates'
        ));
    }

    public function create()
    {
        $this->authorize('module','generate.create');

        $config = [
            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ],
        ];
        $template = 'backend.generate.form';
        $config['seo'] = __('messages.generate');
        $config['method'] = 'create';

        return view('backend.dashboard.layout', compact(
            'template',
            'config'
        ));
    }

    public function store(StoreGenerateRequest $request)
    {
        if ($this->generateService->create($request)) {
            return redirect()->route('generate.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('generate.index')->with('error', 'Thêm mới không thành công');
    }

    public function edit($id)
    {
        $this->authorize('module','generate.update');

        $config = [
            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ],
        ];
        $generate = $this->generateRepository->findById($id);
        $template = 'backend.generate.form';
        $config['seo'] = __('messages.generate');
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'generate'
        ));
    }

    public function update($id, UpdateGenerateRequest $request)
    {
        if ($this->generateService->update($id, $request)) {
            return redirect()->route('generate.index')->with('success', 'Cập nhật thành công');
        }

        return redirect()->route('generate.index')->with('error', 'Cập nhật không thành công');
    }

    public function destroy($id)
    {
        $this->authorize('module','generate.destroy');

        if ($this->generateService->destroy($id)) {
            return redirect()->route('generate.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('generate.index')->with('error', 'Xoá không thành công');
    }
  
   
  

  
}
