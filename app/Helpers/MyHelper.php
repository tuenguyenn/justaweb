<?php
use Illuminate\Support\Str;

use Carbon\Carbon;
if (!function_exists('write_url')) {
    function write_url( $canonical = null, bool $fullDomain = true ,$suffix = false )
    {   
        $canonical = $canonical ?? '';
        $canonical= str::slug($canonical);
        if(strpos($canonical ,'http')!== false){
            return $canonical;
        }
        $fullUrl = (($fullDomain=== true) ? config('app.url'): '').$canonical.(($suffix === true) ? config('app.general.suffix'): '');
        return $fullUrl;
    }
}
if (!function_exists('image')) {
    function image( string $image ='' )
    {   
       
        return $image;
    }
}
if (!function_exists('convert_price')) {
    function convert_price( string $image ='' )
    {   
       
        return $image;
    }
}
if (!function_exists('getReview')) {
    function getReview( string $product ='' )
    {   
        return [
            'stars'=> rand(1,5),
            'reviews' => rand(0,100),
            
        ];
    }
}
if (!function_exists('sortString')) {
    function sortString( string $str ='' )
    {   
        $extract = explode(',',$str);
        sort($extract,SORT_NUMERIC);
        $newArray = implode(',',$extract);
        return $newArray;
    }
}

if (!function_exists('sortArray')) {
    function sortArray( array $arr =[] )
    {   
        sort($arr, SORT_NUMERIC);
        $attrId = implode(',', $arr);
        return $attrId;
    }
}


if (!function_exists('recursive')) {
    function recursive($data, $parentId = 0)
    {
        $temp = [];
        if (!is_null($data) && count($data)) {
            foreach ($data as $key => $value) {
                if ($value->parent_id === $parentId) {
                    $temp[] = [
                        'item' => $value,
                        'children' => recursive($data, $value->id)
                    ];
                }
            }
        }
        return $temp;
    }
}
if (!function_exists('fe_recursive_menu')) {
    function fe_recursive_menu(array $data =[], $parentId = 0, $count = 1, $type = 'html')
    {    
        $html ='';
        if (count($data)) {
            if($type == 'html')
            {
                foreach ($data as $key => $val) {
                    $name = $val['item']->languages->first()->pivot->name;
                    $canonical = write_url($val['item']->languages->first()->pivot->canonical,true,true);
                    $ulClass = ($count > 1) ? 'menu-level__'.($count+1): '';
                    $html .= '<li class="'.(($count == 1) ? 'children' : '').'">';
                    $html .= '<a href="'.$canonical.'" title ="'.$name.'">'.$name.'</a>';
                        if(count($val['children'])){
                            $html = $html.'<div class="dropdown-menu">';
                                $html = $html.'<ul class="uk-list uk-clearfix menu-style "'.$ulClass.'">';
                                $html.= fe_recursive_menu($val['children'],$val['item']->parent_id,$count +1,$type) ;               
                                $html .= '</ul>';
                            $html .= '</div>';
                        }
                    $html .= '</li>';
                }
                return $html;
            }
         
       
        return $data;
    }
}
}
if (!function_exists('recursiveMenu')) {
    function recursiveMenu($data)
    {   
       
        $html = '';
        if (count($data)) {
            foreach ($data as $key => $val) {
                $itemId = $val['item']->id ?? null;
                $itemName = $val['item']->languages->first()->pivot->name ?? 'Unnamed';
                $itemUrl = route('menu.children', ['id' => $itemId]);

                $html .= "<li class=\"dd-item\" data-id=\"$itemId\">";
                $html .= " <div class=\"dd-handle\">
                                <span class=\"label label-info\"><i class=\"fa fa-arrows\"></i></span> $itemName
                            </div>";
                $html .= " <a class=\"create-children-menu\" href=\"$itemUrl\">Quản lý menu con</a>";
                if (count($val['children'])) {
                    $html .= "<ol class=\"dd-list\">";
                    $html .= recursiveMenu($val['children']);
                    $html .= "</ol>";
                }
                $html .= "</li>";

               
            }
        }
        return $html;
    }
}

if (!function_exists('convertToArrayByKey')) {
    function convertArrayByKey($object = null, $fields = [])
    {
        $temp = [];

        foreach ($object as $key => $val) {
            foreach ($fields as $field) {
                
                if (is_array($object)) {
                    
                    $temp[$field][] = $val[$field];
                    
                } else {
                    $extract = explode('.',$field);
                    if(count($extract) ==2){
                        $temp[$extract[0]][] = $val->{$extract[1]}->first()->pivot->{$extract[0]};
                    }else{
                        $temp[$field][] = $val->$field;
                    }
                    
                }
            }
        }

        return $temp;
    }
}


