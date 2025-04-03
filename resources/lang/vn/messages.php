<?php
    return [
    'post'=>[
       'index' =>[
        'title'=> 'Chủ đề ',
        'table' =>'Danh sách Chủ đề ',
        ],
        'create'=>[
            'title'=> 'Thêm mới bài viết',
        ],
        'update'=>[
            'title'=> 'Chỉnh sửa bài viết',
        ],
        
           
    ],

     'postCatalogue'=>[
        'index' =>[
            'title'=> 'Danh mục ',
            'table' =>'Danh sách danh mục ',
        ],
        'create'=>[
            'title'=> 'Thêm mới danh mục',
            'table'=> 'Thêm mới danh mục ',
        ],
        'update'=>[
            'title'=> 'Cập nhật',
            'table'=> 'Cập nhật danh mục ',
        ],
        'select'=>'Chọn danh mục'
           
    ],
    'productCatalogue'=>[
        'index' =>[
            'title'=> 'Danh mục sản phẩm',
            'table' =>'Danh sách danh mục sản phẩm',
        ],
        'create'=>[
            'title'=> 'Thêm mới danh mục sản phẩm',
            'table'=> 'Thêm mới danh mục sản phẩm',
        ],
        'update'=>[
            'title'=> 'Cập nhật danh mục sản phẩm',
        ],
        'list'=>'Danh sách Menu'
        
           
    ],

    'language'=>[
        'index' =>[
            'title'=> 'Ngôn ngữ',
            'table' =>'Danh sách ngôn ngữ',
        ],
        'create'=>[
            'title'=> 'Thêm mới ngôn ngữ',
        ],
        'update'=>[
            'title'=> 'Cập nhật ngôn ngữ',
        ]
           
    ],
    'menu'=>[
        'index' =>[
            'title'=> 'Danh mục menu',
            'table' =>'Danh sách danh mục menu',
        ],
        'create'=>[
            'title'=> 'Thêm mới danh mục menu',
            'children'=> 'Cập nhật menu con cho ',
        ],
        'update'=>[
            'title'=> 'Danh sách Menu ',
            'table'=> 'Cập nhật menu con cho',
        ],
        'translate'=>[
            'title'=>'Tạo bản dịch {language} cho ',
        ],
        'select'=>'Chọn danh mục'
           
    ],
    'product'=>[
        'index' =>[
            'title'=> 'Sản phẩm',
            'table' =>'Danh sách  sản phẩm',
        ],
        'create'=>[
            'title'=> 'Thêm mới  sản phẩm',
            'table'=> 'Thêm mới  sản phẩm',
        ],
        'update'=>[
            'title'=> 'Cập nhật ',
            'table'=> 'Cập nhật  sản phẩm',
        ],
        'select'=>'Chọn '
           
    ],
    'order'=>[
        'index' =>[
            'title'=> 'Đơn hàng',
            'table' =>'Danh sách  sản phẩm',
        ],
        'detail' =>[
            'title'=> 'Thông tin chi tiết đơn hàng',
           
        ],
     
           
    ],
    'attributeCatalogue'=>[
        'index' =>[
            'title'=> 'danh mụcThuộc tính',
            'table' =>'Danh sách  danh mụcThuộc tính',
        ],
        'create'=>[
            'title'=> 'Thêm mới danh mục Thuộc tính',
            'table'=> 'Thêm mới danh mục Thuộc tính',
        ],
        'update'=>[
            'title'=> 'Cập nhật ',
            'table'=> 'Cập nhật danh mục Thuộc tính',
        ],
        'select'=>'Chọn '
           
    ],
    'attribute'=>[
        'index' =>[
            'title'=> 'Thuộc tính',
            'table' =>'Danh sách  Thuộc tính',
        ],
        'create'=>[
            'title'=> 'Thêm mới  Thuộc tính',
            'table'=> 'Thêm mới  Thuộc tính',
        ],
        'update'=>[
            'title'=> 'Cập nhật ',
            'table'=> 'Cập nhật  Thuộc tính',
        ],
      
           
    ],
    'user'=>[
        'index' =>[
            'title'=> 'Quản lý thành viên',
            'table' =>'Danh sách thành viên',
        ],
        'create'=>[
            'title'=> 'Thêm mới ',
            'table'=> 'Thêm mới thành viên',
        ],
        'update'=>[
            'title'=> 'Chỉnh sửa thông tin thành viên ',
            
        ],
       
         
    ],
    'customer'=>[
        'index' =>[
            'title'=> 'Quản lý Khách hàng',
            'table' =>'Danh sách Khách hàng',
        ],
        'create'=>[
            'title'=> 'Thêm mới Khách hàng',
        ],
        'update'=>[
            'title'=> 'Chỉnh sửa thông tin Khách hàng ',
            
        ],
       
         
    ],
    
    

    'permission'=>[
        'index' =>[
            'title'=> 'Quyền',
            'table' =>'Danh sách quyền',
        ],
        'create'=>[
            'title'=> 'Thêm mới quyền',
            'table'=> 'Them moi quyền',
        ],
     
        'update'=>[
            'title'=>'Cập nhật thông tin'
        ],
        'name'=> 'Tên quyền',
        'canonical'=> 'Canonical',
         
    ],
    'userCatalogue'=>[
        'index' =>[
            'title'=> 'Quản lý nhóm thành viên',
            'table' =>'Danh sách nhóm thành viên',
        ],
        'create'=>[
            'title'=> 'Thêm mới nhóm thành viên',
        ],
        'update'=>[
            'title'=> 'Chỉnh sửa nhóm thành viên',
        ],
        'permission'=>[
            'title'=> 'Cấp quyền',
        ],
        'selectRole'=>'Chọn nhóm thành viên:',

        
         
    ],
    'customerCatalogue'=>[
        'index' =>[
            'title'=> 'Quản lý nhóm Khách hàng',
            'table' =>'Danh sách nhóm Khách hàng',
        ],
        'create'=>[
            'title'=> 'Thêm mới nhóm Khách hàng',
        ],
        'update'=>[
            'title'=> 'Chỉnh sửa nhóm Khách hàng',
        ],
        'permission'=>[
            'title'=> 'Cấp quyền',
        ],
        'selectRole'=>'Chọn nhóm Khách hàng:',

        
         
    ],
    'customerCatalogue'=>[
        'index' =>[
            'title'=> 'Quản lý nhóm khách hàng',
            'table' =>'Danh sách nhóm khách hàng',
        ],
        'create'=>[
            'title'=> 'Thêm mới nhóm khách hàng',
        ],
        'update'=>[
            'title'=> 'Chỉnh sửa nhóm khách hàng',
        ],
        'permission'=>[
            'title'=> 'Cấp quyền',
        ],
        'selectRole'=>'Chọn nhóm khách hàng:',

        
         
    ],
    'slide'=>[
        'index' =>[
            'title'=> 'Quản lý Banner/Slide',
            'table' =>'Quản lý Banner/Slide',
        ],
        'create'=>[
            'title'=> 'Thêm mới Slide',
        ],
        'update'=>[
            'title'=> 'Chỉnh sửa Slide',
        ],
       


        
         
    ],
    'widget'=>[
        'index' =>[
            'title'=> 'Quản lý Widget',
            'table' =>'Quản lý Widget',
        ],
        'create'=>[
            'title'=> 'Thêm mới Widget',
            'translate'=> 'Tạo bản dịch {language} cho '
        ],
        'update'=>[
            'title'=> 'Chỉnh sửa Widget',
        ],
   
    ],
    'source'=>[
        'index' =>[
            'title'=> 'Quản lý Nguồn khách',
            'table' =>'Quản lý Nguồn khách',
        ],
        'create'=>[
            'title'=> 'Thêm mới Nguồn khách',
        ],
        'update'=>[
            'title'=> 'Chỉnh sửa Nguồn khách',
        ],
   
    ],
    'promotion'=>[
        'index' =>[
            'title'=> 'Quản lý khuyến mãi',
            'table' =>'Quản lý khuyến mãi',
        ],
        'create'=>[
            'title'=> 'Thêm mới khuyến mãi',
            'translate'=> 'Tạo bản dịch {language} cho '
        ],
        'update'=>[
            'title'=> 'Chỉnh sửa khuyến mãi',
        ],
   
    ],
    'generate'=>[
        'index'=>[
            'title'=>'Module',
            'table'=> 'Danh sách Module',
        ],
        'create'=>[
            'title'=> 'Thêm mới Module',
        ],
        'update'=>[
            'title'=> 'Chỉnh sửa Module',
        ],
        'schema'=> 'Schema',
        
    ],
    'update_success'=>'Cập nhật thành công',
    'update_error'=> 'Cập nhật không thành công',
    'delete_success'=> 'Xoá thành công',
    'delete_error'=> 'Xoá không thành công',
    'delete'=> 'Xoá',
    'new'=>'Thêm mới',
    'keyword'=>"Từ khoá:",
    'cata'=>'Danh mục',
    'parent'=>'Chọn danh mục cha',
    'parentNotice'=>'Chọn Root nếu không có danh mục cha',
    'image'=> 'Ảnh',
    'selectStatus'=>'Trạng thái:',
    'selectFollow'=>'Điều hướng:',
    'perpage'=> 'Bản ghi:',
    'search'=> 'Tìm kiếm',
    'searchInput'=> 'Nhập Từ khóa bạn muốn tìm kiếm...',
    'common'=> 'Thông tin chung',
    'title'=> 'Tiêu đề',
    'description'=>'Mô tả ngắn',
    'content'=>'Nội dung bài viết',
    'seo'=> 'Cấu hình SEO',
    'seoTitle'=> 'Nhập tiêu đề SEO',
    'seo_canonical'=> 'https://www.đường-dẫn-của-bạn.com/',
    'seo_description'=> 'Nhập mô tả SEO',
    'seo_meta_title'=> 'Mô tả SEO',
    'seo_meta_keyword'=> 'Từ khoá SEO',
    'seo_meta_desc'=> 'Nội dung ',
    'canonical'=> 'Đường dẫn',
    'tableStatus'=> 'Tình trạng',
    'tableAction'=> 'Thao tác',
        'create_success' => "Thêm mới thành công",
    'deleteAction'=> 'Xác nhận xoá',
    'confirmedDelete'=> 'Bạn có chắc muốn xoá',
    'btnCancel'=> 'Huỷ',
    'btnDelete'=> 'Xoá',
    'btnSave'=>'Lưu',
    'dashboard'=>'Trang chủ',
    'searchGeneral'=> 'Tìm kiếm...',
    'logout'=> 'Đăng xuất',
    'active'=> 'Bật',
    'unactive'=> 'Tắt',
    'publish' =>[
            '0' => 'Chọn tình trạng',
            '1'=>   'Không hoạt động',
            '2'=>  'Hoạt động'
        ],
    'follow'=>[
                '0'=> 'Chọn điều hướng',
                '1'=>   'nofollow',
                 '2'=>  'follow'
        ],
    'roles' =>[
            '0' => 'Chọn nhóm người dùng',
            '1'=>   'Quản trị viên',
            '2'=>  'Người dùng'
        ],


    ];