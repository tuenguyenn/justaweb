<?php

namespace App\Http\Controllers\backend\Attribute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\AttributeServiceInterface as AttributeService;
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;
use App\Classes\Nestedsetbie;
use App\Http\Requests\Attribute\StoreAttributeRequest;
use App\Http\Requests\Attribute\UpdateAttributeRequest;
use App\Models\Language;

class AttributeController extends Controller
{
    protected $attributeService;
    protected $attributeRepository;
    protected $nestedset;
    protected $language;

    public function __construct(
        AttributeService $attributeService,
        AttributeRepository $attributeRepository
    ) {
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->attributeService = $attributeService;
        $this->attributeRepository = $attributeRepository;
        $this->initialize();
    }

    private function initialize()
    {
        $this->nestedset = new NestedSetBie([
            'table' => 'attribute_catalogues',
            'foreignkey' => 'attribute_catalogue_id',
            'language_id' => $this->language,
        ]);
    }

    public function index(Request $request)
    {
        // $this->authorize('module', 'attribute.index');
        $attributes = $this->attributeService->paginate($request,$this->language);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        $config['seo'] = __('messages.attribute');
        $template = 'backend.attribute.attribute.index';
        $currentLanguage = $this->language;

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'attributes',
            'currentLanguage'
        ));
    }

    public function create()
    {
        // $this->authorize('module', 'attribute.create');

        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
            ],
        ];
        $template = 'backend.attribute.attribute.form';
        $config['seo'] = __('messages.attribute');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->dropdown();

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }

    public function store(StoreAttributeRequest $request)
    {
        if ($this->attributeService->create($request, $this->language)) {
            return redirect()->route('attribute.index')->with('success', __('messages.create_success'));
        }
        return redirect()->route('attribute.index')->with('error', __('messages.create_error'));
    }

    public function edit($id)
    {
        // $this->authorize('module', 'attribute.update');
        $attribute = $this->attributeRepository->getAttributeById($id, $this->language);

        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
            ],
        ];
        $dropdown = $this->nestedset->dropdown();
        $template = 'backend.attribute.attribute.form';
        $config['seo'] = __('messages.attribute');
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'attribute',
            'dropdown'
        ));
    }

    public function update($id, UpdateAttributeRequest $request)
    {
        if ($this->attributeService->update($id, $request,$this->language)) {
            return redirect()->route('attribute.index')->with('success', __('messages.update_success'));
        }
        return redirect()->route('attribute.index')->with('error', __('messages.update_error'));
    }

    public function destroy($id)
    {
        // $this->authorize('module', 'attribute.destroy');
        if ($this->attributeService->destroy($id)) {
            return redirect()->route('attribute.index')->with('success', __('messages.delete_success'));
        }
        return redirect()->route('attribute.index')->with('error', __('messages.delete_error'));
    }
}
