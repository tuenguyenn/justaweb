@include('backend.dashboard.component.breadcumb', [
    'title' => ($config['method'] == 'edit' ? $config['seo']['title'] : $config['seo']['title'])
])

<form action="{{route('user.catalogue.updatePermission')}}"  class="box" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="table-responsive ">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th class="text-dark">Quyền</th>
                        @foreach($userCatalogues as $userCatalogue)
                    
                            <th class="text-dark">{{ $userCatalogue->name }}</th>
                        @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td class="text-primary">{{ $permission->name }}</td>
                        @foreach($userCatalogues as $userCatalogue)
                            <td class="text-center">
                                <div class="form-check">
                                    <input 
                                        {{(collect($userCatalogue->permissions)->contains('id',$permission->id)) ? 'checked' :''}}
                                        class="form-check-input" 
                                        type="checkbox"     
                                        value="{{$permission->id}}" 
                                        name="permission[{{ $userCatalogue->id }}][]"
                                        id="flexCheckDefault">
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary " name="send" value="send">{{__('messages.btnSave')}}</button>

    </div>

    
</form>