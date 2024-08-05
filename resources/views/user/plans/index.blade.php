@extends('layouts.app')

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center">
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0"> {{ __('Subscription Plans') }}</h4>
			<ol class="breadcrumb mb-2 justify-content-center">
				<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="fa-solid fa-id-badge mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('user.plans') }}"> {{ __('Pricing Plans') }}</a></li>
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
			<div class="col-lg-11 col-md-12 col-sm-12">
				<div class="card border-0">
					<div class="card-body pl-6" id="subscription-header">
						<div class="row" id="subscription-header-inner">
							<div class="col-lg-4 col-md-4 col-sm-12" style="margin-top: auto; margin-bottom: auto">
								@if (is_null($plan))
									<h3 class="card-title"><i class="fa-solid fa-box-circle-check text-primary mr-2 fs-14"></i>{{ __('Upgrade to premium plan') }}</h3>
									<p class="fs-13 text-muted mb-0">{{ __('You are currently not subscribed to any plan, please select your plan below and get started!') }}</p>
								@else
									<h3 class="card-title fs-16">{{ __('You are subscribed to') }} <span class="text-primary">{{ $plan->plan_name }} </span> {{ __('plan') }}</h3>
									<p class="fs-12 text-muted mb-5">{{ __('It is a') }} <span class="font-weight-bold">{{ __($plan->payment_frequency) }}</span> {{ __('plan with a cost of') }} <span class="font-weight-bold">{{ $plan->price }} {{ $plan->currency }}</span></p>

									@if ($plan->payment_frequency != 'lifetime')
										<p class="fs-12 text-muted mb-2">{{ __('Next billing is on') }} {{ $date }} {{ __('at') }} {{ $time }}</p>
									@endif								
									<a href="{{ route('user.purchases') }}" class="fs-12 cancel-subscription">{{ __('View Orders') }}</a>
									<a href="#" class="fs-12 cancel-subscription initiate-cancellation" id="{{ $subscriber->id }}">{{ __('Cancel Subscription') }}</a>								
								@endif
								
							</div>
			
							<div class="col-lg-8 col-md-8 col-sm-12" style="margin-top: auto; margin-bottom: auto">
								<div class="row text-center">
									<div class="col-lg col-md-6 col-sm-6">
										<h6 class="fs-12 mt-3 font-weight-bold">{{ App\Services\HelperService::mainPlanModel()}} {{ __('Words Left') }}</h6>
										<h4 class="font-weight-800 text-primary fs-20 mb-0">{{ App\Services\HelperService::mainPlanBalance()}}</h4>	
										<div class="view-credits mb-3"><a class=" fs-11 text-muted" href="javascript:void(0)" id="view-credits" data-bs-toggle="modal" data-bs-target="#creditsModel"> {{ __('View All Credits') }}</a></div> 									
									</div>
									@role('user|subscriber|admin')
										@if (config('settings.image_feature_user') == 'allow')
											<div class="col-lg col-md-6 col-sm-6">
												<h6 class="fs-12 mt-3 font-weight-bold">{{ __('DE/SD Images Left') }}</h6>
												<h4 class="mb-3 font-weight-800 text-primary fs-20">@if(auth()->user()->available_dalle_images == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_dalle_images + auth()->user()->available_dalle_images_prepaid + auth()->user()->available_sd_images + auth()->user()->available_sd_images_prepaid) }} @endif</h4>										
											</div>	
										@endif
									@endrole	
									@role('user|subscriber|admin')
										@if (config('settings.voiceover_feature_user') == 'allow')				
											<div class="col-lg col-md-6 col-sm-6">
												<h6 class="fs-12 mt-3 font-weight-bold">{{ __('Characters Left') }}</h6>
												<h4 class="mb-3 font-weight-800 text-primary fs-20">@if(auth()->user()->available_chars == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_chars + auth()->user()->available_chars_prepaid) }} @endif</h4>										
											</div>
										@endif
									@endrole
									@role('user|subscriber|admin')
										@if (config('settings.whisper_feature_user') == 'allow')
											<div class="col-lg col-md-6 col-sm-6">
												<h6 class="fs-12 mt-3 font-weight-bold">{{ __('Minutes Left') }}</h6>
												<h4 class="mb-3 font-weight-800 text-primary fs-20">@if(auth()->user()->available_minutes == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_minutes + auth()->user()->available_minutes_prepaid) }} @endif</h4>										
											</div>
										@endif
									@endrole
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-11 col-md-12 col-sm-12">
				<div class="card border-0 pt-2">
					<div class="card-body">		
						<div class="title text-center mt-5 mb-5 backend-title">
							<h3 class="mb-4 fs-30">{{ __('Our') }} <span>{{ __('Subscription') }}</span> {{ __('Plans') }}</h3>     
							<h6 class="font-weight-normal fs-14 text-center">{{ __('Upgrade to your preferred Subscription Plan or Top Up your credits and get started') }}</h6>                   
						</div>
						@if ($monthly || $yearly || $prepaid || $lifetime)
			
							<div class="tab-menu-heading text-center mb-3">
								<div class="tabs-menu dark-theme-target" >								
									<ul class="nav">
										@if ($prepaid)						
											<li><a href="#prepaid" class="@if (!$monthly && !$yearly && $prepaid && !$lifetime) active @else '' @endif" data-bs-toggle="tab"> {{ __('Prepaid Plans') }}</a></li>
										@endif							
										@if ($monthly)
											<li><a href="#monthly_plans" class="@if (($monthly && $prepaid && $yearly) || ($monthly && !$prepaid && !$yearly) || ($monthly && $prepaid && !$yearly) || ($monthly && !$prepaid && $yearly)) active @else '' @endif" data-bs-toggle="tab"> {{ __('Monthly Plans') }}</a></li>
										@endif	
										@if ($yearly)
											<li><a href="#yearly_plans" class="@if ((!$monthly && !$prepaid && $yearly && !$lifetime) || (!$monthly && $prepaid && $yearly && !$lifetime) || (!$monthly && $prepaid && $yearly && $lifetime))  active @else '' @endif" data-bs-toggle="tab"> {{ __('Yearly Plans') }}</a></li>
										@endif
										@if ($lifetime)
											<li><a href="#lifetime" class="@if ((!$monthly && !$yearly && !$prepaid &&  $lifetime) || (!$monthly && !$yearly && $prepaid &&  $lifetime)) active @else '' @endif" data-bs-toggle="tab"> {{ __('Lifetime Plans') }}</a></li>
										@endif								
									</ul>
								</div>
							</div>
			
						
			
							<div class="tabs-menu-body">
								<div class="tab-content">
			
									@if ($prepaid)
										<div class="tab-pane @if ((!$monthly && $prepaid) && (!$yearly && $prepaid)) active @else '' @endif" id="prepaid">
			
											@if ($prepaids->count())
																
												<div class="row justify-content-md-center">
												
													@foreach ( $prepaids as $prepaid )																			
														<div class="col-lg-4 col-md-6 col-sm-12">
															<div class="price-card pl-3 pr-3 pt-2 mb-6">
																<div class="card p-4 pl-5 prepaid-cards @if ($prepaid->featured) price-card-border @endif">
																	@if ($prepaid->featured)
																		<span class="plan-featured-prepaid">{{ __('Most Popular') }}</span>
																	@endif
																	<div class="plan prepaid-plan">
																		<div class="plan-title">{{ $prepaid->plan_name }} </div>
																		<div class="plan-cost-wrapper mt-2 text-center mb-3 p-1"><span class="plan-cost">@if (config('payment.decimal_points') == 'allow') {{ number_format((float)$prepaid->price, 2) }} @else {{ number_format($prepaid->price) }} @endif</span><span class="prepaid-currency-sign text-muted">{{ $prepaid->currency }}</span></div>	
																		<p class="fs-12 mb-3 text-muted">{{ __('Included Credits') }}</p>	
																		<div class="credits-box">										 
																			@if ($prepaid->gpt_4_credits_prepaid != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('GPT 4 Model Words') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->gpt_4_credits_prepaid) }}</span></p>@endif
																			@if ($prepaid->gpt_4o_credits_prepaid != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('GPT 4o Model Words') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->gpt_4o_credits_prepaid) }}</span></p>@endif
																			@if ($prepaid->gpt_4o_mini_credits_prepaid != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('GPT 4o mini Model Words') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->gpt_4o_mini_credits_prepaid) }}</span></p>@endif
																			@if ($prepaid->gpt_4_turbo_credits_prepaid != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('GPT 4 Turbo Model Words') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->gpt_4_turbo_credits_prepaid) }}</span></p>@endif
																			@if ($prepaid->gpt_3_turbo_credits_prepaid != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('GPT 3.5 Turbo Model Words') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->gpt_3_turbo_credits_prepaid) }}</span></p>@endif
																			@if ($prepaid->fine_tune_credits_prepaid != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Fine Tune Model  Words') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->fine_tune_credits_prepaid) }}</span></p>@endif
																			@if ($prepaid->claude_3_opus_credits_prepaid != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Claude 3 Opus Model Words') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->claude_3_opus_credits_prepaid) }}</span></p>@endif
																			@if ($prepaid->claude_3_sonnet_credits_prepaid != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Claude 3.5 Sonnet Model Words') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->claude_3_sonnet_credits_prepaid) }}</span></p>@endif
																			@if ($prepaid->claude_3_haiku_credits_prepaid != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Claude 3 Haiku Model Words') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->claude_3_haiku_credits_prepaid) }}</span></p>@endif
																			@if ($prepaid->gemini_pro_credits_prepaid != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Gemini Pro Model Words') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->gemini_pro_credits_prepaid) }}</span></p>@endif
																			@if ($prepaid->dalle_images != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Dalle Images Included') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->dalle_images) }}</span></p>@endif
																			@if ($prepaid->sd_images != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('SD Images Included') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->sd_images) }}</span></p>@endif
																			@if ($prepaid->characters != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Characters Included') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->characters) }}</span></p>@endif																							
																			@if ($prepaid->minutes != 0) <p class="fs-12 mt-2 mb-0"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __('Minutes Included') }}: <span class="ml-2 font-weight-bold text-primary">{{ number_format($prepaid->minutes) }}</span></p>@endif	
																		</div>
																		<div class="text-center action-button mt-2 mb-2">
																			<a href="{{ route('user.prepaid.checkout', ['type' => 'prepaid', 'id' => $prepaid->id]) }}" class="btn btn-primary-pricing">{{ __('Select Package') }}</a> 
																		</div>																								                                                                          
																	</div>							
																</div>	
															</div>							
														</div>										
													@endforeach						
			
												</div>
			
											@else
												<div class="row text-center">
													<div class="col-sm-12 mt-6 mb-6">
														<h6 class="fs-12 font-weight-bold text-center">{{ __('No Prepaid plans were set yet') }}</h6>
													</div>
												</div>
											@endif
			
										</div>			
									@endif	
			
									@if ($monthly)	
										<div class="tab-pane @if (($monthly && $prepaid) || ($monthly && !$prepaid) || ($monthly && !$yearly)) active @else '' @endif" id="monthly_plans">
			
											@if ($monthly_subscriptions->count())		
			
												<div class="row justify-content-md-center">
			
													@foreach ( $monthly_subscriptions as $subscription )																			
														<div class="col-lg-3 col-md-4 col-sm-12">
															<div class="pt-2 h-100 prices-responsive pb-6">
																<div class="card p-5 mb-4 pl-6 pr-6 h-100 price-card @if ($subscription->featured) price-card-border @endif">
																	@if ($subscription->featured)
																		<span class="plan-featured">{{ __('Most Popular') }}</span>
																	@endif
																	<div class="plan">			
																		<div class="plan-title">{{ $subscription->plan_name }}</div>	
																		<p class="plan-cost mb-5">																					
																			@if ($subscription->free)
																				{{ __('Free') }}
																			@else
																				{!! config('payment.default_system_currency_symbol') !!}@if(config('payment.decimal_points') == 'allow'){{ number_format((float)$subscription->price, 2) }} @else{{ number_format($subscription->price) }} @endif<span class="fs-12 text-muted"><span class="mr-1">/</span> {{ __('monthly') }}</span>
																			@endif   
																		</p>  																				
																		<div class="text-center action-button mt-2 mb-5">
																			@if (auth()->user()->plan_id == $subscription->id)
																				<a href="#" class="btn btn-primary-pricing"><i class="fa-solid fa-check fs-14 mr-2"></i>{{ __('Subscribed') }}</a> 
																			@else
																				<a href="{{ route('user.plan.subscribe', $subscription->id) }}" class="btn btn-primary-pricing">@if (!is_null(auth()->user()->plan_id)) {{ __('Upgrade to') }} {{ $subscription->plan_name }} @else {{ __('Subscribe Now') }} @endif</a>
																			@endif                                               														
																		</div>
																		<p class="fs-12 mb-3 text-muted">{{ __('Included Features') }}</p>																		
																		<ul class="fs-12 pl-3">	
																			@if ($subscription->gpt_4_turbo_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4T Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4_turbo_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4T') }} {{ number_format($subscription->gpt_4_turbo_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_4_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4 Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4') }} {{ number_format($subscription->gpt_4_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_4o_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4o Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4o_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4o') }} {{ number_format($subscription->gpt_4o_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_4o_mini_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4o  miniModel') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4o_mini_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4o mini') }} {{ number_format($subscription->gpt_4o_mini_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_3_turbo_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 3.5T Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_3_turbo_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 3.5T') }} {{ number_format($subscription->gpt_3_turbo_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->fine_tune_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Fine Tune Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->fine_tune_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Fine Tune Model') }} {{ number_format($subscription->fine_tune_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->claude_3_opus_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Claude 3 Opus Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->claude_3_opus_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Claude 3 Opus Model') }} {{ number_format($subscription->claude_3_opus_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->claude_3_sonnet_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Claude 3.5 Sonnet Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->claude_3_sonnet_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Claude 3.5 Sonnet Model') }} {{ number_format($subscription->claude_3_sonnet_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->claude_3_haiku_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Claude 3 Haiku Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->claude_3_haiku_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Claude 3 Haiku Model') }} {{ number_format($subscription->claude_3_haiku_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gemini_pro_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Gemini Pro Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gemini_pro_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Gemini Pro Model') }} {{ number_format($subscription->gemini_pro_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if (config('settings.image_feature_user') == 'allow')
																				@if ($subscription->dalle_image_engine != 'none')
																					@if ($subscription->dalle_images == -1)
																						<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Dalle images') }}</span></li>
																					@else
																						@if($subscription->dalle_images != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->dalle_images) }}</span> <span class="plan-feature-text">{{ __('Dalle images') }}</span></li> @endif
																					@endif
																				@endif																
																			@endif
																			@if (config('settings.image_feature_user') == 'allow')
																				@if ($subscription->sd_image_engine != 'none')
																					@if ($subscription->sd_images == -1)
																						<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('SD images') }}</span></li>
																					@else
																						@if($subscription->sd_images != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->sd_images) }}</span> <span class="plan-feature-text">{{ __('SD images') }}</span></li> @endif
																					@endif
																				@endif																	
																			@endif
																			@if (config('settings.whisper_feature_user') == 'allow')
																				@if ($subscription->minutes == -1)
																					<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('minutes') }}</span></li>
																				@else
																					@if($subscription->minutes != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->minutes) }}</span> <span class="plan-feature-text">{{ __('minutes') }}</span></li> @endif
																				@endif																	
																			@endif
																			@if (config('settings.voiceover_feature_user') == 'allow')
																				@if ($subscription->characters == -1)
																					<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('characters') }}</span></li>
																				@else
																					@if($subscription->characters != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->characters) }}</span> <span class="plan-feature-text">{{ __('characters') }}</span></li> @endif
																				@endif																	
																			@endif
																				@if($subscription->team_members != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->team_members) }}</span> <span class="plan-feature-text">{{ __('team members') }}</span></li> @endif
																			
																			@if (config('settings.writer_feature_user') == 'allow')
																				@if($subscription->writer_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Writer Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.wizard_feature_user') == 'allow')
																				@if($subscription->wizard_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Article Wizard Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.smart_editor_feature_user') == 'allow')
																				@if($subscription->smart_editor_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Smart Editor Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.rewriter_feature_user') == 'allow')
																				@if($subscription->rewriter_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI ReWriter Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.chat_feature_user') == 'allow')
																				@if($subscription->chat_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Chats Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.image_feature_user') == 'allow')
																				@if($subscription->image_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Images Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.voiceover_feature_user') == 'allow')
																				@if($subscription->voiceover_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Voiceover Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.video_feature_user') == 'allow')
																				@if($subscription->video_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Video Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.voice_clone_feature_user') == 'allow')
																				@if($subscription->voice_clone_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Voice Clone Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.sound_studio_feature_user') == 'allow')
																				@if($subscription->sound_studio_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Sound Studio Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.whisper_feature_user') == 'allow')
																				@if($subscription->transcribe_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Speech to Text Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.plagiarism_checker_feature_user') == 'allow')
																				@if($subscription->plagiarism_checker_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Plagiarism Checker Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.vision_feature_user') == 'allow')
																				@if($subscription->vision_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Vision Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.ai_detector_feature_user') == 'allow')
																				@if($subscription->ai_detector_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Detector Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.chat_file_feature_user') == 'allow')
																				@if($subscription->chat_file_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI File Chat Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.chat_web_feature_user') == 'allow')
																				@if($subscription->chat_web_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Web Chat Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.code_feature_user') == 'allow')
																				@if($subscription->code_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Code Feature') }}</span></li> @endif
																			@endif
																			@if($subscription->team_members) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Team Members Option') }}</span></li> @endif
																			@foreach ( (explode(',', $subscription->plan_features)) as $feature )
																				@if ($feature)
																					<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __($feature) }}</li>
																				@endif																
																			@endforeach															
																		</ul>																
																	</div>					
																</div>	
															</div>							
														</div>										
													@endforeach
			
												</div>	
											
											@else
												<div class="row text-center">
													<div class="col-sm-12 mt-6 mb-6">
														<h6 class="fs-12 font-weight-bold text-center">{{ __('No Subscriptions plans were set yet') }}</h6>
													</div>
												</div>
											@endif					
										</div>	
									@endif	
									
									@if ($yearly)	
										<div class="tab-pane @if (($yearly && $prepaid) && ($yearly && !$prepaid) && ($yearly && !$monthly)) active @else '' @endif" id="yearly_plans">
			
											@if ($yearly_subscriptions->count())		
			
												<div class="row justify-content-md-center">
			
													@foreach ( $yearly_subscriptions as $subscription )																			
														<div class="col-lg-4 col-md-6 col-sm-12">
															<div class="pt-2 h-100 prices-responsive pb-6">
																<div class="card p-5 mb-4 pl-6 pr-6 h-100 price-card @if ($subscription->featured) price-card-border @endif">
																	@if ($subscription->featured)
																		<span class="plan-featured">{{ __('Most Popular') }}</span>
																	@endif
																	<div class="plan">			
																		<div class="plan-title">{{ $subscription->plan_name }}</div>	
																		<p class="plan-cost mb-5">
																			@if ($subscription->free)
																				{{ __('Free') }}
																			@else
																				{!! config('payment.default_system_currency_symbol') !!}@if(config('payment.decimal_points') == 'allow'){{ number_format((float)$subscription->price, 2) }} @else{{ number_format($subscription->price) }} @endif<span class="fs-12 text-muted"><span class="mr-1">/</span> {{ __('yearly') }}</span>
																			@endif    
																		</p> 																				
																		<div class="text-center action-button mt-2 mb-5">
																			@if (auth()->user()->plan_id == $subscription->id)
																				<a href="#" class="btn btn-primary-pricing"><i class="fa-solid fa-check fs-14 mr-2"></i>{{ __('Subscribed') }}</a> 
																			@else
																				<a href="{{ route('user.plan.subscribe', $subscription->id) }}" class="btn btn-primary-pricing">@if (!is_null(auth()->user()->plan_id)) {{ __('Upgrade to') }} {{ $subscription->plan_name }} @else {{ __('Subscribe Now') }} @endif</a>
																			@endif                                                														
																		</div>
																		<p class="fs-12 mb-3 text-muted">{{ __('Included Features') }}</p>																	
																		<ul class="fs-12 pl-3">	
																			@if ($subscription->gpt_4_turbo_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4T Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4_turbo_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4T') }} {{ number_format($subscription->gpt_4_turbo_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_4_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4 Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4') }} {{ number_format($subscription->gpt_4_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_4o_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4o Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4o_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4o') }} {{ number_format($subscription->gpt_4o_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_4o_mini_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4o  miniModel') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4o_mini_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4o mini') }} {{ number_format($subscription->gpt_4o_mini_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_3_turbo_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 3.5T Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_3_turbo_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 3.5T') }} {{ number_format($subscription->gpt_3_turbo_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->fine_tune_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Fine Tune Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->fine_tune_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Fine Tune Model') }} {{ number_format($subscription->fine_tune_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->claude_3_opus_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Claude 3 Opus Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->claude_3_opus_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Claude 3 Opus Model') }} {{ number_format($subscription->claude_3_opus_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->claude_3_sonnet_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Claude 3.5 Sonnet Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->claude_3_sonnet_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Claude 3.5 Sonnet Model') }} {{ number_format($subscription->claude_3_sonnet_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->claude_3_haiku_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Claude 3 Haiku Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->claude_3_haiku_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Claude 3 Haiku Model') }} {{ number_format($subscription->claude_3_haiku_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gemini_pro_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Gemini Pro Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gemini_pro_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Gemini Pro Model') }} {{ number_format($subscription->gemini_pro_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if (config('settings.image_feature_user') == 'allow')
																				@if ($subscription->dalle_image_engine != 'none')
																					@if ($subscription->dalle_images == -1)
																						<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Dalle images') }}</span></li>
																					@else
																						@if($subscription->dalle_images != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->dalle_images) }}</span> <span class="plan-feature-text">{{ __('Dalle images') }}</span></li> @endif
																					@endif
																				@endif																
																			@endif
																			@if (config('settings.image_feature_user') == 'allow')
																				@if ($subscription->sd_image_engine != 'none')
																					@if ($subscription->sd_images == -1)
																						<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('SD images') }}</span></li>
																					@else
																						@if($subscription->sd_images != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->sd_images) }}</span> <span class="plan-feature-text">{{ __('SD images') }}</span></li> @endif
																					@endif
																				@endif																	
																			@endif
																			@if (config('settings.whisper_feature_user') == 'allow')
																				@if ($subscription->minutes == -1)
																					<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('minutes') }}</span></li>
																				@else
																					@if($subscription->minutes != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->minutes) }}</span> <span class="plan-feature-text">{{ __('minutes') }}</span></li> @endif
																				@endif																	
																			@endif
																			@if (config('settings.voiceover_feature_user') == 'allow')
																				@if ($subscription->characters == -1)
																					<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('characters') }}</span></li>
																				@else
																					@if($subscription->characters != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->characters) }}</span> <span class="plan-feature-text">{{ __('characters') }}</span></li> @endif
																				@endif																	
																			@endif
																				@if($subscription->team_members != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->team_members) }}</span> <span class="plan-feature-text">{{ __('team members') }}</span></li> @endif
																			
																			@if (config('settings.writer_feature_user') == 'allow')
																				@if($subscription->writer_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Writer Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.wizard_feature_user') == 'allow')
																				@if($subscription->wizard_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Article Wizard Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.smart_editor_feature_user') == 'allow')
																				@if($subscription->smart_editor_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Smart Editor Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.rewriter_feature_user') == 'allow')
																				@if($subscription->rewriter_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI ReWriter Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.chat_feature_user') == 'allow')
																				@if($subscription->chat_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Chats Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.image_feature_user') == 'allow')
																				@if($subscription->image_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Images Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.voiceover_feature_user') == 'allow')
																				@if($subscription->voiceover_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Voiceover Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.video_feature_user') == 'allow')
																				@if($subscription->video_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Video Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.voice_clone_feature_user') == 'allow')
																				@if($subscription->voice_clone_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Voice Clone Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.sound_studio_feature_user') == 'allow')
																				@if($subscription->sound_studio_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Sound Studio Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.whisper_feature_user') == 'allow')
																				@if($subscription->transcribe_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Speech to Text Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.plagiarism_checker_feature_user') == 'allow')
																				@if($subscription->plagiarism_checker_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Plagiarism Checker Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.vision_feature_user') == 'allow')
																				@if($subscription->vision_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Vision Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.ai_detector_feature_user') == 'allow')
																				@if($subscription->ai_detector_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Detector Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.chat_file_feature_user') == 'allow')
																				@if($subscription->chat_file_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI File Chat Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.chat_web_feature_user') == 'allow')
																				@if($subscription->chat_web_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Web Chat Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.code_feature_user') == 'allow')
																				@if($subscription->code_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Code Feature') }}</span></li> @endif
																			@endif
																			@if($subscription->team_members) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Team Members Option') }}</span></li> @endif
																			@foreach ( (explode(',', $subscription->plan_features)) as $feature )
																				@if ($feature)
																					<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __($feature) }}</li>
																				@endif																
																			@endforeach															
																		</ul>																	
																	</div>					
																</div>	
															</div>							
														</div>											
													@endforeach
			
												</div>	
											
											@else
												<div class="row text-center">
													<div class="col-sm-12 mt-6 mb-6">
														<h6 class="fs-12 font-weight-bold text-center">{{ __('No Subscriptions plans were set yet') }}</h6>
													</div>
												</div>
											@endif					
										</div>
									@endif	
									
									@if ($lifetime)
										<div class="tab-pane @if ((!$monthly && $lifetime) && (!$yearly && $lifetime)) active @else '' @endif" id="lifetime">
			
											@if ($lifetime_subscriptions->count())                                                    
												
												<div class="row justify-content-md-center">
												
													@foreach ( $lifetime_subscriptions as $subscription )																			
														<div class="col-lg-4 col-md-6 col-sm-12">
															<div class="pt-2 h-100 prices-responsive pb-6">
																<div class="card p-5 mb-4 pl-6 pr-6 h-100 price-card @if ($subscription->featured) price-card-border @endif">
																	@if ($subscription->featured)
																		<span class="plan-featured">{{ __('Most Popular') }}</span>
																	@endif
																	<div class="plan">			
																		<div class="plan-title">{{ $subscription->plan_name }}</div>	
																		<p class="plan-cost mb-5">
																			@if ($subscription->free)
																				{{ __('Free') }}
																			@else
																				{!! config('payment.default_system_currency_symbol') !!}@if(config('payment.decimal_points') == 'allow'){{ number_format((float)$subscription->price, 2) }} @else{{ number_format($subscription->price) }} @endif<span class="fs-12 text-muted"><span class="mr-1">/</span> {{ __('forever') }}</span>
																			@endif
																		</p>																					
																		<div class="text-center action-button mt-2 mb-5">
																			@if (auth()->user()->plan_id == $subscription->id)
																				<a href="#" class="btn btn-primary-pricing"><i class="fa-solid fa-check fs-14 mr-2"></i>{{ __('Subscribed') }}</a> 
																			@else
																				<a href="{{ route('user.prepaid.checkout', ['type' => 'lifetime', 'id' => $subscription->id]) }}" class="btn btn-primary-pricing">@if (!is_null(auth()->user()->plan_id)) {{ __('Upgrade to') }} {{ $subscription->plan_name }} @else {{ __('Subscribe Now') }} @endif</a>
																			@endif                                                 														
																		</div>
																		<p class="fs-12 mb-3 text-muted">{{ __('Included Features') }}</p>																	
																		<ul class="fs-12 pl-3">	
																			@if ($subscription->gpt_4_turbo_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4T Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4_turbo_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4T') }} {{ number_format($subscription->gpt_4_turbo_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_4_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4 Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4') }} {{ number_format($subscription->gpt_4_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_4o_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4o Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4o_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4o') }} {{ number_format($subscription->gpt_4o_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_4o_mini_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 4o  miniModel') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_4o_mini_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 4o mini') }} {{ number_format($subscription->gpt_4o_mini_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gpt_3_turbo_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('GPT 3.5T Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gpt_3_turbo_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('GPT 3.5T') }} {{ number_format($subscription->gpt_3_turbo_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->fine_tune_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Fine Tune Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->fine_tune_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Fine Tune Model') }} {{ number_format($subscription->fine_tune_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->claude_3_opus_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Claude 3 Opus Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->claude_3_opus_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Claude 3 Opus Model') }} {{ number_format($subscription->claude_3_opus_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->claude_3_sonnet_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Claude 3.5 Sonnet Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->claude_3_sonnet_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Claude 3.5 Sonnet Model') }} {{ number_format($subscription->claude_3_sonnet_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->claude_3_haiku_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Claude 3 Haiku Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->claude_3_haiku_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Claude 3 Haiku Model') }} {{ number_format($subscription->claude_3_haiku_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if ($subscription->gemini_pro_credits == -1)
																				<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Gemini Pro Model') }} {{ __('words') }}</span></li>
																			@else	
																				@if($subscription->gemini_pro_credits != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Gemini Pro Model') }} {{ number_format($subscription->gemini_pro_credits) }}</span> <span class="plan-feature-text">{{ __('words') }}</span></li> @endif
																			@endif
																			@if (config('settings.image_feature_user') == 'allow')
																				@if ($subscription->dalle_image_engine != 'none')
																					@if ($subscription->dalle_images == -1)
																						<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('Dalle images') }}</span></li>
																					@else
																						@if($subscription->dalle_images != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->dalle_images) }}</span> <span class="plan-feature-text">{{ __('Dalle images') }}</span></li> @endif
																					@endif
																				@endif																
																			@endif
																			@if (config('settings.image_feature_user') == 'allow')
																				@if ($subscription->sd_image_engine != 'none')
																					@if ($subscription->sd_images == -1)
																						<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('SD images') }}</span></li>
																					@else
																						@if($subscription->sd_images != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->sd_images) }}</span> <span class="plan-feature-text">{{ __('SD images') }}</span></li> @endif
																					@endif
																				@endif																	
																			@endif
																			@if (config('settings.whisper_feature_user') == 'allow')
																				@if ($subscription->minutes == -1)
																					<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('minutes') }}</span></li>
																				@else
																					@if($subscription->minutes != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->minutes) }}</span> <span class="plan-feature-text">{{ __('minutes') }}</span></li> @endif
																				@endif																	
																			@endif
																			@if (config('settings.voiceover_feature_user') == 'allow')
																				@if ($subscription->characters == -1)
																					<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ __('Unlimited') }}</span> <span class="plan-feature-text">{{ __('characters') }}</span></li>
																				@else
																					@if($subscription->characters != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->characters) }}</span> <span class="plan-feature-text">{{ __('characters') }}</span></li> @endif
																				@endif																	
																			@endif
																				@if($subscription->team_members != 0) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="font-weight-bold">{{ number_format($subscription->team_members) }}</span> <span class="plan-feature-text">{{ __('team members') }}</span></li> @endif
																			
																			@if (config('settings.writer_feature_user') == 'allow')
																				@if($subscription->writer_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Writer Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.wizard_feature_user') == 'allow')
																				@if($subscription->wizard_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Article Wizard Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.smart_editor_feature_user') == 'allow')
																				@if($subscription->smart_editor_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Smart Editor Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.rewriter_feature_user') == 'allow')
																				@if($subscription->rewriter_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI ReWriter Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.chat_feature_user') == 'allow')
																				@if($subscription->chat_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Chats Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.image_feature_user') == 'allow')
																				@if($subscription->image_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Images Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.voiceover_feature_user') == 'allow')
																				@if($subscription->voiceover_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Voiceover Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.video_feature_user') == 'allow')
																				@if($subscription->video_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Video Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.voice_clone_feature_user') == 'allow')
																				@if($subscription->voice_clone_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Voice Clone Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.sound_studio_feature_user') == 'allow')
																				@if($subscription->sound_studio_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Sound Studio Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.whisper_feature_user') == 'allow')
																				@if($subscription->transcribe_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Speech to Text Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.plagiarism_checker_feature_user') == 'allow')
																				@if($subscription->plagiarism_checker_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Plagiarism Checker Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.vision_feature_user') == 'allow')
																				@if($subscription->vision_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Vision Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.ai_detector_feature_user') == 'allow')
																				@if($subscription->ai_detector_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Detector Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.chat_file_feature_user') == 'allow')
																				@if($subscription->chat_file_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI File Chat Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.chat_web_feature_user') == 'allow')
																				@if($subscription->chat_web_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Web Chat Feature') }}</span></li> @endif
																			@endif
																			@if (config('settings.code_feature_user') == 'allow')
																				@if($subscription->code_feature) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('AI Code Feature') }}</span></li> @endif
																			@endif
																			@if($subscription->team_members) <li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> <span class="plan-feature-text">{{ __('Team Members Option') }}</span></li> @endif
																			@foreach ( (explode(',', $subscription->plan_features)) as $feature )
																				@if ($feature)
																					<li class="fs-13 mb-3"><i class="fa-solid fa-check fs-14 mr-2 text-success"></i> {{ __($feature) }}</li>
																				@endif																
																			@endforeach															
																		</ul>																	
																	</div>					
																</div>	
															</div>							
														</div>											
													@endforeach					
			
												</div>
			
											@else
												<div class="row text-center">
													<div class="col-sm-12 mt-6 mb-6">
														<h6 class="fs-12 font-weight-bold text-center">{{ __('No lifetime plans were set yet') }}</h6>
													</div>
												</div>
											@endif
			
										</div>	
									@endif	
								</div>
							</div>
						
						@else
							<div class="row text-center">
								<div class="col-sm-12 mt-6 mb-6">
									<h6 class="fs-12 font-weight-bold text-center">{{ __('No Subscriptions or Prepaid plans were set yet') }}</h6>
								</div>
							</div>
						@endif
			
					</div>
				</div>
			</div>
		@endif
	</div>
@endsection

@section('js')
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script type="text/javascript">
		$(function () {

			"use strict";


			// CANCEL SUBSCRIPTION
			$(document).on('click', '.initiate-cancellation', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Confirm Subscription Cancellation') }}',
					text: '{{ __('It will cancel this subscription plan going forward') }}',
					icon: 'warning',
					showCancelButton: true,
					cancelButtonText: '{{ __('No Way') }}',
					confirmButtonText: '{{ __('Yes, I want to Cancel') }}',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						var formData = new FormData();
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: '/user/purchases/subscriptions/cancel',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data['status'] == 200) {
									Swal.fire('{{__('Subscription Cancelled')}}', data['message'], 'success');	
									$("#mySubscriptionsTable").DataTable().ajax.reload();								
								} else {
									Swal.fire('{{ __('Cancellation Failed') }}', data['message'], 'error');
								}      
							},
							error: function(data) {
								console.log(data)
								Swal.fire('Oops...','Something went wrong!', 'error')
							}
						})
					} 
				})
			});

		});
	</script>
@endsection