if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'd/m/Y') {
        if($date ==null){
            return '';
        }
        return \Carbon\Carbon::parse($date)->format($format);
    }
}
if (!function_exists('loadClass')) {
    function loadClass($model) {
        $repositoryInterfaceNamespace = '\App\Repositories\\' . $model . 'Repository';

        if (class_exists($repositoryInterfaceNamespace)) {
            $loadClass = app($repositoryInterfaceNamespace);
        } 
        return $loadClass;
    }
}
if (!function_exists('renderQuickBuy')) {
    function renderQuickBuy($product = null, string $canonical = '', string $name = '') {
        $class = 'btn-addCart';
        $openModal = '';

        if (isset($product->product_variants) && count($product->product_variants)) {
            $class = '';
            $canonical = '#popup';
            $openModal = 'data-uk-modal'; // Sửa lỗi chính tả 'data-uk-model' -> 'data-uk-modal'
        }

        // Sử dụng dấu nháy kép đúng cách
        $html = '<a href="' . $canonical . '" ' . $openModal . ' title="' . $name . '" class="' . $class . '">
        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g>
        <path d="M24.4941 3.36652H4.73614L4.69414 3.01552C4.60819 2.28593 4.25753 1.61325 3.70863 1.12499C3.15974 0.636739 2.45077 0.366858 1.71614 0.366516L0.494141 0.366516V2.36652H1.71614C1.96107 2.36655 2.19748 2.45647 2.38051 2.61923C2.56355 2.78199 2.68048 3.00626 2.70914 3.24952L4.29414 16.7175C4.38009 17.4471 4.73076 18.1198 5.27965 18.608C5.82855 19.0963 6.53751 19.3662 7.27214 19.3665H20.4941V17.3665H7.27214C7.02705 17.3665 6.79052 17.2764 6.60747 17.1134C6.42441 16.9505 6.30757 16.7259 6.27914 16.4825L6.14814 15.3665H22.3301L24.4941 3.36652ZM20.6581 13.3665H5.91314L4.97214 5.36652H22.1011L20.6581 13.3665Z" fill="#253D4E"></path>
        <path d="M7.49414 24.3665C8.59871 24.3665 9.49414 23.4711 9.49414 22.3665C9.49414 21.2619 8.59871 20.3665 7.49414 20.3665C6.38957 20.3665 5.49414 21.2619 5.49414 22.3665C5.49414 23.4711 6.38957 24.3665 7.49414 24.3665Z" fill="#253D4E"></path>
        <path d="M17.4941 24.3665C18.5987 24.3665 19.4941 23.4711 19.4941 22.3665C19.4941 21.2619 18.5987 20.3665 17.4941 20.3665C16.3896 20.3665 15.4941 21.2619 15.4941 22.3665C15.4941 23.4711 16.3896 24.3665 17.4941 24.3665Z" fill="#253D4E"></path>
        </g>
        <defs>
        <clipPath>
        <rect width="24" height="24" fill="white" transform="translate(0.494141 0.366516)"></rect>
        </clipPath>
        </defs>
        </svg></a>';
      
        return $html;
    }
}

if (!function_exists('cutnchar')) {
    function cutnchar($string = null, $n=320) {
        if(strlen($string) < $n) return $string;
        $html = substr($string, 0, $n);
        $html = substr($string, 0, strpos($html,' '));

    
        return $html.'...';
    }
}


if (!function_exists('cut_string_decode')) {
    function cut_string_decode($string = null, $n=200) {
        $string = html_entity_decode($string );
        
        $string = strip_tags($string);
        
       $string = cutnchar($string,$n);
        
        return $string;
    }
}
if (!function_exists('seo')) {
    function seo($model = null, $n=200) {
       return [
        'meta_title' => (!empty($model->meta_title) ? $model->meta_title  : $model->name),

        'meta_keywords' => (!empty($model->meta_keyword) ? $model->meta_keyword  : ''),
        'meta_description' => (!empty($model->meta_description) ? $model->meta_description  : cut_string_decode($model->description,168)),
        'meta_image' => $model->image ?? '',
        'canonical' => write_url($model->canonical,true,true),
    ];
        
        
    }
}

if (!function_exists('VnpayConfig')) {
    function VnpayConfig()
    {   
       
        return [
            'vnp_Url'=> 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
            'vnp_Returnurl' => write_url('return/vnpay'),
            'vnp_TmnCode' =>'JBLZPDOV',
            'vnp_HashSecret' =>'FEG9OGJZQXRJ3ZAJPA5GWX6L54TM6N7O',
            'vnp_apiUrl'=>'http://sandbox.vnpayment.vn/merchant_webapi/merchant.html',
            'apiUrl'=>'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'

        ];
    }
}
if (!function_exists('MomoConfig')) {
    function MomoConfig()
    {   
       
        return [
            'partnerCode'=> 'MOMOBKUN20180529',
            'accessKey' => 'klm05TvNBzhg7h7j',
            'secretKey' =>'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa',
          

        ];
    }
}


if(!function_exists('execPostRequest')){
    function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    return $result;
}
}
function getConfirmClass($status) {
    return match ($status) {
        'pending' => 'uk-label-warning',    
        'cancel'  => 'uk-label-danger',     
        'confirm' => 'uk-label-success',    
        default   => 'uk-label-default',    
    };
}

function getDeliveryClass($status) {
    return match ($status) {
        'pending'    => 'uk-label-warning',    
        'processing' => 'uk-label-primary',    
        'success'    => 'uk-label-success',    
        'fail'       => 'uk-label-danger',     
        default      => 'uk-label-default',    
    };
}

function caculateGrowth($current ,$previous){
    $growth = 0;

    if ($previous > 0) {
        $growth = (($current - $previous) / $previous) * 100;
    } elseif ($previous == 0 && $current > 0) {
        $growth = 100; // hoặc bạn có thể cho là 999% nếu muốn thể hiện "tăng mạnh"
    } else {
        $growth = 0; // cả hai đều bằng 0 => không thay đổi
    }
    
    return round($growth, 2); // làm tròn đến 2 chữ số thập phân
    
}