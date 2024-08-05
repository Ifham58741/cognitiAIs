@extends('layouts.app')

@section('css')
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center"> 
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0">{{ __('Update Subscription Plan') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-sack-dollar mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.dashboard') }}"> {{ __('Finance Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.plans') }}"> {{ __('Subscription Plans') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Update Subscription Plan') }}</a></li>
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
						<h3 class="card-title">{{ __('Update Subscription Plan') }}: <span class="text-primary font-weight-bold">{{ $id->plan_name }}</span></h3>
					</div>
					<div class="card-body pt-5">									
						<form action="{{ route('admin.finance.plan.update', $id) }}" method="POST" enctype="multipart/form-data">
							@method('PUT')
							@csrf

							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12">						
									<div class="input-box">	
										<h6>{{ __('Plan Status') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
										<select id="plan-status" name="plan-status" class="form-select" data-placeholder="{{ __('Select Plan Status') }}:">			
											<option value="active" @if ($id->status == 'active') selected @endif>{{ __('Active') }}</option>
											<option value="hidden" @if ($id->status == 'hidden') selected @endif>{{ __('Hidden') }}</option>
											<option value="closed" @if ($id->status == 'closed') selected @endif>{{ __('Closed') }}</option>
										</select>
										@error('plan-status')
											<p class="text-danger">{{ $errors->first('plan-status') }}</p>
										@enderror	
									</div>						
								</div>							
								<div class="col-lg-6 col-md-6 col-sm-12">							
									<div class="input-box">								
										<h6>{{ __('Plan Name') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
										<div class="form-group">							    
											<input type="text" class="form-control" id="plan-name" name="plan-name" value="{{ $id->plan_name }}" required>
										</div> 
										@error('plan-name')
											<p class="text-danger">{{ $errors->first('plan-name') }}</p>
										@enderror
									</div> 						
								</div>

								<div class="col-lg-6 col-md-6 col-sm-12">							
									<div class="input-box">								
										<h6>{{ __('Price') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
										<div class="form-group">							    
											<input type="text" class="form-control" id="cost" name="cost" value="{{ $id->price }}" required>
										</div> 
										@error('cost')
											<p class="text-danger">{{ $errors->first('cost') }}</p>
										@enderror
									</div> 						
								</div>

								<div class="col-lg-6 col-md-6 col-sm-12">							
									<div class="input-box">								
										<h6>{{ __('Currency') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
										<select id="currency" name="currency" class="form-select" data-placeholder="{{ __('Select Currency') }}:">			
											@foreach(config('currencies.all') as $key => $value)
												<option value="{{ $key }}" @if( $id->currency == $key) selected @endif>{{ $value['name'] }} - {{ $key }} ({!! $value['symbol'] !!})</option>
											@endforeach
										</select>
										@error('currency')
											<p class="text-danger">{{ $errors->first('currency') }}</p>
										@enderror
									</div> 						
								</div>

								<div class="col-lg-6 col-md-6 col-sm-12">							
									<div class="input-box">								
										<h6>{{ __('Payment Frequence') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
										<select id="frequency" name="frequency" class="form-select" data-placeholder="{{ __('Select Payment Frequency') }}:" data-callback="duration_select">		
											<option value="monthly" @if ($id->payment_frequency == 'monthly') selected @endif>{{ __('Monthly') }}</option>
											<option value="yearly" @if ($id->payment_frequency == 'yearly') selected @endif>{{ __('Yearly') }}</option>
											<option value="lifetime" @if ($id->payment_frequency == 'lifetime') selected @endif>{{ __('Lifetime') }}</option>
											
										</select>
									</div> 						
								</div>

								<div class="col-lg-6 col-md-6 col-sm-12">							
									<div class="input-box">								
										<h6>{{ __('Featured Plan') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
										<select id="featured" name="featured" class="form-select" data-placeholder="{{ __('Select if Plan is Featured') }}:">		
											<option value=1 @if ($id->featured == true) selected @endif>{{ __('Yes') }}</option>
											<option value=0 @if ($id->featured == false) selected @endif>{{ __('No') }}</option>
										</select>
									</div> 						
								</div>

								<div class="col-lg-6 col-md-6 col-sm-12">							
									<div class="input-box">								
										<h6>{{ __('Free Plan') }}</h6>
										<div class="form-group">							    
											<select id="free-plan" name="free-plan" class="form-select" data-placeholder="{{ __('Make this plan a Free Plan?') }}:">			
												<option value=1 @if ($id->free == true) selected @endif>{{ ('Yes') }}</option>
												<option value=0 @if ($id->free == false) selected @endif>{{ ('No') }}</option>
											</select>
										</div> 
										@error('free-plan')
											<p class="text-danger">{{ $errors->first('free-plan') }}</p>
										@enderror
									</div> 						
								</div>

								<div class="col-lg-6 col-md-6 col-sm-12">							
									<div class="input-box">								
										<h6>{{ __('Free Plan Days') }}</h6>
										<div class="form-group">							    
											<input type="number" class="form-control" id="days" name="days" min=0 value="{{ $id->days }}">
										</div> 
										@error('days')
											<p class="text-danger">{{ $errors->first('days') }}</p>
										@enderror
									</div> 						
								</div>
							</div>

							<div class="card mt-6 shadow-0" id="payment-gateways">
								<div class="card-body">
									<h6 class="fs-12 font-weight-bold mb-5"><i class="fa fa-bank text-info fs-14 mr-1 fw-2"></i>{{ __('Payment Gateways Plan IDs') }}</h6>

									<div class="row">								
										<div class="col-lg-6 col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('PayPal Plan ID') }} <span class="text-danger">({{ __('Required for Paypal') }}) <i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('You have to get Paypal Plan ID in your Paypal account. Refer to the documentation if you need help with creating one') }}."></i></span></h6>
												<div class="form-group">							    
													<input type="text" class="form-control" id="paypal_gateway_plan_id" name="paypal_gateway_plan_id" value="{{ $id->paypal_gateway_plan_id }}">
												</div> 
												@error('paypal_gateway_plan_id')
													<p class="text-danger">{{ $errors->first('paypal_gateway_plan_id') }}</p>
												@enderror
											</div> 						
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Stripe Product ID') }} <span class="text-danger">({{ __('Required for Stripe') }}) <i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('You have to get Stripe Product ID in your Stripe account. Refer to the documentation if you need help with creating one') }}."></i></span></h6>
												<div class="form-group">							    
													<input type="text" class="form-control" id="stripe_gateway_plan_id" name="stripe_gateway_plan_id" value="{{ $id->stripe_gateway_plan_id }}">
												</div> 
												@error('stripe_gateway_plan_id')
													<p class="text-danger">{{ $errors->first('stripe_gateway_plan_id') }}</p>
												@enderror
											</div> 						
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Paystack Plan Code') }} <span class="text-danger">({{ __('Required for Paystack') }}) <i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('You have to get Paystack Plan ID in your Paystack account. Refer to the documentation if you need help with creating one') }}."></i></span></h6>
												<div class="form-group">							    
													<input type="text" class="form-control" id="paystack_gateway_plan_id" name="paystack_gateway_plan_id" value="{{ $id->paystack_gateway_plan_id }}">
												</div> 
												@error('paystack_gateway_plan_id')
													<p class="text-danger">{{ $errors->first('paystack_gateway_plan_id') }}</p>
												@enderror
											</div> 						
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Razorpay Plan ID') }} <span class="text-danger">({{ __('Required for Razorpay') }}) <i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('You have to get Razorpay Plan ID in your Razorpay account. Refer to the documentation if you need help with creating one') }}."></i></span></h6>
												<div class="form-group">							    
													<input type="text" class="form-control" id="razorpay_gateway_plan_id" name="razorpay_gateway_plan_id" value="{{ $id->razorpay_gateway_plan_id }}">
												</div> 
												@error('razorpay_gateway_plan_id')
													<p class="text-danger">{{ $errors->first('razorpay_gateway_plan_id') }}</p>
												@enderror
											</div> 						
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">							
											<div class="input-box">																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																															
												<h6>{{ __('Flutterwave Plan ID') }} <span class="text-danger">({{ __('Required for Flutterwave') }}) <i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('You have to get Flutterwave Plan ID in your Flutterwave account. Refer to the documentation if you need help with creating one') }}."></i></span></h6>
												<div class="form-group">							    
													<input type="text" class="form-control" id="flutterwave_gateway_plan_id" name="flutterwave_gateway_plan_id" value="{{ $id->flutterwave_gateway_plan_id }}">
												</div> 
												@error('flutterwave_gateway_plan_id')
													<p class="text-danger">{{ $errors->first('flutterwave_gateway_plan_id') }}</p>
												@enderror
											</div> 						
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Paddle Plan ID') }} <span class="text-danger">({{ __('Required for Paddle') }}) <i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('You have to get Paddle Plan ID in your Paddle account. Refer to the documentation if you need help with creating one') }}."></i></span></h6>
												<div class="form-group">							    
													<input type="text" class="form-control" id="paddle_gateway_plan_id" name="paddle_gateway_plan_id" value="{{ $id->paddle_gateway_plan_id }}">
												</div> 
												@error('paddle_gateway_plan_id')
													<p class="text-danger">{{ $errors->first('paddle_gateway_plan_id') }}</p>
												@enderror
											</div> 						
										</div>
									</div>
								</div>						
							</div>

							<div class="card mt-7 mb-7 shadow-0">
								<div class="card-body">
									<h6 class="fs-12 font-weight-bold mb-5"><i class="fa-solid fa-box-circle-check text-info fs-14 mr-1 fw-2"></i>{{ __('Included AI Credits') }}</h6>

									<div class="row">
										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('GPT 3 Turbo Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gpt_3_turbo" name="gpt_3_turbo" value="{{ $id->gpt_3_turbo_credits }}" required placeholder="0">
													<span class="text-muted fs-10">{{ __('Each text generation task counts output words created') }}. {{ __('Set as -1 for unlimited words') }}. ({{ __('1 credit = 1 word') }}).</span>
												</div> 
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('GPT 4 Turbo Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gpt_4_turbo" name="gpt_4_turbo" value="{{ $id->gpt_4_turbo_credits }}" placeholder="0">
													<span class="text-muted fs-10">{{ __('Each text generation task counts output words created') }}. {{ __('Set as -1 for unlimited words') }}. ({{ __('1 credit = 1 word') }}).</span>
												</div> 
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('GPT 4o Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gpt_4o" name="gpt_4o" value="{{ $id->gpt_4o_credits }}" placeholder="0">
													<span class="text-muted fs-10">{{ __('Each text generation task counts output words created') }}. {{ __('Set as -1 for unlimited words') }}. ({{ __('1 credit = 1 word') }}).</span>
												</div> 
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('GPT 4o mini Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gpt_4o_mini" name="gpt_4o_mini" value="{{ $id->gpt_4o_mini_credits }}" placeholder="0">
													<span class="text-muted fs-10">{{ __('Each text generation task counts output words created') }}. {{ __('Set as -1 for unlimited words') }}. ({{ __('1 credit = 1 word') }}).</span>
												</div> 
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('GPT 4 Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gpt_4" name="gpt_4" value="{{ $id->gpt_4_credits }}" placeholder="0">
													<span class="text-muted fs-10">{{ __('Each text generation task counts output words created') }}. {{ __('Set as -1 for unlimited words') }}. ({{ __('1 credit = 1 word') }}).</span>
												</div> 
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Fine Tuned Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="fine_tune" name="fine_tune" value="{{ $id->fine_tune_credits }}" placeholder="0">
													<span class="text-muted fs-10">{{ __('Each text generation task counts output words created') }}. {{ __('Set as -1 for unlimited words') }}. ({{ __('1 credit = 1 word') }}).</span>
												</div> 
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Claude 3 Opus Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="claude_3_opus" name="claude_3_opus" value="{{ $id->claude_3_opus_credits }}" placeholder="0">
													<span class="text-muted fs-10">{{ __('Each text generation task counts output words created') }}. {{ __('Set as -1 for unlimited words') }}. ({{ __('1 credit = 1 word') }}).</span>
												</div> 
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Claude 3.5 Sonnet Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="claude_3_sonnet" name="claude_3_sonnet" value="{{ $id->claude_3_sonnet_credits }}" placeholder="0">
													<span class="text-muted fs-10">{{ __('Each text generation task counts output words created') }}. {{ __('Set as -1 for unlimited words') }}. ({{ __('1 credit = 1 word') }}).</span>
												</div> 
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Claude 3 Haiku Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="claude_3_haiku" name="claude_3_haiku" value="{{ $id->claude_3_haiku_credits }}" placeholder="0">
													<span class="text-muted fs-10">{{ __('Each text generation task counts output words created') }}. {{ __('Set as -1 for unlimited words') }}. ({{ __('1 credit = 1 word') }}).</span>
												</div> 
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Gemini Pro Model Credits') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="gemini_pro" name="gemini_pro" value="{{ $id->gemini_pro_credits }}" placeholder="0">
													<span class="text-muted fs-10">{{ __('Each text generation task counts output words created') }}. {{ __('Set as -1 for unlimited words') }}. ({{ __('1 credit = 1 word') }}).</span>
												</div> 
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Characters Included') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="characters" name="characters" value="{{ $id->characters }}" placeholder="0">
													<span class="text-muted fs-10">{{ __('For AI Voiceover feature') }}. {{ __('Set as -1 for unlimited characters') }}.</span>
												</div> 
												@error('characters')
													<p class="text-danger">{{ $errors->first('characters') }}</p>
												@enderror
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Dalle Images Included') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="dalle-images" name="dalle-images" value="{{ $id->dalle_images }}">
													<span class="text-muted fs-10">{{ __('Valid for all image sizes') }}. {{ __('Set as -1 for unlimited images') }}.</span>
												</div> 
												@error('dalle-images')
													<p class="text-danger">{{ $errors->first('dalle-images') }}</p>
												@enderror
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Stable Diffusion Images Included') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="sd-images" name="sd-images" value="{{ $id->sd_images }}">
													<span class="text-muted fs-10">{{ __('Valid for all image sizes') }}. {{ __('Set as -1 for unlimited images') }}.</span>
												</div> 
												@error('sd-images')
													<p class="text-danger">{{ $errors->first('sd-images') }}</p>
												@enderror
											</div> 						
										</div>

										<div class="col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Minutes Included') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-muted ml-3">({{ __('Renewed Monthly') }})</span></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="minutes" name="minutes" value="{{ $id->minutes }}" placeholder="0">
													<span class="text-muted fs-10">{{ __('For AI Speech to Text feature') }}. {{ __('Set as -1 for unlimited minutes') }}.</span>
												</div> 
												@error('minutes')
													<p class="text-danger">{{ $errors->first('minutes') }}</p>
												@enderror
											</div> 						
										</div>

									</div>
								</div>
							</div>

							<div class="card mt-7 mb-7 shadow-0">
								<div class="card-body">
									<h6 class="fs-12 font-weight-bold mb-5"><i class="fa-solid fa-box-circle-check text-info fs-14 mr-1 fw-2"></i>{{ __('Included Features') }}</h6>

									<div class="row">			
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Writer Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="writer-feature" class="custom-switch-input" @if ($id->writer_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Image Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="image-feature" class="custom-switch-input" @if ($id->image_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Voiceover Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="voiceover-feature" class="custom-switch-input" @if ($id->voiceover_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Speech to Text Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="whisper-feature" class="custom-switch-input" @if ($id->transcribe_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Chat Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="chat-feature" class="custom-switch-input" @if ($id->chat_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Code Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="code-feature" class="custom-switch-input" @if ($id->code_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Personal OpenAI API Usage Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="personal-openai-api" class="custom-switch-input" @if ($id->personal_openai_api == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Personal Claude API Usage Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="personal-claude-api" class="custom-switch-input" @if ($id->personal_claude_api == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Personal Gemini API Usage Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="personal-gemini-api" class="custom-switch-input" @if ($id->personal_gemini_api == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Personal Stable Diffusion API Usage Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="personal-sd-api" class="custom-switch-input" @if ($id->personal_sd_api == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Article Wizard Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="wizard-feature" class="custom-switch-input" @if ($id->wizard_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>
			
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Vision Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="vision-feature" class="custom-switch-input" @if ($id->vision_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Chat Image Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="chat-image-feature" class="custom-switch-input" @if ($id->chat_image_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI File Chat Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="file-chat-feature" class="custom-switch-input" @if ($id->file_chat_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Internet Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="internet-feature" class="custom-switch-input" @if ($id->internet_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Web Chat Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="chat-web-feature" class="custom-switch-input" @if ($id->chat_web_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Smart Editor Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="smart-editor-feature" class="custom-switch-input" @if ($id->smart_editor_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Rewriter Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="rewriter-feature" class="custom-switch-input" @if ($id->rewriter_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Image to Video Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="video-image-feature" class="custom-switch-input" @if ($id->video_image_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Voice Clone Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="voice-clone-feature" class="custom-switch-input" @if ($id->voice_clone_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Sound Studio Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="sound-studio-feature" class="custom-switch-input" @if ($id->sound_studio_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Plagiarism Checker Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="plagiarism-feature" class="custom-switch-input" @if ($id->plagiarism_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Content Detector Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="detector-feature" class="custom-switch-input" @if ($id->ai_detector_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Personal Custom AI Chat Bot Creation Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="personal-chat-feature" class="custom-switch-input" @if ($id->personal_chats_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Personal Custom Template Creation Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="personal-template-feature" class="custom-switch-input" @if ($id->personal_templates_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Brand Voice Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="brand-voice-feature" class="custom-switch-input" @if ($id->brand_voice_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Integration Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="integration-feature" class="custom-switch-input" @if ($id->integration_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>	

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Photo Studio Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="photo-studio-feature" class="custom-switch-input" @if ($id->photo_studio_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Youtube Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="youtube-feature" class="custom-switch-input" @if ($id->youtube_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI RSS Feature') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<div class="form-group mt-3">
													<label class="custom-switch">
														<input type="checkbox" name="rss-feature" class="custom-switch-input" @if ($id->rss_feature == true) checked @endif>
														<span class="custom-switch-indicator"></span>
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card mt-7 mb-7 shadow-0">
								<div class="card-body">
									<h6 class="fs-12 font-weight-bold mb-5"><i class="fa-solid fa-box-circle-check text-info fs-14 mr-1 fw-2"></i>{{ __('Included Service Limits') }}</h6>

									<div class="row">								
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Available Models for All Templates') }} <i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Subscribers will only have access to the selected models for all AI features related to generating text') }}."></i></h6>
												<select class="form-select" id="templates-models-list" name="templates_models_list[]" data-placeholder="{{ __('Choose Models for Templates') }}" multiple>
													<option value="gpt-3.5-turbo-0125" @foreach ($model_templates as $key=>$value) @if($value == 'gpt-3.5-turbo-0125') selected @endif @endforeach>{{ __('GPT 3.5 Turbo') }}</option>												
													<option value="gpt-4" @foreach ($model_templates as $key=>$value) @if($value == 'gpt-4') selected @endif @endforeach>{{ __('GPT 4') }}</option>
													<option value="gpt-4o" @foreach ($model_templates as $key=>$value) @if($value == 'gpt-4o') selected @endif @endforeach>{{ __('GPT 4o') }}</option>
													<option value="gpt-4o-mini" @foreach ($model_templates as $key=>$value) @if($value == 'gpt-4o-mini') selected @endif @endforeach>{{ __('GPT 4o mini') }}</option>
													<option value="gpt-4-0125-preview" @foreach ($model_templates as $key=>$value) @if($value == 'gpt-4-0125-preview') selected @endif @endforeach>{{ __('GPT 4 Turbo') }}</option>
													<option value="gpt-4-turbo-2024-04-09" @foreach ($model_templates as $key=>$value) @if($value == 'gpt-4-turbo-2024-04-09') selected @endif @endforeach>{{ __('GPT 4 Turbo with Vision') }} ({{ __('Preview') }})</option>	
													<option value='claude-3-opus-20240229' @foreach ($model_templates as $key=>$value) @if($value == 'claude-3-opus-20240229') selected @endif @endforeach>{{ __('Claude 3 Opus') }}</option>																																																																																																																											
													<option value='claude-3-5-sonnet-20240620' @foreach ($model_templates as $key=>$value) @if($value == 'claude-3-5-sonnet-20240620') selected @endif @endforeach>{{ __('Claude 3.5 Sonnet') }}</option>																																																																																																																											
													<option value='claude-3-haiku-20240307' @foreach ($model_templates as $key=>$value) @if($value == 'claude-3-haiku-20240307') selected @endif @endforeach>{{ __('Claude 3 Haiku') }}</option>																																																																																																																										
													<option value='gemini_pro' @foreach ($model_templates as $key=>$value) @if($value == 'gemini_pro') selected @endif @endforeach>{{ __('Gemini Pro') }}</option>																																																																																																																										
													@foreach ($models as $model)
														<option value="{{ $model->model }}" @foreach ($model_templates as $key=>$value) @if($value == $model->model) selected @endif @endforeach>{{ $model->description }} ({{ __('Fine Tune Model')}})</option>
													@endforeach
												</select>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Available Models for All Chat Bots') }} <i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Subscribers will only have access to the selected models for all AI features related to chat bots') }}."></i></h6>
												<select class="form-select" id="chats-models-list" name="chats_models_list[]" data-placeholder="{{ __('Choose Models for Chat Bots') }}" multiple>
													<option value="gpt-3.5-turbo-0125" @foreach ($model_chats as $key=>$value) @if($value == 'gpt-3.5-turbo-0125') selected @endif @endforeach>{{ __('GPT 3.5 Turbo') }}</option>
													<option value="gpt-4" @foreach ($model_chats as $key=>$value) @if($value == 'gpt-4') selected @endif @endforeach>{{ __('GPT 4') }}</option>												
													<option value="gpt-4o" @foreach ($model_chats as $key=>$value) @if($value == 'gpt-4o') selected @endif @endforeach>{{ __('GPT 4o') }}</option>	
													<option value="gpt-4o-mini" @foreach ($model_chats as $key=>$value) @if($value == 'gpt-4o-mini') selected @endif @endforeach>{{ __('GPT 4o mini') }}</option>	
													<option value="gpt-4-0125-preview" @foreach ($model_chats as $key=>$value) @if($value == 'gpt-4-0125-preview') selected @endif @endforeach>{{ __('GPT 4 Turbo') }}</option>
													<option value="gpt-4-turbo-2024-04-09" @foreach ($model_chats as $key=>$value) @if($value == 'gpt-4-turbo-2024-04-09') selected @endif @endforeach>{{ __('GPT 4 Turbo with Vision') }}</option>																																																																																																																										
													<option value='claude-3-opus-20240229' @foreach ($model_chats as $key=>$value) @if($value == 'claude-3-opus-20240229') selected @endif @endforeach>{{ __('Claude 3 Opus') }}</option>																																																																																																																											
													<option value='claude-3-5-sonnet-20240620' @foreach ($model_chats as $key=>$value) @if($value == 'claude-3-5-sonnet-20240620') selected @endif @endforeach>{{ __('Claude 3.5 Sonnet') }}</option>																																																																																																																											
													<option value='claude-3-haiku-20240307' @foreach ($model_chats as $key=>$value) @if($value == 'claude-3-haiku-20240307') selected @endif @endforeach>{{ __('Claude 3 Haiku') }}</option>
													<option value='gemini_pro' @foreach ($model_chats as $key=>$value) @if($value == 'gemini_pro') selected @endif @endforeach>{{ __('Gemini Pro') }}</option>
													@foreach ($models as $model)
														<option value="{{ $model->model }}" @foreach ($model_chats as $key=>$value) @if($value == $model->model) selected @endif @endforeach>{{ $model->description }} ({{ __('Fine Tune Model')}})</option>
													@endforeach
												</select>
											</div>
										</div>		


										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Template Categories Access') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<select id="templates" name="templates" class="form-select" data-placeholder="{{ __('Set Templates Access') }}">
													<option value="all" @if ($id->templates == 'all') selected @endif>{{ __('All Templates') }}</option>
													<option value="free" @if ($id->templates == 'free') selected @endif>{{ __('Only Free Templates') }}</option>																																											
													<option value="standard" @if ($id->templates == 'standard') selected @endif> {{ __('Up to Standard Templates') }}</option>
													<option value="professional" @if ($id->templates == 'professional') selected @endif> {{ __('Up to Professional Templates') }}</option>
													<option value="premium" @if ($id->templates == 'premium') selected @endif> {{ __('Up to Premium Templates') }} ({{ __('All') }})</option>																																																														
												</select>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('AI Chat Categories Access') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
												<select id="chats" name="chats" class="form-select" data-placeholder="{{ __('Set AI Chat Type Access') }}">
													<option value="all" @if ($id->chats == 'all') selected @endif>{{ __('All Chat Types') }}</option>
													<option value="free" @if ($id->chats == 'free') selected @endif>{{ __('Only Free Chat Types') }}</option>																																											
													<option value="standard" @if ($id->chats == 'standard') selected @endif> {{ __('Up to Standard Chat Types') }}</option>
													<option value="professional" @if ($id->chats == 'professional') selected @endif> {{ __('Up to Professional Chat Types') }}</option>
													<option value="premium" @if ($id->chats == 'premium') selected @endif> {{ __('Up to Premium Chat Types') }} ({{ __('All') }})</option>																																																														
												</select>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="input-box">
												<h6>{{ __('Supported AI Voiceover Vendors') }} <i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Only listed TTS voices of the listed vendors will be available for the subscriber. Make sure to include respective vendor API keys in the Davinci settings page.') }}."></i></h6>
												<select class="form-select" id="voiceover-vendors" name="voiceover_vendors[]" data-placeholder="{{ __('Choose Voiceover vendors') }}" multiple>
													<option value='aws' @foreach ($vendors as $key=>$value) @if($value == 'aws') selected @endif @endforeach>{{ __('AWS') }}</option>																															
													<option value='azure' @foreach ($vendors as $key=>$value) @if($value == 'azure') selected @endif @endforeach>{{ __('Azure') }}</option>																																																														
													<option value='gcp' @foreach ($vendors as $key=>$value) @if($value == 'gcp') selected @endif @endforeach>{{ __('GCP') }}</option>																																																														
													<option value='openai' @foreach ($vendors as $key=>$value) @if($value == 'openai') selected @endif @endforeach>{{ __('OpenAI') }}</option>																																																														
													<option value='elevenlabs' @foreach ($vendors as $key=>$value) @if($value == 'elevenlabs') selected @endif @endforeach>{{ __('ElevenLabs') }}</option>																																																																																																																											
												</select>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">							
											<div class="input-box">								
												<h6>{{ __('Number of Team Members') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Define how many team members a user is allowed to create under this subscription plan') }}."></i></h6>
												<div class="form-group">							    
													<input type="number" class="form-control" id="team-members" name="team-members" value="{{ $id->team_members }}" required>
												</div> 
												@error('team-members')
													<p class="text-danger">{{ $errors->first('team-members') }}</p>
												@enderror
											</div> 						
										</div>									

										<div class="row">

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6>{{ __('OpenAI Image Engine') }} <i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Make sure that AI Image Feature is Enabled first and also that this AI Image vendor is enabled in your Davinci Settings page') }}."></i></h6>
													<select id="dalle-image-engine" name="dalle-image-engine" class="form-select">
														<option value='none'>{{ __('Not Allowed') }}</option>
														<option value='dall-e-2' @if ($id->dalle_image_engine == 'dall-e-2') selected @endif>{{ __('Dalle 2') }}</option>
														<option value='dall-e-3' @if ($id->dalle_image_engine == 'dall-e-3') selected @endif> {{ __('Dalle 3') }}</option>																															
														<option value='dall-e-3-hd' @if ($id->dalle_image_engine == 'dall-e-3-hd') selected @endif> {{ __('Dalle 3 HD') }}</option>																																																															
													</select>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">
												<div class="input-box">
													<h6>{{ __('Stable Diffusion Image Engine') }} <i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Make sure that AI Image Feature is Enabled first and also that this AI Image vendor is enabled in your Davinci Settings page') }}."></i></h6>
													<select id="sd-image-engine" name="sd-image-engine" class="form-select">
														<option value='none'>{{ __('Not Allowed') }}</option>	
														<option value='stable-diffusion-v1-6' @if ($id->sd_image_engine == 'stable-diffusion-v1-6') selected @endif>{{ __('Stable Diffusion v1.6') }}</option>
														<option value='stable-diffusion-xl-beta-v2-2-2' @if ($id->sd_image_engine == 'stable-diffusion-xl-beta-v2-2-2') selected @endif> {{ __('Stable Diffusion v2.2.2-XL Beta') }}</option>																															
														<option value='stable-diffusion-xl-1024-v0-9' @if ($id->sd_image_engine == 'stable-diffusion-xl-1024-v0-9') selected @endif> {{ __('SDXL v0.9') }}</option>																															
														<option value='stable-diffusion-xl-1024-v1-0' @if ($id->sd_image_engine == 'stable-diffusion-xl-1024-v1-0') selected @endif> {{ __('SDXL v1.0') }}</option>																																																														
														<option value='sd3' @if ($id->sd_image_engine == 'sd3') selected @endif> {{ __('Stable Diffusion 3.0') }}</option>		
														<option value='sd3-turbo' @if ($id->sd_image_engine == 'sd3-turbo') selected @endif> {{ __('Stable Diffusion 3.0 Turbo') }}</option>		
														<option value='core' @if ($id->sd_image_engine == 'core') selected @endif> {{ __('Stable Image Core') }}</option>	
														<option value='ultra' @if ($id->sd_image_engine == 'ultra') selected @endif> {{ __('Stable Image Ultra') }}</option>	
													</select>
												</div>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">							
												<div class="input-box">								
													<h6>{{ __('Maximum Allowed CSV File Size') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Set the maximum CSV file size limit that subscriber is allowed to process') }}."></i></h6>
													<div class="form-group">							    
														<input type="number" class="form-control" min="0.1" step="0.1" id="chat-csv-file-size" name="chat-csv-file-size" value="{{ $id->chat_csv_file_size }}">
														<span class="text-muted fs-10">{{ __('Maximum Size limit is in Megabytes (MB)') }}.</span>
													</div> 
													@error('chat-csv-file-size')
														<p class="text-danger">{{ $errors->first('chat-csv-file-size') }}</p>
													@enderror
												</div> 						
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">							
												<div class="input-box">								
													<h6>{{ __('Maximum Allowed PDF File Size') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Set the maximum PDF file size limit that subscriber is allowed to process') }}."></i></h6>
													<div class="form-group">							    
														<input type="number" class="form-control" min="0.1" step="0.1" id="chat-pdf-file-size" name="chat-pdf-file-size" value="{{ $id->chat_pdf_file_size }}">
														<span class="text-muted fs-10">{{ __('Maximum Size limit is in Megabytes (MB)') }}.</span>
													</div> 
													@error('chat-pdf-file-size')
														<p class="text-danger">{{ $errors->first('chat-pdf-file-size') }}</p>
													@enderror
												</div> 						
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">							
												<div class="input-box">								
													<h6>{{ __('Maximum Allowed Word File Size') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Set the maximum Word file size limit that subscriber is allowed to process') }}."></i></h6>
													<div class="form-group">							    
														<input type="number" class="form-control" min="0.1" step="0.1" id="chat-word-file-size" name="chat-word-file-size" value="{{ $id->chat_word_file_size }}">
														<span class="text-muted fs-10">{{ __('Maximum Size limit is in Megabytes (MB)') }}.</span>
													</div> 
													@error('chat-word-file-size')
														<p class="text-danger">{{ $errors->first('chat-word-file-size') }}</p>
													@enderror
												</div> 						
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">							
												<div class="input-box">								
													<h6>{{ __('Maximum Allowed Created Voice Clones') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('Set the number of voice clones that user can create') }}."></i></h6>
													<div class="form-group">							    
														<input type="number" class="form-control" min="0" id="voice_clone_number" name="voice_clone_number" value="{{ $id->voice_clone_number }}">
													</div> 
													@error('voice_clone_number')
														<p class="text-danger">{{ $errors->first('voice_clone_number') }}</p>
													@enderror
												</div> 						
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">							
												<div class="input-box">								
													<h6>{{ __('Total Scan tasks for AI Plagiarism Checker') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="number" class="form-control" min="0" id="plagiarism-pages" name="plagiarism-pages" value="{{ $id->plagiarism_pages }}">
													</div> 
													@error('plagiarism-pages')
														<p class="text-danger">{{ $errors->first('plagiarism-pages') }}</p>
													@enderror
												</div> 						
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">							
												<div class="input-box">								
													<h6>{{ __('Total Scan tasks for AI Content Decoder') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
													<div class="form-group">							    
														<input type="number" class="form-control" min="0" id="detector-pages" name="detector-pages" value="{{ $id->ai_detector_pages }}">
													</div> 
													@error('detector-pages')
														<p class="text-danger">{{ $errors->first('detector-pages') }}</p>
													@enderror
												</div> 						
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">							
												<div class="input-box">								
													<h6>{{ __('Image/Video/Voiceover Results Storage Period') }} <span class="text-muted">({{ __('In Days') }})</span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('After set days file results will be deleted via CRON task') }}."></i></h6>
													<div class="form-group">							    
														<input type="number" class="form-control" id="file-result-duration" name="file-result-duration" value="{{ $id->file_result_duration }}">
														<span class="text-muted fs-10">{{ __('Set as -1 for unlimited storage duration') }}.</span>
													</div> 
													@error('file-result-duration')
														<p class="text-danger">{{ $errors->first('file-result-duration') }}</p>
													@enderror
												</div> 						
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">							
												<div class="input-box">								
													<h6>{{ __('Generated Text Content Results Storage Period') }} <span class="text-muted">({{ __('In Days') }})</span><i class="ml-3 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('After set days results will be deleted from database via CRON task') }}."></i></h6>
													<div class="form-group">							    
														<input type="number" class="form-control" id="document-result-duration" name="document-result-duration" value="{{ $id->document_result_duration }}">
														<span class="text-muted fs-10">{{ __('Set as -1 for unlimited storage duration') }}.</span>
													</div> 
													@error('document-result-duration')
														<p class="text-danger">{{ $errors->first('document-result-duration') }}</p>
													@enderror
												</div> 						
											</div>

											<div class="col-lg-6 col-md-6 col-sm-12">							
												<div class="input-box">								
													<h6>{{ __('Max Allowed Words Limit for All Text Results') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span><i class="ml-2 text-dark fs-13 fa-solid fa-circle-info" data-tippy-content="{{ __('OpenAI will treat this limit as a stop marker. i.e. If you set it to 500, openai will try to stop as it will create a text with 500 tokens, but it can also ignore it on some cases') }}."></i></h6>
													<div class="form-group">							    
														<input type="number" class="form-control" id="tokens" name="tokens" value="{{ $id->max_tokens }}" required>
													</div> 
													@error('tokens')
														<p class="text-danger">{{ $errors->first('words') }}</p>
													@enderror
												</div> 						
											</div>										
										</div>
									</div>
								</div>
							</div>

							<div class="row mt-6">
								<div class="col-12">
									<div class="input-box">	
										<h6>{{ __('Primary Heading') }} <span class="text-muted">({{ __('Optional') }})</span></h6>
										<div class="form-group">							    
											<input type="text" class="form-control" id="primary-heading" name="primary-heading" value="{{ $id->primary_heading }}">
										</div>
									</div>
								</div>
							</div>

							<div class="row mt-6">
								<div class="col-lg-12 col-md-12 col-sm-12">	
									<div class="input-box">	
										<h6>{{ __('Plan Features') }} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span> <span class="text-danger ml-3">({{ __('Comma Seperated') }})</span></h6>							
										<textarea class="form-control" name="features" rows="10">{{ $id->plan_features }}</textarea>
										@error('features')
											<p class="text-danger">{{ $errors->first('features') }}</p>
										@enderror	
									</div>											
								</div>
							</div>
							

							<!-- ACTION BUTTON -->
							<div class="border-0 text-center mb-2 mt-1">
								<a href="{{ route('admin.finance.plans') }}" class="btn btn-cancel mr-2 pl-7 pr-7">{{ __('Return') }}</a>
								<button type="submit" class="btn btn-primary pl-7 pr-7">{{ __('Update') }}</button>							
							</div>				

						</form>					
					</div>
				</div>
			</div>
		@endif
	</div>
@endsection

@section('js')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script> 
	<script>

		let list = "{{ $id->voiceover_vendors }}"
		let templates = "{{ $id->model }}"
		let chats = "{{ $id->model_chat }}"
		list = list.split(', ')
		templates = templates.split(', ')
		chats = chats.split(', ')

		$(function(){
			$("#voiceover-vendors").select2({
				theme: "bootstrap-5",
				containerCssClass: "select2--small",
				dropdownCssClass: "select2--small",
			}).val(list).trigger('change.select2');

			$("#templates-models-list").select2({
				theme: "bootstrap-5",
				containerCssClass: "select2--small",
				dropdownCssClass: "select2--small",
			}).val(templates).trigger('change.select2');

			$("#chats-models-list").select2({
				theme: "bootstrap-5",
				containerCssClass: "select2--small",
				dropdownCssClass: "select2--small",
			}).val(chats).trigger('change.select2');
		});


		 function duration_select(value) {
			if (value == 'lifetime') {
				$('#payment-gateways').css('display', 'none');
			} else {
				$('#payment-gateways').css('display', 'block');
			}
		 }
	</script>
@endsection
