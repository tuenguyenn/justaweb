<table class="table  table-bordered table-hover" style="background-color:white">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center" style="width:70px">Ảnh</th>
            <th class="text-center">Họ Tên</th>
            <th class="text-center">Email</th>
            <th class="text-center">Nguồn khách</th>
            <th class="text-center">Nhóm Khách</th>
           
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($customers) && is_object($customers))
            @foreach($customers as $customer)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $customer->id }}" class="input-checkbox checkboxItem">
                    </td>
                    <td class="text-center">
                        <span class="image img-cover me-2">
                            <img src="{{ $customer->image }}" alt="" style="width: 50px; height: 60px; object-fit: cover;">
                        </span>
                    </td>
                    <td class="text-center">{{ $customer->name }}</td>
                    <td class="text-center">{{ $customer->email }}</td>
                    <td class="text-center">{{ $customer->customer_catalogues->name }}</td>
                    <td class="text-center">{{ $customer->sources->name }}</td>

                 
                    <td class="text-center js-switch-{{$customer->id}}">
                        <input type="checkbox" id="status" 
                            value="{{$customer->publish}}" 
                            class="js-switch status " 
                            data-field="publish"    
                            data-modelId={{$customer->id}} 
                            data-model="Customer" {{($customer->publish==2) ? 'checked': '' }}>
                    </td>
                    <td class="text-center">
                        <a href="{{route('customer.edit', $customer->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>

                        <button type="button" class="btn btn-danger delete-button" 
                            data-id="{{ $customer->id }}" data-name="{{ $customer->name }}" data-model="customer">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div class="pagination-wrapper">
    {{ $customers->links('pagination::bootstrap-5') }}
</div>

