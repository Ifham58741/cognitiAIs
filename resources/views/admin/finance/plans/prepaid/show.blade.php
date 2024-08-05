@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center">
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0">{{ __('View Prepaid Plan') }}</h4>
			<ol class="breadcrumb mb-2">
				<ol class="breadcrumb mb-2">
					<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-sack-dollar mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.dashboard') }}"> {{ __('Finance Management') }}</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.finance.prepaid') }}"> {{ __('Prepaid Plans') }}</a></li>
					<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('View Prepaid Plan') }}</a></li>
				</ol>
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
			<div class="col-lg-6 col-md-6 col-xm-12">
				<div class="card border-0">
					<div class="card-header">
						<h3 class="card-title">{{ __('Prepaid Plan Name') }}: <span class="text-info">{{ $id->plan_name }}</span> </h3>
					</div>
					<div class="card-body pt-5">		

						<div class="row">
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Plan Type') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ __('Prepaid') }}</span>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Plan Name') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ ucfirst($id->plan_name) }}</span>
								</div>	
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Plan Status') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ ucfirst($id->status) }}</span>
								</div>	
							</div>										
						
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Price') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ $id->price }}</span>
								</div>	
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Currency') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ $id->currency }}</span>
								</div>	
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Created Date') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ date_format($id->created_at, 'M d Y, H:i:s') }}</span>
								</div>							
							</div>	

							<h6 class="fs-12 font-weight-bold text-center mb-5 mt-2"><i class="fa-solid fa-box-circle-check text-info fs-14 mr-1 fw-2"></i>{{ __('Included AI Credits') }}</h6>
							

							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('GPT 4 Model Credits') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->gpt_4_credits_prepaid) }}</span>
								</div>							
							</div>	
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('GPT 4o Model Credits') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->gpt_4o_credits_prepaid) }}</span>
								</div>							
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('GPT 4o mini Model Credits') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->gpt_4o_mini_credits_prepaid) }}</span>
								</div>							
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('GPT 4 Turbo Model Credits') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->gpt_4_turbo_credits_prepaid) }}</span>
								</div>							
							</div>	
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('GPT 3 Turbo Model Credits') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->gpt_3_turbo_credits_prepaid) }}</span>
								</div>							
							</div>												

							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Fine Tune Model Credits') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->fine_tune_credits_prepaid) }}</span>
								</div>							
							</div>	
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Claude 3 Opus Model Credits') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->claude_3_opus_credits_prepaid) }}</span>
								</div>							
							</div>	
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Claude 3 Sonnet Model Credits') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->claude_3_sonnet_credits_prepaid) }}</span>
								</div>							
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Claude 3 Haiku Model Credits') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->claude_3_haiku_credits_prepaid) }}</span>
								</div>							
							</div>	
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Gemini Pro Model Credits') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->gemini_pro_credits_prepaid) }}</span>
								</div>							
							</div>	
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Characters Included') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->characters) }}</span>
								</div>							
							</div>												

							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Dalle Images Included') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->dalle_images) }}</span>
								</div>	
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Stable Diffusion Images Included') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->sd_images) }}</span>
								</div>	
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="prepaid-view-box text-center">
									<h6 class="text-muted fs-12 mb-1">{{ __('Minutes Included') }} </h6>
									<span class="fs-14 font-weight-semibold">{{ number_format($id->minutes) }}</span>
								</div>
							</div>
						</div>

						<!-- SAVE CHANGES ACTION BUTTON -->
						<div class="border-0 text-center mb-2 mt-2">
							<a href="{{ route('admin.finance.prepaid') }}" class="btn btn-cancel ripple mr-2 pl-7 pr-7">{{ __('Return') }}</a>
							<a href="{{ route('admin.finance.prepaid.edit', $id) }}" class="btn btn-primary ripple pl-7 pr-7">{{ __('Edit Plan') }}</a>						
						</div>
					</div>
				</div>
			</div>
		@endif
	</div>
@endsection

