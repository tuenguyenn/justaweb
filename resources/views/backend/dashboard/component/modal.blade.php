<div class="modal " id="deleteModal" tabindex="-1">
    <div class="modal-dialog ">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h4 class="modal-title">Xác nhận xoá </h4>
                <button type="button" class="close-modal" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa </p>
                <p><strong>Tên:</strong> <span id="deleteName"></span></p>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>

