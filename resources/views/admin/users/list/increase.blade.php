@extends('layouts.app')

@section('page-header')
	<!-- EDIT PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center">
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0">{{ __('Update User Credits') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-id-badge mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.user.dashboard') }}"> {{ __('User Management') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.user.list') }}">{{ __('User List') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Update User Credits') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<div class="row justify-content-center">
		<div class="col-lg-8 col-md-12 col-sm-12">
			<div class="card border-0">
				<div class="card-header">
					<h3 class="card-title"><i class="fa-solid fa-id-badge mr-2 text-primary fs-14"></i>{{ __('Update User Credits') }}</h3>
				</div>
				<div class="card-body">
					<form method="POST" action="{{ route('admin.user.increase', [$user->id]) }}" enctype="multipart/form-data">
						@csrf
						
						<div class="row">

							<div class="col-sm-12 col-md-12 mt-2">
								<div>
									<p class="fs-12 mb-2">{{ __('Full Name') }}: <span class="font-weight-bold ml-2 text-primary">{{ $user->name }}</span></p>
									<p class="fs-12 mb-2">{{ __('Email Address') }}: <span class="font-weight-bold ml-2">{{ $user->email }}</span></p>
									<p class="fs-12 mb-2">{{ __('User Group') }}: <span class="font-weight-bold ml-2">{{ ucfirst($user->group) }}</span></p>
								</div>
								<div class="row mt-4">
									<div class="col-sm-12 col-md-6">
										<p class="fs-12 mb-2">{{ __('Available GPT 3.5 Turbo Model Words') }}: <span class="font-weight-bold ml-2">@if ($user->gpt_3_turbo_credits == -1) {{ __('Unlimited') }} @else {{ number_format($user->gpt_3_turbo_credits) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available GPT 4 Turbo Model Words') }}: <span class="font-weight-bold ml-2">@if ($user->gpt_4_turbo_credits == -1) {{ __('Unlimited') }} @else {{ number_format($user->gpt_4_turbo_credits) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available GPT 4 Model Words') }}: <span class="font-weight-bold ml-2">@if ($user->gpt_4_credits == -1) {{ __('Unlimited') }} @else {{ number_format($user->gpt_4_credits) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available GPT 4o Model Words') }}: <span class="font-weight-bold ml-2">@if ($user->gpt_4o_credits == -1) {{ __('Unlimited') }} @else {{ number_format($user->gpt_4o_credits) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available GPT 4o mini Model Words') }}: <span class="font-weight-bold ml-2">@if ($user->gpt_4o_mini_credits == -1) {{ __('Unlimited') }} @else {{ number_format($user->gpt_4o_mini_credits) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Fine Tune Model Words') }}: <span class="font-weight-bold ml-2">@if ($user->fine_tune_credits == -1) {{ __('Unlimited') }} @else {{ number_format($user->fine_tune_credits) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Claude 3 Opus Model Words') }}: <span class="font-weight-bold ml-2">@if ($user->claude_3_opus_credits == -1) {{ __('Unlimited') }} @else {{ number_format($user->claude_3_opus_credits) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Claude 3 Sonnet Model Words') }}: <span class="font-weight-bold ml-2">@if ($user->claude_3_sonnet_credits == -1) {{ __('Unlimited') }} @else {{ number_format($user->claude_3_sonnet_credits) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Claude 3 Haiku Model Words') }}: <span class="font-weight-bold ml-2">@if ($user->claude_3_haiku_credits == -1) {{ __('Unlimited') }} @else {{ number_format($user->claude_3_haiku_credits) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Gemini Pro Model Words') }}: <span class="font-weight-bold ml-2">@if ($user->gemini_pro_credits == -1) {{ __('Unlimited') }} @else {{ number_format($user->gemini_pro_credits) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Dalle Images') }}: <span class="font-weight-bold ml-2">@if ($user->available_dalle_images == -1) {{ __('Unlimited') }} @else {{ number_format($user->available_dalle_images ) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Stable Diffusion Images') }}: <span class="font-weight-bold ml-2">@if ($user->available_sd_images == -1) {{ __('Unlimited') }} @else {{ number_format($user->available_sd_images ) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Characters') }}: <span class="font-weight-bold ml-2">@if ($user->available_chars == -1) {{ __('Unlimited') }} @else {{ number_format($user->available_chars) }} @endif</span></p>
										<p class="fs-12 mb-2">{{ __('Available Minutes') }}: <span class="font-weight-bold ml-2">@if ($user->available_minutes == -1) {{ __('Unlimited') }} @else {{ number_format($user->available_minutes) }} @endif</span></p>
									</div>
									<div class="col-sm-12 col-md-6">
										<p class="fs-12 mb-2">{{ __('Available Prepaid GPT 3.5 Turbo Model Words') }}: <span class="font-weight-bold ml-2">{{ number_format($user->gpt_3_turbo_credits_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid GPT 4 Turbo Model Words') }}: <span class="font-weight-bold ml-2">{{ number_format($user->gpt_4_turbo_credits_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid GPT 4 Model Words') }}: <span class="font-weight-bold ml-2">{{ number_format($user->gpt_4_credits_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid GPT 4o Model Words') }}: <span class="font-weight-bold ml-2">{{ number_format($user->gpt_4o_credits_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid GPT 4o mini Model Words') }}: <span class="font-weight-bold ml-2">{{ number_format($user->gpt_4o_mini_credits_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Fine Tune Model Words') }}: <span class="font-weight-bold ml-2">{{ number_format($user->fine_tune_credits_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Claude 3 Opus Model Words') }}: <span class="font-weight-bold ml-2">{{ number_format($user->claude_3_opus_credits_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Claude 3 Sonnet Model Words') }}: <span class="font-weight-bold ml-2">{{ number_format($user->claude_3_sonnet_credits_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Claude 3 Haiku Model Words') }}: <span class="font-weight-bold ml-2">{{ number_format($user->claude_3_haiku_credits_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Gemini Pro Model Words') }}: <span class="font-weight-bold ml-2">{{ number_format($user->gemini_pro_credits_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Dalle Images') }}: <span class="font-weight-bold ml-2">{{ number_format($user->available_dalle_images_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Stable Diffusion Images') }}: <span class="font-weight-bold ml-2">{{ number_format($user->available_sd_images_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Characters') }}: <span class="font-weight-bold ml-2">{{ number_format($user->available_chars_prepaid) }}</span></p>
										<p class="fs-12 mb-2">{{ __('Available Prepaid Minutes') }}: <span class="font-weight-bold ml-2">{{ number_format($user->available_minutes_prepaid) }}</span></p>
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User GPT 3.5 Turbo Credits') }}</label>
										<input type="number" class="form-control @error('gpt-3-turbo') is-danger @enderror" value={{ $user->gpt_3_turbo_credits }} name="gpt-3-turbo">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited words') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Prepaid GPT 3.5 Turbo Credits') }}</label>
										<input type="number" class="form-control @error('gpt-3-turbo-prepaid') is-danger @enderror" value={{ $user->gpt_3_turbo_credits_prepaid }} name="gpt-3-turbo-prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User GPT 4 Turbo Credits') }}</label>
										<input type="number" class="form-control @error('gpt-4-turbo') is-danger @enderror" value={{ $user->gpt_4_turbo_credits }} name="gpt-4-turbo">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited words') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Prepaid GPT 4 Turbo Credits') }}</label>
										<input type="number" class="form-control @error('gpt-4-turbo-prepaid') is-danger @enderror" value={{ $user->gpt_4_turbo_credits_prepaid }} name="gpt-4-turbo-prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User GPT 4 Credits') }}</label>
										<input type="number" class="form-control @error('gpt-4') is-danger @enderror" value={{ $user->gpt_4_credits }} name="gpt-4">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited words') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Prepaid GPT 4 Credits') }}</label>
										<input type="number" class="form-control @error('gpt-4-prepaid') is-danger @enderror" value={{ $user->gpt_4_credits_prepaid }} name="gpt-4-prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User GPT 4o Credits') }}</label>
										<input type="number" class="form-control @error('gpt-4o') is-danger @enderror" value={{ $user->gpt_4o_credits }} name="gpt-4o">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited words') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Prepaid GPT 4o Credits') }}</label>
										<input type="number" class="form-control @error('gpt-4o-prepaid') is-danger @enderror" value={{ $user->gpt_4o_credits_prepaid }} name="gpt-4o-prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User GPT 4o mini Credits') }}</label>
										<input type="number" class="form-control @error('gpt-4o-mini') is-danger @enderror" value={{ $user->gpt_4o_mini_credits }} name="gpt-4o-mini">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited words') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Prepaid GPT 4o mini Credits') }}</label>
										<input type="number" class="form-control @error('gpt-4o-mini-prepaid') is-danger @enderror" value={{ $user->gpt_4o_mini_credits_prepaid }} name="gpt-4o-mini-prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Fine Tune Credits') }}</label>
										<input type="number" class="form-control @error('fine-tune') is-danger @enderror" value={{ $user->fine_tune_credits }} name="fine-tune">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited words') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Prepaid Fine Tune Credits') }}</label>
										<input type="number" class="form-control @error('fine-tune-prepaid') is-danger @enderror" value={{ $user->fine_tune_credits_prepaid }} name="fine-tune-prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Claude 3 Opus Credits') }}</label>
										<input type="number" class="form-control @error('claude-3-opus') is-danger @enderror" value={{ $user->claude_3_opus_credits }} name="claude-3-opus">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited words') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Prepaid Claude 3 Opus Credits') }}</label>
										<input type="number" class="form-control @error('claude-3-opus-prepaid') is-danger @enderror" value={{ $user->claude_3_opus_credits_prepaid }} name="claude-3-opus-prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Claude 3 Sonnet Credits') }}</label>
										<input type="number" class="form-control @error('claude-3-sonnet') is-danger @enderror" value={{ $user->claude_3_sonnet_credits }} name="claude-3-sonnet">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited words') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Prepaid Claude 3 Sonnet Credits') }}</label>
										<input type="number" class="form-control @error('claude-3-sonnet-prepaid') is-danger @enderror" value={{ $user->claude_3_sonnet_credits_prepaid }} name="claude-3-sonnet-prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Claude 3 Haiku Credits') }}</label>
										<input type="number" class="form-control @error('claude-3-haiku') is-danger @enderror" value={{ $user->claude_3_haiku_credits }} name="claude-3-haiku">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited words') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Prepaid Claude 3 Haiku Credits') }}</label>
										<input type="number" class="form-control @error('claude-3-haiku-prepaid') is-danger @enderror" value={{ $user->claude_3_haiku_credits_prepaid }} name="claude-3-haiku-prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Gemini Pro Credits') }}</label>
										<input type="number" class="form-control @error('gemini-pro') is-danger @enderror" value={{ $user->gemini_pro_credits }} name="gemini-pro">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited words') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6 mt-3">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-scroll-old mr-2 text-info"></i>{{ __('User Prepaid Gemini Pro Credits') }}</label>
										<input type="number" class="form-control @error('gemini-pro-prepaid') is-danger @enderror" value={{ $user->gemini_pro_credits_prepaid }} name="gemini-pro-prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-image mr-2 text-info"></i>{{ __('User Dalle Image Credits') }}</label>
										<input type="number" class="form-control @error('dalle-images') is-danger @enderror" value={{ $user->available_dalle_images }} name="dalle-images">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited images') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-image mr-2 text-info"></i>{{ __('User Prepaid Dalle Image Credits') }}</label>
										<input type="number" class="form-control @error('dalle_images_prepaid') is-danger @enderror" value={{ $user->available_dalle_images_prepaid }} name="dalle_images_prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-image mr-2 text-info"></i>{{ __('User Stable Diffusion Image Credits') }}</label>
										<input type="number" class="form-control @error('sd-images') is-danger @enderror" value={{ $user->available_sd_images }} name="sd-images">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited images') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-image mr-2 text-info"></i>{{ __('User Prepaid Stable Diffusion Image Credits') }}</label>
										<input type="number" class="form-control @error('sd_images_prepaid') is-danger @enderror" value={{ $user->available_sd_images_prepaid }} name="sd_images_prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-waveform-lines mr-2 text-info"></i>{{ __('User Character Credits') }}</label>
										<input type="number" class="form-control @error('chars') is-danger @enderror" value={{ $user->available_chars }} name="chars">	
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited characters') }}</span>							
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-waveform-lines mr-2 text-info"></i>{{ __('User Prepaid Character Credits') }}</label>
										<input type="number" class="form-control @error('chars_prepaid') is-danger @enderror" value={{ $user->available_chars_prepaid }} name="chars_prepaid">								
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-folder-music mr-2 text-info"></i>{{ __('User Minutes Credits') }}</label>
										<input type="number" class="form-control @error('minutes') is-danger @enderror" value={{ $user->available_minutes }} name="minutes">
										<span class="text-muted fs-10">{{ __('Set as -1 for unlimited minutes') }}</span>									
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-6">
								<div class="input-box mb-4">
									<div class="form-group">
										<label class="form-label fs-12 font-weight-bold"><i class="fa-solid fa-folder-music mr-2 text-info"></i>{{ __('User Prepaid Minutes Credits') }}</label>
										<input type="number" class="form-control @error('minutes_prepaid') is-danger @enderror" value={{ $user->available_minutes_prepaid }} name="minutes_prepaid">									
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer border-0 text-center pr-0">							
							<a href="{{ route('admin.user.list') }}" class="btn btn-cancel mr-2">{{ __('Return') }}</a>
							<button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
