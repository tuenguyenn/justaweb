

        <div class=" justify-content-center">
            <div class="col-lg-12">
                <div class="page-header">
                    <h1>Quản lý đánh giá</h1>
                   
                </div>
                
                <!-- Filter and Search Section -->
              
                @include('backend.review.component.filter')
                <!-- Comments Table -->
              @if (!is_null($reviews))
              <div class="comments-table">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
                            </th>

                            <th>Khách hàng</th>
                            <th>Đánh giá</th>
                            <th>Số sao</th>
                            <th>Sản phẩm </th>
                            <th>Trạng thái </th>
                            
                            <th>Duyệt</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($reviews as $key => $val)
                        @php
                            $reviewableLink = $val->reviewable->languages->first()->pivot->canonical;
                            $objectName = $val->reviewable->languages->first()->pivot->name;
                        @endphp
                       <tr>
                        <td class="text-center">
                            <input type="checkbox" value="{{ $val->id }}" class="input-checkbox checkboxItem">
                        </td>
                        
                        <td>{{$val->name}}</td>
                        <td>{{$val->description}}
                            
                        </td>
                        <td>{{$val->score}}</td>
                        <td>
                            <a href="{{$reviewableLink}}">{{$objectName}}</a>
                        </td>
                      
                        <td>
                            <span class="status-badge status-{{ $val->status }}">
                                {{ ucfirst($val->status) }}
                            </span>
                            
                        </td>
                        <td class="action-buttons">
                            <button class="btn btn-sm btn-outline-success status-review">Cho phép</button>
                            <button class="btn btn-sm btn-outline-danger status-review">Từ chối</button>
                        </td>
                    </tr>
                       @endforeach
                        
                    </tbody>
                </table>
            </div>
              @endif

                <!-- Pagination -->
                <nav aria-label="Comments pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
   
 