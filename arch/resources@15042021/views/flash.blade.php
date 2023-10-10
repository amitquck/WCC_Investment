@if(session()->has('success'))

<div class="alert alert-success animate__backInDown">
{{session()->get('success')}}
</div>
@endif

@if(session()->has('error'))

<div class="alert alert-danger animate__backInDown">
{{session()->get('error')}}
</div>
@endif

@if(session()->has('info'))

<div class="alert alert-info animate__backInDown">
{{session()->get('info')}}
</div>
@endif