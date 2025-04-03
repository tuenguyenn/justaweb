<?php
namespace App\Services;

use App\Services\Interfaces\SlideServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\SlideRepositoryInterface;
use Illuminate\Support\Facades\DB;


/** 
 * Class SlideService
 * @package App\Services
 */
class SlideService extends BaseService implements SlideServiceInterface
{   
    protected $slideRepository;

    public function __construct(
        SlideRepositoryInterface $slideRepository,
        
        )
    {
        $this->slideRepository = $slideRepository;
    }

  
    public function paginate($request)
    {   
        $languageId = $this->getRegion();
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
       
        $perpage = $request->integer('perpage');
        $slide= $this->slideRepository->pagination(
            $this->panigateSelect(),
            $condition,
            [ ],
            ['path' => 'slide.index' ,'groupBy'=>$this->panigateSelect()],
            $perpage,
            [],
            ['slides.id', 'desc'],
            
        );
        return $slide;
    }
    
    public function create($request,$languageId)
    {
        return $this->handleTransaction(function () use ($request,$languageId) {
            $payload = $this->preparePayload($request,$languageId);
            $slide= $this->slideRepository->create($payload);
            return true;
        });
    }

    public function update($id, $request, $languageId)
    {
        return $this->handleTransaction(function () use ($id, $request, $languageId) {
            $slide = $this->slideRepository->findById($id);
            $slideItem =$slide->item;
            unset($slideItem[$languageId]);
            $payload = $this->preparePayload($request,$languageId);
            $payload['item'] = $payload['item'] + $slideItem;
            
            $slide= $this->slideRepository->update($id,$payload);

            return true;
        });
    }

    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            

            if ($this->slideRepository->destroy($id)) {
                return redirect()->route('slide.index')->with('success', 'slide deleted successfully.');
            }

            return redirect()->route('slide.index')->with('error', 'slide not found.');
        });
    }

    public function updateStatus($slide)
    {
        return $this->handleTransaction(function () use ($slide) {
            $payload = [$slide['field'] => $slide['value'] == 1 ? 2 : 1];
            $this->slideRepository->update($slide['modelId'], $payload);
            return true;
        });
    }

  
    public function updateAllStatus($slide)
    {
        return $this->handleTransaction(function () use ($slide) {
            $payload = [$slide['field'] => $slide['value']];
            $this->slideRepository->updateByWhereIn('id', $slide['id'], $payload);
            return true;
        });
    }

    /**
     * Các phương thức hỗ trợ chung
     */
    private function preparePayload($request,$languageId)
    {
        $payload = $request->only($this->payload());
        $payload['item'] =( $this->handeSlideItem($request,$languageId));

        return $payload;
    }

   
    private function handeSlideItem($request,$languageId){
        $slide = $request->input('slide');
        $temp =[];
        foreach($slide['image'] as $key => $val){
            $temp[$languageId][] = [
                'image' => $val,
                'name' => $slide['name'][$key],
                'description' => $slide['description'][$key],
                'canonical' => $slide['canonical'][$key],
                'alt' => $slide['alt'][$key],
                'window' => (isset($slide['window'][$key])) ? $slide['window'][$key] : '',
                
            ];

        }
       return $temp;
       
    }
    public function convertSlideArray (array $slide = []){
        $temp =[];
       
        $fields =['image','name','description','canonical','alt','window'];
        foreach($slide as $key => $val){
            foreach ($fields as $field){
                $temp[$field][]= $val[$field];
            }
        }
        return $temp;
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
        return [ 'name', 'keyword', 'setting','short_code'];
    }

    private function panigateSelect()
    {
        return ['id','name', 'keyword','item','description','publish','order'];
    }


    /*FE */
    public function getSlide($array=[],$languageId){
        $slides = $this->slideRepository->findByWhere(
            [
                config('apps.general.defaultPublish'),
            ],
            true,
            [],
            ['id','desc'],
            ['whereIn' => $array ,'whereInField'=> 'keyword']
        );
        $temp =[];
        foreach($slides as $key => $val){
            $val->slideItems = $val->item[$languageId];
            $temp[$val->keyword] = $val;
        }

        return $temp;
    }


}
