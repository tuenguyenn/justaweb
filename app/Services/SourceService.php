<?php
namespace App\Services;

use App\Services\Interfaces\SourceServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use Illuminate\Support\Facades\DB;


/** 
 * Class SourceService
 * @package App\Services
 */
class SourceService extends BaseService implements SourceServiceInterface
{   
    protected $sourceRepository;


    public function __construct(
        SourceRepositoryInterface $sourceRepository,

        )
    {
        $this->sourceRepository = $sourceRepository;
    }

    /**
     * Phân trang người dùng
     */
    public function paginate($request)
    {   
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            
        ];
       
        $perpage = $request->integer('perpage');
        $source= $this->sourceRepository->pagination(
            $this->panigateSelect(),
            $condition,
            [ ],
            ['path' => 'source.index'],
            $perpage,
            [],
            ['sources.id', 'desc'],
            
        );
        return $source;
    }
    
    public function create($request)
    {
        return $this->handleTransaction(function () use ($request) {
            $payload = $request->only($this->payload());
            $source= $this->sourceRepository->create($payload);
            return true;
        });
    }

    public function update($id, $request)
    {
        return $this->handleTransaction(function () use ($id, $request) {

            $source = $this->sourceRepository->findById($id);
            $payload = $request->only($this->payload());
            $source= $this->sourceRepository->update($id,$payload);

            return true;
        });
    }

    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            if ($this->sourceRepository->destroy($id)) {
                return redirect()->route('source.index')->with('success', 'source deleted successfully.');
            }
            return redirect()->route('source.index')->with('error', 'source not found.');
        });
    }

    public function updateStatus($source)
    {
        return $this->handleTransaction(function () use ($source) {
            $payload = [$source['field'] => $source['value'] == 1 ? 2 : 1];
            $this->sourceRepository->update($source['modelId'], $payload);
            return true;
        });
    }

  
    public function updateAllStatus($source)
    {
        return $this->handleTransaction(function () use ($source) {
            $payload = [$source['field'] => $source['value']];
            $this->sourceRepository->updateByWhereIn('id', $source['id'], $payload);
            return true;
        });
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
            \Log::error('Transaction Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            die("Error: " . $e->getMessage() . "<br>File: " . $e->getFile() . "<br>Line: " . $e->getLine());
        }
    }
    
    private function payload()
    {
        return [ 'name', 'keyword','description'];
    }

   
    private function panigateSelect()
    {
        return ['id','name','description','keyword','publish'];
    }


}
