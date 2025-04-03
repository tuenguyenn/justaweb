<?php
namespace App\Services;

use App\Services\Interfaces\PostServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\RouterRepositoryInterface;


/** 
 * Class PostService
 * @package App\Services
 */
class PostService extends BaseService implements PostServiceInterface
{   
    protected $postRepository;
    protected $controllerName ='PostController';
    protected $routerRepository;


    public function __construct(
        PostRepositoryInterface $postRepository,
        RouterRepositoryInterface $routerRepository

        )
    {
        $this->postRepository = $postRepository;
        $this->routerRepository = $routerRepository;
    }

    /**
     * Phân trang người dùng
     */
    public function paginate($request)
    {   
        $languageId = $this->getRegion();
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
        $condition['post_catalogue_id']=$request->input('post_catalogue_id');
        $condition['where'] = [
            ['tb2.language_id', '=', $languageId],
        ];
        $perpage = $request->integer('perpage');
        $post= $this->postRepository->pagination(
            $this->panigateSelect(),
            $condition,
            [
                ['posts_language as tb2', 'tb2.post_id', '=', 'posts.id'],
                ['post_catalogue_post as tb3','posts.id','=','tb3.post_id']
            ],
            ['path' => 'post.index' ,'groupBy'=>$this->panigateSelect()],
            $perpage,
            ['post_catalogues'],
            ['posts.id', 'desc'],
            $this->whereRaw($request),

        );
        return $post;
    }
    private function whereRaw($request)
    {
        $rawCondition = [];
        if ($request->integer('post_catalogue_id') > 0) {
            $rawCondition['whereRaw'] = [
                [
                    'tb3.post_catalogue_id IN (
                        SELECT id
                        FROM post_catalogues
                        WHERE lft >= (SELECT lft FROM post_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM post_catalogues as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('post_catalogue_id'), $request->integer('post_catalogue_id')]
                ]
            ];
        }
    return $rawCondition;
    }

public function create($request,$languageId)
    {
        return $this->handleTransaction(function () use ($request, $languageId) {
            $payload = $this->preparePayload($request);
            $post = $this->postRepository->create($payload);

            if ($post->id) {
                $this->createLanguageAndSyncCatalogue($post, $request,$languageId);
                 $router = $this->formatRouterPayload($post, $request,$this->controllerName,$languageId);
                $this->routerRepository->create($router);
            }
            return true;
        });
    }

    public function update($id, $request, $languageId)
    {
        return $this->handleTransaction(function () use ($id, $request, $languageId) {
            $payload = $this->preparePayload($request);
            $post = $this->postRepository->findById($id);

            if ($this->postRepository->update($id, $payload)) {
                $post->languages()->detach();
                $this->createLanguageAndSyncCatalogue($post, $request, $languageId);
                $condition = [
                    ['module_id', '=', $post->id],
                    ['controllers', '=', 'App\Http\Controllers\Frontend\postController'],
                    ['language_id', '=', $languageId],
                ];
                $router = $this->routerRepository->findByCondition($condition);
                $payloadRouter =$this->formatRouterPayload($post, $request,$this->controllerName,$languageId);
                $this->routerRepository->update($router->id, $payloadRouter);
            }

            return true;
        });
    }

    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            $post = $this->postRepository->findById($id);

            if ($this->deleteLanguageAndRouter($post,$id)) {
                return redirect()->route('post.index')->with('success', 'post deleted successfully.');
            }

            return redirect()->route('post.index')->with('error', 'post not found.');
        });
    }

    private function deleteLanguageAndRouter( $post,$id)
    {   
        $payloadLanguage['language_id'] = $this->getRegion();
        $post->languages()->detach([$payloadLanguage['language_id'], $post->id]);
        
        $postRemain = $post->languages()->where('post_id', $post->id)->exists();

        if (!$postRemain) {
            $this->postRepository->destroy($id);
        }
        
        $condition = [
            ['module_id', '=', $post->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\postController'],
            ['language_id','=', $this->getRegion()],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $this->routerRepository->forceDelete($router->id);
    }
    public function updateStatus($post)
    {
        return $this->handleTransaction(function () use ($post) {
            $payload = [$post['field'] => $post['value'] == 1 ? 2 : 1];
            $this->postRepository->update($post['modelId'], $payload);
            return true;
        });
    }

  
    public function updateAllStatus($post)
    {
        return $this->handleTransaction(function () use ($post) {
            $payload = [$post['field'] => $post['value']];
            $this->postRepository->updateByWhereIn('id', $post['id'], $payload);
            return true;
        });
    }

    /**
     * Các phương thức hỗ trợ chung
     */
    private function preparePayload($request)
    {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();

        return $payload;
    }

    private function createLanguageAndSyncCatalogue($post, $request,$languageId)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $languageId;
        $payloadLanguage['post_id'] = $post->id;

        $this->postRepository->createPivot($post, $payloadLanguage, 'languages');
        $catalogue = $request->has('catalogue') && is_array($request->input('catalogue'))
                    ? array_unique(array_merge($request->input('catalogue'), [$request->post_catalogue_id]))
                    : [$request->post_catalogue_id];
    
        $post->post_catalogues()->sync($catalogue);
    
       
    }

    private function handleTransaction($callback)
    {
        DB::beginTransaction();
        try {
            $result = $callback();
            DB::commit();
            return $result;
        }catch (\Exception $e) {
            DB::rollBack();
    
            // Ghi lỗi vào log để theo dõi
            \Log::error('Transaction Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
    
            // Hiển thị lỗi chi tiết ra màn hình
            die("Error: " . $e->getMessage() . "<br>File: " . $e->getFile() . "<br>Line: " . $e->getLine());
        }
    }
    
    private function payload()
    {
        return ['follow', 'publish', 'image', 'post_catalogue_id'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
    private function panigateSelect()
    {
        return ['posts.id','posts.publish', 'posts.image','posts.order','tb2.name','tb2.canonical','tb2.meta_title'];
    }
}