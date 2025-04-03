
@if ($momo['m2signature'] == $momo['partnerSignature'])
    @if ($momo['message'] == 'Successful')
        <div class="alert alert-success">
            <strong>Giao dịch : </strong>Thành công
    @else
        <div class="alert alert-danger">
            <strong>Trạng thái </strong>{{ $momo['message']}}
        </div>
    @endif
@else
    <div class="alert alert-danger">
        Giao dịch không thành công
    </div>
@endif
