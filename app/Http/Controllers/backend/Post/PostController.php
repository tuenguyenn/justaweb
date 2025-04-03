<?php

namespace App\Http\Controllers\backend\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use App\Services\Interfaces\PostServiceInterface as PostService;

use App\Models\Language;

use App\Classes\Nestedsetbie;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends Controller
{
    protected $postService;
    protected $postRepository;
    protected $nestedset;
    protected $languageRepository;
    protected $language;

    public function __construct(
        PostService $postService,
        PostRepository $postRepository,

    ) {
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language =$language->id;
            $this->initialize();
            return $next($request);
        });
        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->initialize();
       

    }
    private function initialize(){
        $this->nestedset = new NestedSetBie([
            'table'=>'post_catalogues',
            'foreignKey'=> 'post_catalogue_id',
            'language_id'=> $this->language,
        ]);
    }

    public function index(Request $request)
    {   
        $this->authorize('module','post.index');

        $posts = $this->postService->paginate($request, $this->language);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        $currentLanguage = $this->language;
        
        $dropdown =$this->nestedset->dropdown();
        $config['seo'] = (__('messages.post'));
        $template = 'backend.post.post.index';
        // $languages= $this->languageRepository->all();
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'posts',
            'dropdown',
            'currentLanguage'
        ));
    }

    public function create()
    {
        $this->authorize('module','post.create');

        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',


                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js'

            ],
        ];
        $template = 'backend.post.post.form';
        $config['seo'] = (__('messages.post'));
        $config['method'] = 'create';
        $dropdown =$this->nestedset->dropdown();
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'  
        ));
    }

    public function store(StorePostRequest $request)
    {

        // Create a new  entry using the service
        if ($this->postService->create($request, $this->language)) {
            return redirect()->route('post.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('post.index')->with('error', 'Thêm mới không thành công');
    }

    public function edit($id)
    {   
        $this->authorize('module','post.update');

        $post = $this->postRepository->getPostById($id, $this->language);
         $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                  'backend/library/seo.js',

                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ],
        ];
        $album =json_decode($post->album);

        $dropdown =$this->nestedset->dropdown();
        $template = 'backend.post.post.form';
        $config['seo'] = (__('messages.post'));
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'post',
            'dropdown',
            'album'

        ));
    }

    public function update($id, UpdatePostRequest $request)
    {   

        if ($this->postService->update($id, $request ,$this->language)) {
            return redirect()->route('post.index')->with('success', 'Cập nhật thành công');
        }

        return redirect()->route('post.index')->with('error', 'Cập nhật không thành công');
    }

    public function destroy($id)
    {
        $this->authorize('module','post.destroy');

        if ($this->postService->destroy($id)) {
            return redirect()->route('post.index')->with('success', 'Xoá thành công');
        }
    
        return redirect()->route('post.index')->with('error', 'Xoá không thành công');
    }
    
    
    
}
