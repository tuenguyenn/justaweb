<table class="table  table-bordered table-hover" style="background-color:white">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center" style="width:70px">Ảnh</th>
            <th class="text-center">Họ Tên</th>
            <th class="text-center">Email</th>
            <th class="text-center">Vai trò</th>
            <th class="text-center">Liên hệ</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($users) && is_object($users))
            @foreach($users as $user)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $user->id }}" class="input-checkbox checkboxItem">
                    </td>
                    <td class="text-center">
                        <span class="image img-cover me-2">
                            <img src="{{ $user->image }}" alt="" style="width: 50px; height: 60px; object-fit: cover;">
                        </span>
                    </td>
                    <td class="text-center">{{ $user->name }}</td>
                    <td class="text-center">{{ $user->email }}</td>
                    <td class="text-center">{{ $user->user_catalogues->name }}</td>
                    <td class="text-center">{{ $user->phone }}</td>
                 
                    <td class="text-center">
                        <input type="checkbox" id="status" 
                            value="{{ $user->publish }}" 
                            class="js-switch status" 
                            data-field="publish" 
                            data-modelId="{{ $user->id }}" 
                            data-model="User" {{ ($user->publish == 2) ? 'checked' : '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('user.edit', $user->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>

                        <button type="button" class="btn btn-danger delete-button" 
                            data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-model="user">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div class="pagination-wrapper">
    {{ $users->links('pagination::bootstrap-5') }}
</div>

