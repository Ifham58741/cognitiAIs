@extends('layouts.app')
@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
<!-- PAGE HEADER -->
<div class="page-header mt-5-7 justify-content-center">
	<div class="page-leftheader text-center">
		<h4 class="page-title mb-0"><i class="text-primary mr-2 fs-16 fa-solid fa-rectangles-mixed"></i>{{ __('Integrations') }}</h4>
		<h6 class="text-muted">{{ __('Posts your contents directly to your favorite CMS') }}</h6>
		<ol class="breadcrumb mb-2 justify-content-center">
			<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="fa-solid fa-id-badge mr-2 fs-12"></i>{{ __('User') }}</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Integrations') }}</a></li>
		</ol>
	</div>
</div>
<!-- END PAGE HEADER -->
@endsection
@section('content')	
	<div class="row justify-content-center">
		@if ($type == 'Regular License' || $type == '')
			<div class="row text-center justify-content-center">
				<p class="fs-14" style="background:#FFE2E5; color:#ff0000; padding:1rem 2rem; border-radius: 0.5rem; max-width: 1200px;">{{ __('Extended License is required in order to have access to these features') }}</p>
			</div>	
		@else
			<div class="col-lg-10 col-md-12 col-sm-12">
				<div class="card border-0 p-6 pt-7 pb-7">
					<div class="card-body pt-2">
						@foreach ($integrations as $integration)
							<div class="col-4">
								<div class="cms-box text-center">	
									@if (!is_null($wordpress))
										<span class="cms-status @if ($wordpress) cms-active @else cms-deactive @endif">@if ($wordpress) {{ __('Activated') }} @else {{ __('Not Activated') }} @endif</span>
									@else
										<span class="cms-status cms-deactive">{{ __('Not Activated') }}</span>
									@endif															
									<img class="cms-image mb-4" src="{{ URL::asset($integration->logo) }}" alt="">							
									<h5 class="cms-title font-weight-semibold fs-18">{{ ucfirst($integration->app) }}</h5>
									<p class="cms-description fs-14 text-muted">{{ __($integration->description) }}</p>
									<a href="{{ route('user.integration.edit', $integration->id) }}" class="cms-action ripple btn btn-primary pl-8 pr-8 fs-12">{{ __('Configure') }}</a>
								</div>
							</div>
						@endforeach						
					</div>
				</div>
			</div>
		@endif
	</div>
@endsection

@section('js')
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
@endsection