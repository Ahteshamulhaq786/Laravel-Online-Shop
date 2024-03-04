@if(Session::has('success'))

<div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Success!</strong> {{Session::get('success')}}
  </div>

@endif

@if(Session::has('error'))

  <div class="alert alert-danger alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error!</strong> {{Session::get('error')}}
  </div>

  @endif

  @if(Session::has('info'))

  <div class="alert alert-info alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Info!</strong> {{Session::get('info')}}
  </div>

  @endif