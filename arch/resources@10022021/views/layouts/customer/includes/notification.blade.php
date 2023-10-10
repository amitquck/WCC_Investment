@if (session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<h4>Success <i class="fa fa-check"></i></h4> {{ session("success") }}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	</div>
@endif

@if (session('error'))
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<h4>Error <i class="fa fa-times"></i></h4> {{ session("error") }}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	</div>
@endif

@if (session('warning'))
	<div class="alert alert-warning alert-dismissible fade show" role="alert">
		<h4>Warning <i class="fa fa-warning"></i></h4> {{ session("warning") }}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	</div>
@endif

@if (session('info'))
	<div class="alert alert-info alert-dismissible fade show" role="alert">
		<h4>Info <i class="fa fa-info"></i></h4> {{ session("info") }}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	</div>
@endif