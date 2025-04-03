(function($) {
    "use strict";
    var HT = {};

    HT.seoPreview = () => {
        $('input[name=meta_title]').on('keyup', function() {
            let text = $(this).val();
            $('.meta-title').html(text); 
        });
       

        $('.seo-canonical').each(function(){
            let _this= $(this)
            _this.css({
                'padding-left': parseInt($('.baseUrl').outerWidth()) + 10 

            })
        })
        $('input[name=canonical]').on('keyup', function() {
            let inputValue = $(this).val();
            let text = inputValue.replace(/\s+/g, '-'); 
        
            text = HT.convertUtf(text);
        
            $('.canonical').html(BASE_URL + text + SUFFIX);
        });
        

        $('textarea[name=meta_description]').on('keyup', function() {
            let text = $(this).val();
            $('.meta-description').html(text); 
        });
    };

    // Hàm chuyển đổi UTF sang ký tự không dấu
    HT.convertUtf = (str) => {
        const charMap = {
            'â': 'a', 'ă': 'a', 'á': 'a', 'à': 'a', 'ả': 'a', 'ã': 'a', 'ạ': 'a',
            'ấ': 'a', 'ầ': 'a', 'ẩ': 'a', 'ẫ': 'a', 'ậ': 'a', 
            'ắ': 'a', 'ằ': 'a', 'ẳ': 'a', 'ẵ': 'a', 'ặ': 'a',
            'ê': 'e', 'é': 'e', 'è': 'e', 'ẻ': 'e', 'ẽ': 'e', 'ẹ': 'e',
            'ế': 'e', 'ề': 'e', 'ể': 'e', 'ễ': 'e', 'ệ': 'e',
            'ô': 'o', 'ơ': 'o', 'ó': 'o', 'ò': 'o', 'ỏ': 'o', 'õ': 'o', 'ọ': 'o',
            'ố': 'o', 'ồ': 'o', 'ổ': 'o', 'ỗ': 'o', 'ộ': 'o',
            'ớ': 'o', 'ờ': 'o', 'ở': 'o', 'ỡ': 'o', 'ợ': 'o',
            'ư': 'u', 'ú': 'u', 'ù': 'u', 'ủ': 'u', 'ũ': 'u', 'ụ': 'u',
            'ứ': 'u', 'ừ': 'u', 'ử': 'u', 'ữ': 'u', 'ự': 'u',
            'í': 'i', 'ì': 'i', 'ỉ': 'i', 'ĩ': 'i', 'ị': 'i',
            'đ': 'd',
            'ý': 'y', 'ỳ': 'y', 'ỷ': 'y', 'ỹ': 'y', 'ỵ': 'y'
        };

        return str.split('').map(char => charMap[char] || char).join('');
    };

    // Khởi chạy khi tài liệu đã sẵn sàng
    $(document).ready(function() {
        HT.seoPreview();
    });
})(jQuery);
