@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center"> 
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0">{{ __('New Prepaid Plan') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-sack-dollar mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.dashboard') }}"> {{ __('Finance Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.prepaid') }}"> {{ __('Prepaid Plans') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('New Prepaid Plan') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')						
	<div class="row justify-content-center">
		@if ($type == 'Regular License' && $status)
			<div class="row text-center justify-content-center">
				<p class="fs-14" style="background:#FFE2E5; color:#ff0000; padding:1rem 2rem; border-radius: 0.5rem; max-width: 1200px;">{{ __('Extended License is required in order to have access to these features') }}</p>
			</div>	
		@else
			<div class="col-lg-8 col-md-10 col-sm-12">
				<div class="card border-0">
					<div class="card-header">
						<h3 class="card-title">{{ __('Create New Prepaid Plan') }}</h3>
					</div>
					<div class="card-body pt-5">									
						<form action="{{ route('admin.finance.prepaid.store') }}" method="POST" enctype="multipart/form-data">
							@csrf

							<div class="row">

								<div class="col-lg-6 col-md-6 col-sm-12">						
									<div class="input-box">	
										<h6>{{ __('Plan Status') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
										<select id="plan-status" name="plan-status" class="form-select" data-placeholder="{{ __('Select Plan Status') }}:">			
											<option value="active" selected>{{ __('Active') }}</option>
											<option value="closed">{{ __('Closed') }}</option>
										</select>
										@error('plan-status')
											<p class="text-danger">{{ $errors->first('plan-status') }}</p>
										@enderror	
									</div>						
								</div>
								
								<div class="col-lg-6 col-md-6col-sm-12">							
									<div class="input-box">								
										<h6>{{ __('Plan Name') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> </h6>
										<div class="form-group">							    
											<input type="text" class="form-control" id="plan-name" name="plan-name" value="{{ old('plan-name') }}" required>
										</div> 
										@error('plan-name')
											<p class="text-danger">{{ $errors->first('plan-name') }}</p>
										@enderror
									</div> 						
								</div>
							</div>

							<div class="row mt-2">							
								<div class="col-lg-6 col-md-6col-sm-12">							
									<div class="input-box">								
										<h6>{{ __('Price') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
										<div class="form-group">							    
											<input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
										</div> 
										@error('price')
											<p class="text-danger">{{ $errors->first('price') }}</p>
										@enderror
									</div> 						
								</div>

								<div class="col-lg-6 col-md-6col-sm-12">							
									<div class="input-box">								
										<h6>{{ __('Currency') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
										<select id="currency" name="currency" class="form-select" data-placeholder="{{ __('Select Currency') }}:">		
											@foreach(config('currencies.all') as $key => $value)
												<option value="{{ $key }}" @if(config('payment.default_system_currency') == $key) selected @endif>{{ $value['name'] }} - {{ $key }} ({!! $value['symbol'] !!})</option>
											@endforeach
										</select>
										@error('currency')
											<p class="text-danger">{{ $errors->first('currency') }}</p>
										@enderror
									</div> 						
								</div>

								<div class="col-lg-6 col-md-6 col-sm-12">							
									<div class="input-box">								
										<h6>{{ __('Featured Plan') }}</h6>
										<select id="featured" name="featured" class="form-select" data-placeholder="{{ __('Select if Plan is Featured') }}:">		
											<option value=1>{{ __('Yes') }}</option>
											<option value=0 selected>{{ __('No') }}</option>
										</select>
									</div> 						
								</div>
							</div>

							<div class="card mt-6 special-shadow border-0">
								<div class="card-body">
									<h6 class="fs-12 font-weight-bold mb-5"><i class="fa-solid fa-box-circle-check text-info fs-14 mr-1 fw-2"></i>{{ __('Included Credits') }}</h6>

									<div class="row">								
										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('GPT 4 Turbo Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gpt_4_turbo" min=0 name="gpt_4_turbo" value="0">
													<span class="text-muted fs-10">{{ __('For AI Templates and AI Chat features') }}</span>
												</div> 
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('GPT 4 Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gpt_4" min=0 name="gpt_4" value="0">
													<span class="text-muted fs-10">{{ __('For AI Templates and AI Chat features') }}</span>
												</div> 
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('GPT 4o Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gpt_4o" min=0 name="gpt_4o" value="0">
													<span class="text-muted fs-10">{{ __('For AI Templates and AI Chat features') }}</span>
												</div> 
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('GPT 4o mini Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gpt_4o_mini" min=0 name="gpt_4o_mini" value="0">
													<span class="text-muted fs-10">{{ __('For AI Templates and AI Chat features') }}</span>
												</div> 
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('GPT 3.5 Turbo Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gpt_3_turbo" min=0 name="gpt_3_turbo" value="0">
													<span class="text-muted fs-10">{{ __('For AI Templates and AI Chat features') }}</span>
												</div> 
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Fine Tuned Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="fine_tune" min=0 name="fine_tune" value="0">
													<span class="text-muted fs-10">{{ __('For AI Templates and AI Chat features') }}</span>
												</div> 
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Claude 3 Opus Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="claude_3_opus" min=0 name="claude_3_opus" value="0">
													<span class="text-muted fs-10">{{ __('For AI Templates and AI Chat features') }}</span>
												</div> 
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Claude 3.5 Sonnet Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="claude_3_sonnet" min=0 name="claude_3_sonnet" value="0">
													<span class="text-muted fs-10">{{ __('For AI Templates and AI Chat features') }}</span>
												</div> 
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Claude 3 Haiku Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="claude_3_haiku" min=0 name="claude_3_haiku" value="0">
													<span class="text-muted fs-10">{{ __('For AI Templates and AI Chat features') }}</span>
												</div> 
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Gemini Pro Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gemini_pro" min=0 name="gemini_pro" value="0">
													<span class="text-muted fs-10">{{ __('For AI Templates and AI Chat features') }}</span>
												</div> 
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Characters Included') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="characters" name="characters" value="0">
													<span class="text-muted fs-10">{{ __('For AI Voiceover feature') }}</span>
												</div> 
												@error('characters')
													<p class="text-danger">{{ $errors->first('characters') }}</p>
												@enderror
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Dalle Images Included') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="dalle_images" name="dalle_images" value="0">
													<span class="text-muted fs-10">{{ __('Valid for all images sizes') }}</span>
												</div> 
												@error('dalle_images')
													<p class="text-danger">{{ $errors->first('dalle_images') }}</p>
												@enderror
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Stable Diffusion Images Included') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="sd_images" name="sd_images" value="0">
													<span class="text-muted fs-10">{{ __('Valid for all images sizes') }}</span>
												</div> 
												@error('sd_images')
													<p class="text-danger">{{ $errors->first('sd_images') }}</p>
												@enderror
											</div> 						
										</div>

										<div class="col-lg-6 col-md-12 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Minutes Included') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="minutes" name="minutes" value="0">
													<span class="text-muted fs-10">{{ __('For AI Speech to Text feature') }}</span>
												</div> 
												@error('minutes')
													<p class="text-danger">{{ $errors->first('minutes') }}</p>
												@enderror
											</div> 						
										</div>
									</div>
								</div>
							</div>

							<!-- ACTION BUTTON -->
							<div class="border-0 text-center mb-2 mt-1">
								<a href="{{ route('admin.finance.prepaid') }}" class="btn btn-cancel ripple mr-2 pl-7 pr-7">{{ __('Return') }}</a>
								<button type="submit" class="btn btn-primary ripple pl-7 pr-7">{{ __('Create') }}</button>							
							</div>				

						</form>					
					</div>
				</div>
			</div>
		@endif
	</div>
@endsection

