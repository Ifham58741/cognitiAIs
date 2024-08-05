@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center">
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0">{{ __('AI Photo Studio Feature Costs') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-microchip-ai mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.davinci.dashboard') }}"> {{ __('AI Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="#"> {{ __('AI Settings') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('AI Photo Studio Feature Costs') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')						
	<div class="row justify-content-center">
		<div class="col-lg-6 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Set AI Photo Studio Feature Cost per Task') }}</h3>
				</div>
				<div class="card-body pt-5">									
					<form id="" action="{{ route('admin.davinci.configs.sd.store') }}" method="post" enctype="multipart/form-data">
						@csrf

						<div class="row mt-2 pl-5 pr-5">							
							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><span><i class='fa-solid fa-aperture text-muted mr-2'></i></span>{{ __('ReImagine') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" min=1 class="form-control" name="reimagine" value="{{ $studio->sd_photo_studio_reimagine }}">
									</div> 	
								</div> 						
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><span><i class='fa-solid fa-droplet text-muted mr-2'></i></span>{{ __('Erase Objects') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" min=1 class="form-control" name="erase" value="{{ $studio->sd_photo_studio_erase_object }}">
									</div> 	
								</div> 						
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><span><i class='fa-solid fa-hexagon-image text-muted mr-2'></i></span>{{ __('Inpaint Image') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" min=1 class="form-control" name="inpaint" value="{{ $studio->sd_photo_studio_inpaint }}">
									</div> 	
								</div> 						
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><span><i class='fa-solid fa-images text-muted mr-2'></i></span>{{ __('Outpaint Image') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" min=1 class="form-control" name="outpaint" value="{{ $studio->sd_photo_studio_outpaint }}">
									</div> 	
								</div> 						
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><span><i class='fa-solid fa-eye text-muted mr-2'></i></span>{{ __('Search and Replace') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" min=1 class="form-control" name="search" value="{{ $studio->sd_photo_studio_search_replace }}">
									</div> 	
								</div> 						
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><span><i class='fa-solid fa-image-slash text-muted mr-2'></i></span>{{ __('Remove Background') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" min=1 class="form-control" name="background" value="{{ $studio->sd_photo_studio_remove_background }}">
									</div> 	
								</div> 						
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><span><i class='fa-solid fa-pen-clip text-muted mr-2'></i></span>{{ __('Sketch to Image') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" min=1 class="form-control" name="sketch" value="{{ $studio->sd_photo_studio_sketch }}">
									</div> 	
								</div> 						
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><span><i class='fa-solid fa-camera-viewfinder text-muted mr-2'></i></span>{{ __('Structure to Image') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" min=1 class="form-control" name="structure" value="{{ $studio->sd_photo_studio_structure }}">
									</div> 	
								</div> 						
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><span><i class='fa-solid fa-high-definition text-muted mr-2'></i></span>{{ __('Conservative Upscale') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" min=1 class="form-control" name="conservative" value="{{ $studio->sd_photo_studio_conservative_upscaler }}">
									</div> 	
								</div> 						
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><span><i class='fa-solid fa-high-definition text-muted mr-2'></i></span>{{ __('Creative Upscale') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" min=1 class="form-control" name="creative" value="{{ $studio->sd_photo_studio_creative_upscaler }}">
									</div> 	
								</div> 						
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">							
								<div class="input-box">								
									<h6><span><i class='fa-solid fa-wand-magic-sparkles text-muted mr-2'></i></span>{{ __('Text to Image') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
									<div class="form-group">							    
										<input type="number" min=1 class="form-control" name="text" value="{{ $studio->sd_photo_studio_text }}">
									</div> 	
								</div> 						
							</div>
						</div>

						<!-- ACTION BUTTON -->
						<div class="border-0 text-center mb-2 mt-1">
							<a href="{{ route('admin.davinci.configs') }}" class="btn ripple btn-cancel mr-2">{{ __('Return') }}</a>
							<button type="submit" class="btn ripple btn-primary">{{ __('Apply') }}</button>							
						</div>				

					</form>					
				</div>
			</div>
		</div>
	</div>
@endsection

