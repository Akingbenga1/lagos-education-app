@if (Session::has('success'))
    <div class="text-success" >{!! Session::get('success') !!}</div>
@elseif (Session::has('error'))
    <div class="text-danger">{!! Session::get('error') !!}</div>
@endif