(function($) {
    "use strict";

    var HT = {};
    var _token =$('meta[name="csrf-token"]').attr('content')

    HT.initDeleteModal = (BASE_URL) => {
        $('.delete-button').on('click', function() {
            const Id = $(this).data('id');
            const Name = $(this).data('name');
            const model = $(this).data('model');

            $('#deleteName').text(Name);

            const deleteForm = $('#deleteForm');
            deleteForm.attr('action', BASE_URL + model + '/destroy/' + Id);

            $('#deleteModal').css('display', 'block');
        });

        $('.close-modal, .btn-cancel').on('click', function() {
            $('#deleteModal').css('display', 'none');
        });

        $('#deleteForm').on('submit', function(e) {
            e.preventDefault();

            const form = this;
            form.submit();
        });
    };

    $(document).ready(function() {
        HT.initDeleteModal(BASE_URL);
    });
    
})(jQuery);
