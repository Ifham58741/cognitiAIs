@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center"> 
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0">{{ __('Edit Image Prompt') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-microchip-ai mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item"><a href="{{ route('admin.davinci.dashboard') }}"> {{ __('AI Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="#"> {{ __('Image Prompts') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Edit Image Prompt') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')						
	<div class="row justify-content-center">
		<div class="col-lg-8 col-md-12 col-xm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Edit Image Prompt') }}</h3>
				</div>
				<div class="card-body pt-5 pb-0">									
					<form action="{{ route('admin.davinci.image.prompt.update', $prompt->id) }}" method="POST" enctype="multipart/form-data">
						@method('PUT')
						@csrf
						
						<div class="row">
						  <div class="col-md-12 col-sm-12">													
							<div class="input-box">								
							  <h6>{{ __('Title') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
							  <div class="form-group">							    
								<input type="text" class="form-control @error('title') is-danger @enderror" id="title" name="title" value="{{ $prompt->title }}">
								@error('title')
								  <p class="text-danger">{{ $errors->first('title') }}</p>
								@enderror
							  </div> 
							</div> 
						  </div>
					  
						  <div class="col-sm-12">								
							<div class="input-box">								
							  <h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Prompt') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
							  <div class="form-group">
								<div id="field-buttons"></div>							    
								<textarea type="text" rows=5 class="form-control @error('prompt') is-danger @enderror" id="prompt" name="prompt">{{ $prompt->prompt }}</textarea>
								@error('prompt')
								  <p class="text-danger">{{ $errors->first('prompt') }}</p>
								@enderror
							  </div> 
							</div> 
						  </div>
						</div>
					  
						<div class="modal-footer d-inline">
						  <div class="row text-center">
							<div class="col-md-12">
								<a href="{{ route('admin.davinci.image.prompt') }}" class="btn btn-cancel mr-2 pl-7 pr-7">{{ __('Cancel') }}</a>
							  <button type="submit" class="btn btn-primary pl-7 pr-7">{{ __('Update') }}</button>
							</div>
						  </div>
						  
						</div>
					</form>				
				</div>
			</div>
		</div>
	</div>
@endsection


