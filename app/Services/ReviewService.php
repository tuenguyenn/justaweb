<?php
namespace App\Services;
use App\Services\Interfaces\ReviewServiceInterface;
use App\Repositories\Interfaces\ReviewRepositoryInterface ;

use Illuminate\Support\Facades\DB;


use App\Classes\ReviewNested;
/** 
 * Class ReiviewService
 * @package App\Services
 */
class ReviewService extends BaseService implements ReviewServiceInterface 
{   
    protected $reviewRepository;
    protected $reviewNested;

    public function __construct(ReviewRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * Phân trang người dùng
     *
     * @return mixed
     */
    public function paginate($request)
    {   
        $condition['keyword'] = addslashes($request->input('keyword'));
       
        $perpage = $request->integer('perpage');
        $reviews= $this->reviewRepository->pagination(
            $this->panigateSelect(),
            $condition,
            [
                ['customers as tb2', 'tb2.id', '=', 'reviews.customer_id']
            ],
            ['path' => 'review.index' ,'groupBy'=>$this->panigateSelect()],
            $perpage,
            [],
            ['reviews.id', 'desc'],
            
        );
        return $reviews;
    }
    public function create($request,$language_id)
    {
        DB::beginTransaction();
        try {
          
            

            $payload = $request->except('_token','send');  
            
          
            $review =$this->reviewRepository->create($payload);
          
            $this->reviewNested = new ReviewNested([
                'table' => 'reviews',
                'reviewable_type' => $payload['reviewable_type'],
                
            ]);
            $this->reviewNested->Get('level ASC, order ASC');
           
            $this->reviewNested->Recursive(0, $this->reviewNested->Set());
          
            $this->reviewNested->Action();
           
           
            DB::commit();
            return [
                'code'=>10,
                'messages'=> trans('Đánh giá thành công sản phẩm'),
            ];
            
           
          
        } catch (\Exception $e) {
            DB::rollBack(); 
            echo $e->getMessage();
            die();
        }
    }
    private function panigateSelect()
    {
        return ['reviews.id','reviewable_id', 'reviewable_type','customer_id','reviews.description','score','status','reviews.created_at' ,'tb2.name','tb2.image'];
    }


    
   
   
}   