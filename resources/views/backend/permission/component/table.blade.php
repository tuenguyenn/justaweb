<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center" >{{ __('messages.permission.index.title') }}  </th>
            <th class="text-center">Canonical</th>
            <th class="text-center">{{__('messages.tableAction')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($permissions) && is_object($permissions))
            @foreach($permissions as $permission)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $permission->id }}" class="input-checkbox checkboxItem">
                    </td>
                        
                    <td class="text-left">
                        <div class="d-flex align-items-center ">
                          
                            <div class="name">
                                {{$permission->name}} ( <strong>{{$permission->canonical}} </strong>)
                            </div>
                        </div>
                    </td>
                    
                   
                    <td class="text-center">
                        <a href="{{route('permission.edit', $permission->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <button type="button" class="btn btn-danger" onclick="showDeleteModal('{{$permission->id}}', '{{$permission->name}}', '{{$permission->canonical}}')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>   
            @endforeach
        @endif
    </tbody>
</table>

{{-- Pagination --}}
<div class="d-flex justify-content-center">
    {{$permissions->links('pagination::bootstrap-5')}}
</div>

@include('backend.dashboard.component.footer')

<form id="deleteForm" method="POST">
    @csrf
    <!-- Modal -->
    <div class="modal" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xác nhận xoá ngôn ngữ</h4>
                    <button type="button" class="btn-close dong" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa ngôn ngữ: <strong id="deletepermissionName"> </strong>(<span id="deletepermissionCanonical"></span>)</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <input type="hidden" name="_method">
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function showDeleteModal(permissionId, permissionName, permissionCanonical) {
    document.getElementById('deletepermissionName').textContent = permissionName;
    document.getElementById('deletepermissionCanonical').textContent = permissionCanonical;
    const deleteUrl = "{{ route('permission.destroy', ':id') }}".replace(':id', permissionId);
    document.getElementById('deleteForm').action = deleteUrl;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
    document.querySelector('.dong').addEventListener('click', function() {
        deleteModal.hide();
    });
    document.querySelector('.btn-secondary').addEventListener('click', function() {
        deleteModal.hide();
    });
}
document.getElementById('deleteForm').addEventListener('submit', function(event) {
    event.preventDefault();
    this.submit();
});
</script>

