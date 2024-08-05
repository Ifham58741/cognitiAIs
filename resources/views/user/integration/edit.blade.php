@extends('layouts.app')

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
@endsection

@section('content')	
	<div class="row justify-content-center">
		<div class="col-lg-10 col-md-12 col-sm-12">
			<div class="card border-0">				
				<div class="card-body pt-5">
					<form class="w-100" action="{{ route('user.integration.update', $id) }}" method="POST" enctype="multipart/form-data">
						@method('PUT')
						@csrf
						<div class="row justify-content-center">	

							<div class="col-lg-5 col-md-8 col-sm-12">

								<div class="text-center mt-5">
									<img class="mb-4" style="max-width: 9rem" src="{{ URL::asset($id->logo) }}" alt="">	
								</div>								

								<p class="fs-14 text-muted text-center mb-7">{{ __('Provide your') }} {{ __($id->app) }} {{ __('connection informaiton') }}</p>

								@foreach ($fields as $field)
									<div class="col-sm-12">													
										<div class="input-box">								
											<h6>{{ __($field['title']) }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
											<div class="form-group">							    
												<input type="text" class="form-control" name="{{ $field['name'] }}" 
												@if (!is_null($credentials))
													@foreach ($credentials as $key=>$credential)
														@if ($field['name'] == $key)
															value="{{ $credential }}"
														@endif
													@endforeach
												@endif
												required>
											</div> 
										</div> 
									</div>
								@endforeach

								<div class="col-lg-6 col-md-6 col-sm-12">
									<div class="input-box">										
										<div class="form-group mt-3">
											<label class="custom-switch">
												<input type="checkbox" name="status" class="custom-switch-input" @if (!is_null($current) && $current->status) checked @endif>
												<span class="custom-switch-indicator"></span>
												<span class="ml-2">{{ __('Activate') }}</span>
											</label>
										</div>
									</div>
								</div>

								<div class="col-sm-12 text-center mb-5">
									<button type="submit" class="btn btn-primary ripple pl-8 pr-8">{{ __('Save') }}</button>	
								</div>	
							</div>					
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

