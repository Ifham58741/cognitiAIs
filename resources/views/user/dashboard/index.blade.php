@extends('layouts.app')

@section('css')
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __('Dashboard') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-chart-tree-map mr-2 fs-12"></i>{{ __('AI Panel') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Dashboard') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<!-- USER PROFILE PAGE -->
	<div class="row">
		<div class="col-lg-5 col-md-12">
			<div class="card border-0">
				<div class="card-body pt-4 pb-4 pl-6 pr-6 custom-banner-bg">
					<div class="custom-banner-bg-image"></div>
					<div class="row">
						<div class="col-md-8 col-sm-12">
							<span class="fs-10"><i class="fa-solid fa-calendar mr-2"></i> {{ now()->format('M d, Y H:i A'); }}</span>
							<h4 class="mb-4 mt-2 font-weight-800 fs-24">{{ __('Welcome') }}, {{ auth()->user()->name }}</h4>
							<span class="fs-10 custom-span">{{ __('Current Plan') }}</span>
							@if (is_null(auth()->user()->plan_id))
								<h4 class="mb-2 mt-2 font-weight-800 fs-24">{{ __('No Active Plan') }}</h4>						
								<h6 class="fs-12">{{ __('You do not have an active subscription') }}</h6>
							@else
								<h4 class="mb-2 mt-2 font-weight-800 fs-24">{{ $subscription }} {{ __('Plan') }}</h4>
							@endif
						</div>
						<div class="col-md-4 col-sm-12 d-flex align-items-end justify-content-end">
							<div class="text-center">
								@if (!is_null($price))
									@if ($term == 'lifetime')
										<h4 class="mb-2 mt-2 font-weight-bold fs-18">{{ __('Lifetime Plan') }}</h4>		
									@else
										<h4 class="mb-2 mt-2 font-weight-bold fs-18">{!! config('payment.default_system_currency_symbol') !!}{{ $price }} / @if ($term == 'monthly'){{ __('month') }} @else {{ __('year') }} @endif</h4>		
									@endif									
								@else
									<h4 class="mb-2 mt-2 font-weight-bold fs-18">{!! config('payment.default_system_currency_symbol') !!}0 / {{ __('month') }}</h4>
								@endif								
								<a href="{{ route('user.plans') }}" class="btn btn-primary yellow mt-2 custom-pricing-plan-button mb-2">{{ __('See Pricing Plans') }} <i class="fa-regular fa-chevron-right fs-8 ml-1"></i></a>
							</div>
							
						</div>
					</div>
					
					
					
				</div>
			</div>
		</div>

		<div class="col-lg col-md-12 d-flex align-items-stretch">
			<div class="card border-0">
				<div class="card-body p-6 align-items-center">
					<div class="row" style="height: 100%">
						<div class="col-md-6 col-sm-12 text-left mt-auto">
							<h6 class="fs-14 text-muted"><i class="fa-solid fa-badge-dollar mr-2"></i>{{ __('Your balance') }}</h6>
							<h4 class="mt-4 mb-5 font-weight-bold text-muted fs-30">{!! config('payment.default_system_currency_symbol') !!}{{ number_format(auth()->user()->balance) }}</h4>
							<h6 class="fs-14 text-muted">{{ __('Current referral earnings') }}</h6>	
						</div>
						<div class="col-md-6 col-sm-12 d-flex align-items-end justify-content-end">
							<a href="{{ route('user.referral') }}" class="btn btn-primary yellow mt-2 mb-0 pl-6 pr-6" style="text-transform: none;">{{ __('Invite & Earn') }} <i class="fa-regular fa-chevron-right fs-8 ml-1"></i></a>
						</div>
					</div>		
				</div>
			</div>
		</div>

		<div class="col-lg col-md-12 d-flex align-items-stretch">
			<div class="card border-0">
				<div class="card-body pr-0 pb-0">
					<div class="row" style="height: 100%">
						<div class="col-md-6 col-sm-12 justify-content-center mt-auto mb-auto">
							<h6 class="fs-14 text-muted"><i class="fa-solid fa-clock mr-2"></i>{{ __('Time Saved') }}</h6>
							<h4 class="mt-4 mb-5 font-weight-bold text-muted fs-30">{{ number_format($total_words) }}</h4>
							<h6 class="fs-14 text-muted">{{ __('Total hours you saved') }}</h6>
						</div>
						<div class="col-md-6 col-sm-12 d-flex align-items-end justify-content-end" style="margin-bottom: -5px">
							<canvas id="hoursSavedChart" style="max-height: 130px"></canvas>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>

	<div class="row">

		<div class="col-lg-12 col-md-12 mt-3">
			<div class="card border-0">
				<div class="card-body pt-5 pb-5 pl-6 pr-6">
					<div class="row text-center mb-4">
						<div class="col-lg col-md-6 col-sm-6 dashboard-border-right mt-auto mb-auto">
							<h6 class="fs-12 mt-3 font-weight-bold">{{ App\Services\HelperService::mainPlanModel()}} {{ __('Words Left') }}</h6>
							<h4 class="mb-0 font-weight-800 text-primary fs-20">{{ App\Services\HelperService::mainPlanBalance()}}</h4>
							<div class="view-credits mb-3"><a class=" fs-11 text-muted" href="javascript:void(0)" id="view-credits" data-bs-toggle="modal" data-bs-target="#creditsModel"> {{ __('View All Credits') }}</a></div> 										
						</div>
						@role('user|subscriber|admin')
							@if (config('settings.image_feature_user') == 'allow')
								<div class="col-lg col-md-6 col-sm-6 dashboard-border-right mt-auto mb-auto">
									<h6 class="fs-12 mt-3 font-weight-bold">{{ __('DE/SD Images Left') }}</h6>
									<h4 class="mb-3 font-weight-800 text-primary fs-20">@if(auth()->user()->available_dalle_images == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_dalle_images + auth()->user()->available_dalle_images_prepaid + auth()->user()->available_sd_images + auth()->user()->available_sd_images_prepaid) }} @endif</h4>										
								</div>	
							@endif
						@endrole	
						@role('user|subscriber|admin')
							@if (config('settings.voiceover_feature_user') == 'allow')				
								<div class="col-lg col-md-6 col-sm-6 dashboard-border-right mt-auto mb-auto">
									<h6 class="fs-12 mt-3 font-weight-bold">{{ __('Characters Left') }}</h6>
									<h4 class="mb-3 font-weight-800 text-primary fs-20">@if(auth()->user()->available_chars == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_chars + auth()->user()->available_chars_prepaid) }} @endif</h4>										
								</div>
							@endif
						@endrole
						@role('user|subscriber|admin')
							@if (config('settings.whisper_feature_user') == 'allow')
								<div class="col-lg col-md-6 col-sm-6 mt-auto mb-auto">
									<h6 class="fs-12 mt-3 font-weight-bold">{{ __('Minutes Left') }}</h6>
									<h4 class="mb-3 font-weight-800 text-primary fs-20">@if(auth()->user()->available_minutes == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_minutes + auth()->user()->available_minutes_prepaid) }} @endif</h4>										
								</div>
							@endif
						@endrole
					</div>

					<div class="row mb-6">
						<div class="col-md-12">
							<h6 class="fs-12 font-weight-semibold text-muted">{{ __('Your Documents') }}</h6>
							<div class="progress">
								<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $content_documents * 100  }}%; border-top-left-radius: 10px; border-bottom-left-radius: 10px" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
								<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: {{ $content_images * 100  }}%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
								<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: {{ $content_voiceovers * 100  }}%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
								<div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: {{ $content_transcripts * 100  }}%; border-top-right-radius: 10px; border-bottom-right-radius: 10px" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
							  </div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg col-md-4 col-sm-12">
							<div class="card overflow-hidden user-dashboard-special-box">
								<div class="card-body d-flex">
									<div class="usage-info w-100">
										<p class=" mb-3 fs-12 font-weight-bold">{{ __('Words Generated') }}</p>
										<h2 class="mb-2 fs-14 font-weight-semibold text-muted">{{ number_format($data['words']) }} <span class="text-muted fs-14">{{ __('words') }}</span></h2>
									</div>
									<div class="usage-icon-dashboard text-muted text-right">
										<i class="fa-solid fa-microchip-ai"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg col-md-4 col-sm-12">
							<div class="card overflow-hidden user-dashboard-special-box">
								<div class="card-body d-flex">
									<div class="usage-info w-100">
										<p class=" mb-3 fs-12 font-weight-bold">{{ __('Documents Saved') }}</p>
										<h2 class="mb-2 fs-14 font-weight-semibold text-muted">{{ number_format($data['documents']) }} <span class="text-muted fs-14">{{ __('documents') }}</span></h2>
									</div>
									<div class="usage-icon-dashboard text-primary text-right">
										<i class="fa-solid fa-folder-grid"></i>
									</div>
								</div>
							</div>
						</div>						
						@role('user|subscriber|admin')
                    		@if (config('settings.image_feature_user') == 'allow')
								<div class="col-lg col-md-4 col-sm-12">
									<div class="card overflow-hidden user-dashboard-special-box">
										<div class="card-body d-flex">
											<div class="usage-info w-100">
												<p class=" mb-3 fs-12 font-weight-bold">{{ __('Images Created') }}</p>
												<h2 class="mb-2 fs-14 font-weight-semibold text-muted">{{ number_format($data['images']) }} <span class="text-muted fs-14">{{ __('images') }}</span></h2>
											</div>
											<div class="usage-icon-dashboard text-success text-right">
												<i class="fa-solid fa-image-landscape"></i>
											</div>
										</div>
									</div>
								</div>
							@endif
						@endrole
						@role('user|subscriber|admin')
                    		@if (config('settings.voiceover_feature_user') == 'allow')
								<div class="col-lg col-md-4 col-sm-12">
									<div class="card overflow-hidden user-dashboard-special-box">
										<div class="card-body d-flex">
											<div class="usage-info w-100">
												<p class=" mb-3 fs-12 font-weight-bold">{{ __('Voiceover Tasks') }}</p>
												<h2 class="mb-2 fs-14 font-weight-semibold text-muted">{{ number_format($data['synthesized']) }} <span class="text-muted fs-14">{{ __('tasks') }}</span></h2>
											</div>
											<div class="usage-icon-dashboard text-yellow text-right">
												<i class="fa-sharp fa-solid fa-waveform-lines"></i>
											</div>
										</div>
									</div>
								</div>
							@endif
						@endrole
						@role('user|subscriber|admin')
                    		@if (config('settings.whisper_feature_user') == 'allow')
								<div class="col-lg col-md-4 col-sm-12">
									<div class="card overflow-hidden user-dashboard-special-box">
										<div class="card-body d-flex">
											<div class="usage-info w-100">
												<p class=" mb-3 fs-12 font-weight-bold">{{ __('Audio Transcribed') }}</p>
												<h2 class="mb-2 fs-14 font-weight-semibold text-muted">{{ number_format($data['transcribed']) }} <span class="text-muted fs-14">{{ __('audio files') }}</span></h2>
											</div>
											<div class="usage-icon-dashboard text-danger text-right">
												<i class="fa-sharp fa-solid fa-folder-music"></i>
											</div>
										</div>
									</div>
								</div>
							@endif
						@endrole
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg col-md-12 col-sm-12 mt-3">
			<div class="card border-0 pb-5" id="user-dashboard-panels">
				<div class="card-header pt-4 pb-4 border-0">
					<div class="mt-3">
						<h3 class="card-title mb-2"><i class="fa-solid fa-message-captions mr-2 text-muted"></i>{{ __('Favorite AI Chat Assistants') }}</h3>
						<h6 class="text-muted fs-13">{{ __('Have your favorite AI chat assistants handy anytime you need them') }}</h6>
						<div class="btn-group dashboard-menu-button">
							<button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" id="export" data-bs-display="static" aria-expanded="false"><i class="fa-solid fa-ellipsis  table-action-buttons table-action-buttons-big edit-action-button"></i></button>
							<div class="dropdown-menu" aria-labelledby="export" data-popper-placement="bottom-start">								
								<a class="dropdown-item" href="{{ route('user.chat') }}">{{ __('View All') }}</a>	
							</div>
						</div>
					</div>
				</div>
				<div class="card-body pt-2 favorite-dashboard-panel">

					@if ($chat_quantity)
						<div class="row" id="templates-panel">

							@foreach ($favorite_chats as $chat)
								<div class="col-sm-12" id="{{ $chat->chat_code }}">
									<div class="chat-boxes-dasboard text-center">
										<a id="{{ $chat->chat_code }}" @if($chat->favorite) data-tippy-content="{{ __('Remove from favorite') }}" @else data-tippy-content="{{ __('Select as favorite') }}" @endif onclick="favoriteChatStatus(this.id)"><i id="{{ $chat->chat_code }}-icon" class="@if($chat->favorite) fa-solid fa-stars @else fa-regular fa-star @endif star"></i></a>
										<div class="card @if($chat->category == 'professional') professional @elseif($chat->category == 'premium') premium @endif" id="{{ $chat->chat_code }}-card" onclick="window.location.href='{{ url('user/chats') }}/{{ $chat->chat_code }}'">
											<div class="card-body pt-2 pb-2 d-flex">
												<div class="widget-user-image overflow-hidden"><img alt="User Avatar" class="rounded-circle" src="{{ URL::asset($chat->logo) }}"></div>
												<div class="template-title mt-auto mb-auto d-flex justify-content-center">
													<h6 class="fs-13 font-weight-bold mb-0 ml-4 mt-auto mb-auto">{{ __($chat->name) }}</h6> <h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <h6 class="fs-13 text-muted mb-0 mt-auto mb-auto">{{ __($chat->sub_name) }}</h6> 
													@if($chat->category == 'professional') 
														<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-pro"><i class="fa-sharp fa-solid fa-crown mr-2"></i>{{ __('Pro') }}</p> 
													@elseif($chat->category == 'free')
														<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-free"><i class="fa-sharp fa-solid fa-gift mr-2"></i>{{ __('Free') }}</p> 
													@elseif($chat->category == 'premium')
														<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-premium"><i class="fa-sharp fa-solid fa-gem mr-2"></i>{{ __('Premium') }}</p> 
													@endif
												</div>						
											</div>
										</div>
									</div>							
								</div>
							@endforeach

							@foreach ($custom_chats as $chat)
								<div class="col-sm-12" id="{{ $chat->chat_code }}">
									<div class="chat-boxes-dasboard text-center">
										<a id="{{ $chat->chat_code }}" @if($chat->favorite) data-tippy-content="{{ __('Remove from favorite') }}" @else data-tippy-content="{{ __('Select as favorite') }}" @endif onclick="favoriteChatStatus(this.id)"><i id="{{ $chat->chat_code }}-icon" class="@if($chat->favorite) fa-solid fa-stars @else fa-regular fa-star @endif star"></i></a>
										<div class="card @if($chat->category == 'professional') professional @elseif($chat->category == 'premium') premium @endif" id="{{ $chat->chat_code }}-card" onclick="window.location.href='{{ url('user/chats/custom') }}/{{ $chat->chat_code }}'">
											<div class="card-body pt-2 pb-2 d-flex">
												<div class="widget-user-image overflow-hidden"><img alt="User Avatar" class="rounded-circle" src="{{ URL::asset($chat->logo) }}"></div>
												<div class="template-title mt-auto mb-auto d-flex justify-content-center">
													<h6 class="fs-13 font-weight-bold mb-0 ml-4 mt-auto mb-auto">{{ __($chat->name) }}</h6> <h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <h6 class="fs-13 text-muted mb-0 mt-auto mb-auto">{{ __($chat->sub_name) }}</h6> 
													@if($chat->category == 'professional') 
														<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-pro"><i class="fa-sharp fa-solid fa-crown mr-2"></i>{{ __('Pro') }}</p> 
													@elseif($chat->category == 'free')
														<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-free"><i class="fa-sharp fa-solid fa-gift mr-2"></i>{{ __('Free') }}</p> 
													@elseif($chat->category == 'premium')
														<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-premium"><i class="fa-sharp fa-solid fa-gem mr-2"></i>{{ __('Premium') }}</p> 
													@endif
												</div>						
											</div>
										</div>
									</div>							
								</div>
							@endforeach

						</div>
					@else
						<div class="row text-center mt-8">
							<div class="col-sm-12">
								<h6 class="text-muted">{{ __('To add AI chat assitant as your favorite ones, simply click on the start icon on desired') }} <a href="{{ route('user.chat') }}" class="text-primary internal-special-links font-weight-bold">{{ __('AI Chat Assistants') }}</a></h6>
							</div>
						</div>
					@endif
					
				</div>
			</div>
		</div>
		
		<div class="col-lg col-md-12 col-sm-12 mt-3">
			<div class="card border-0 pb-5" id="user-dashboard-panels">
				<div class="card-header pt-4 pb-4 border-0">
					<div class="mt-3">
						<h3 class="card-title mb-2"><i class="fa-solid fa-microchip-ai mr-2 text-muted"></i>{{ __('Favorite AI Writer Templates') }}</h3>
						<h6 class="text-muted fs-13">{{ __('Always have your top favorite templates handy whenever you need them') }}</h6>
						<div class="btn-group dashboard-menu-button">
							<button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" id="export" data-bs-display="static" aria-expanded="false"><i class="fa-solid fa-ellipsis  table-action-buttons table-action-buttons-big edit-action-button"></i></button>
							<div class="dropdown-menu" aria-labelledby="export" data-popper-placement="bottom-start">								
								<a class="dropdown-item" href="{{ route('user.templates') }}">{{ __('View All') }}</a>	
							</div>
						</div>
					</div>
				</div>
				<div class="card-body pt-2 favorite-dashboard-panel">

					@if ($template_quantity)
						<div class="row" id="templates-panel">

							@foreach ($templates as $template)
								<div class="col-sm-12" id="{{ $template->template_code }}">
									<div class="template-dashboard">
										<a id="{{ $template->template_code }}" @if($template->favorite) data-tippy-content="{{ __('Remove from favorite') }}" @else data-tippy-content="{{ __('Select as favorite') }}" @endif onclick="favoriteStatus(this.id)"><i class="@if($template->favorite) fa-solid fa-stars @else fa-regular fa-star @endif star"></i></a>
										<div class="card @if($template->package == 'professional') professional @elseif($template->package == 'premium') premium @endif" onclick="window.location.href='{{ url('user/templates/original-template') }}/{{ $template->slug }}'">
											<div class="card-body d-flex">
												<div class="template-icon">
													{!! $template->icon !!}													
												</div>
												<div class="template-title ml-4">
													<div class="d-flex">
														<h6 class="fs-13 number-font mt-auto mb-auto">{{ __($template->name) }}</h6>
														@if($template->package == 'professional') 
															<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-pro mb-0 mt-0"><i class="fa-sharp fa-solid fa-crown mr-2"></i>{{ __('Pro') }}</p> 
														@elseif($template->package == 'free')
															<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-free mb-0 mt-0"><i class="fa-sharp fa-solid fa-gift mr-2"></i>{{ __('Free') }}</p> 
														@elseif($template->package == 'premium')
															<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-premium mb-0 mt-0"><i class="fa-sharp fa-solid fa-gem mr-2"></i>{{ __('Premium') }}</p> 
														@endif
													</div>
													<p class="fs-12 mb-0 text-muted">{{ __($template->description) }}</p>
												</div>
											</div>
										</div>
									</div>							
								</div>
							@endforeach

							@foreach ($custom_templates as $template)
								<div class="col-sm-12" id="{{ $template->template_code }}">
									<div class="template-dashboard">
										<a id="{{ $template->template_code }}" @if($template->favorite) data-tippy-content="{{ __('Remove from favorite') }}" @else data-tippy-content="{{ __('Select as favorite') }}" @endif onclick="favoriteStatusCustom(this.id)"><i class="@if($template->favorite) fa-solid fa-stars @else fa-regular fa-star @endif star"></i></a>
										<div class="card @if($template->package == 'professional') professional @elseif($template->package == 'premium') premium @endif" onclick="window.location.href='{{ url('user/templates') }}/{{ $template->slug }}/{{ $template->template_code }}'">
											<div class="card-body d-flex">
												<div class="template-icon">
													{!! $template->icon !!}													
												</div>
												<div class="template-title ml-4">
													<div class="d-flex">
														<h6 class="fs-13 number-font mt-auto mb-auto">{{ __($template->name) }}</h6>
														@if($template->package == 'professional') 
															<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-pro mb-0 mt-0"><i class="fa-sharp fa-solid fa-crown mr-2"></i>{{ __('Pro') }}</p> 
														@elseif($template->package == 'free')
															<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-free mb-0 mt-0"><i class="fa-sharp fa-solid fa-gift mr-2"></i>{{ __('Free') }}</p> 
														@elseif($template->package == 'premium')
															<h6 class="mr-2 ml-2 text-muted mt-auto mb-auto">|</h6> <p class="fs-8 btn package-badge btn-premium mb-0 mt-0"><i class="fa-sharp fa-solid fa-gem mr-2"></i>{{ __('Premium') }}</p> 
														@endif
													</div>
													<p class="fs-12 mb-0 text-muted">{{ __($template->description) }}</p>
												</div>
											</div>
										</div>
									</div>							
								</div>
							@endforeach

						</div>
					@else
						<div class="row text-center mt-8">
							<div class="col-sm-12">
								<h6 class="text-muted">{{ __('To add templates as your favorite ones, simply click on the start icon on desired') }} <a href="{{ route('user.templates') }}" class="text-primary internal-special-links font-weight-bold">{{ __('templates') }}</a></h6>
							</div>
						</div>
					@endif
					
				</div>
			</div>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 mt-3">
			<div class="card border-0">
				<div class="card-header pt-4 pb-0 border-0">
					<div class="mt-3">
						<h3 class="card-title mb-2"><i class="fa-solid fa-folder-bookmark mr-2 text-muted"></i>{{ __('Recent Documents') }}</h3>
						<div class="btn-group dashboard-menu-button">
							<button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" id="export" data-bs-display="static" aria-expanded="false"><i class="fa-solid fa-ellipsis  table-action-buttons table-action-buttons-big edit-action-button"></i></button>
							<div class="dropdown-menu" aria-labelledby="export" data-popper-placement="bottom-start">								
								<a class="dropdown-item" href="{{ route('user.documents') }}">{{ __('View All') }}</a>	
							</div>
						</div>
					</div>
				</div>
				<div class="card-body pt-2 responsive-dashboard-table">
					<table class="table table-hover" id="database-backup">
						<thead>
							<tr role="row">
								<th class="fs-12 font-weight-700 border-top-0">{{ __('Document Name') }}</th>
								<th class="fs-12 font-weight-700 border-top-0 text-right">{{ __('Words') }}</th>
								<th class="fs-12 font-weight-700 border-top-0 text-right">{{ __('Workbook') }}</th>
								<th class="fs-12 font-weight-700 border-top-0 text-right">{{ __('Category') }}</th>
								<th class="fs-12 font-weight-700 border-top-0 text-right">{{ __('Last Activity') }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($documents as $data)
							<tr class="relative">
								<td><div class="d-flex">
										<div class="mr-2 rtl-small-icon">{!! $data->icon !!}</div>
										<div><a class="font-weight-bold document-title" href="{{ route("user.documents.show", $data->id ) }}">{{ ucfirst($data->title) }}</a><br><span class="text-muted">{{ ucfirst($data->template_name) }}</span><div>
									</div>
								</td>
								<td class="text-right text-muted">{{ $data->words }}</td>
								<td class="text-right text-muted">{{ ucfirst($data->workbook) }}</td>
								<td class="text-right"><span class="cell-box category-{{ $data->group }}">{{ __(ucfirst($data->group)) }}</span></td>
								<td class="text-right text-muted">{{ \Carbon\Carbon::parse($data->updated_at)->diffForHumans() }}</td>
								<td class="w-0 p-0" colspan="0">
									<a class="strage-things" style="position: absolute; inset: 0px; width: 100%" href="{{ route("user.documents.show", $data->id ) }}"><span class="sr-only">{{ __('View') }}</span></a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>					
				</div>
			</div>
		</div>

		<div class="col-lg-12 col-sm-12 mt-3">
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-12 mt-5">                        
					<div class="title text-center dashboard-title">
						<h3 class="fs-24">{{ __('Need Help?') }}</h3>     
						<h6 class="text-muted fs-14 mb-4">{{ __('Got questions? We have you covered') }}</h6>                    
						<a href="{{ route('user.support') }}" class="btn btn-primary pl-6 pr-6 mb-2" style="text-transform: none">{{ __('Create Suppport Ticket') }}</a>
						<h6 class="text-muted fs-10 mb-4">{{ __('Available from') }} <span class="font-weight-bold">{{ __('9am till 5pm') }}</span></h6> 
					</div>                                               
				</div>

				<div class="col-lg-5 col-md-5 col-sm-12 mb-5">
					<div class="card border-0 pb-4">
						<div class="card-header pt-4 pb-0 border-0">
							<div class="mt-3">
								<h3 class="card-title mb-2"><i class="fa-solid fa-headset mr-2 text-muted"></i>{{ __('Support Tickets') }}</h3>
								<div class="btn-group dashboard-menu-button">
									<button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" id="export" data-bs-display="static" aria-expanded="false"><i class="fa-solid fa-ellipsis  table-action-buttons table-action-buttons-big edit-action-button"></i></button>
									<div class="dropdown-menu" aria-labelledby="export" data-popper-placement="bottom-start">								
										<a class="dropdown-item" href="{{ route('user.support') }}">{{ __('View All') }}</a>	
									</div>
								</div>
							</div>
						</div>
						<div class="card-body pt-2 dashboard-panel-500">
							<table class="table table-hover" id="database-backup">
								<thead>
									<tr role="row">
										<th class="fs-12 font-weight-700 border-top-0">{{ __('Ticket ID') }}</th>
										<th class="fs-12 font-weight-700 border-top-0 text-left">{{ __('Subject') }}</th>
										<th class="fs-12 font-weight-700 border-top-0 text-center">{{ __('Category') }}</th>
										<th class="fs-12 font-weight-700 border-top-0 text-center">{{ __('Status') }}</th>
										<th class="fs-12 font-weight-700 border-top-0 text-right">{{ __('Last Updated') }}</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($tickets as $data)
									<tr class="relative" style="height: 60px">
										<td><a class="font-weight-bold text-primary" href="{{ route("user.support.show", $data->ticket_id ) }}">{{ $data->ticket_id }}</a>
										</td>
										<td class="text-left text-muted">{{ ucfirst($data->subject) }}</td>
										<td class="text-center text-muted">{{ ucfirst($data->category) }}</td>
										<td class="text-center"><span class="cell-box support-{{ strtolower($data->status) }}">{{ __(ucfirst($data->status)) }}</span></td>
										<td class="text-right text-muted">{{ \Carbon\Carbon::parse($data->updated_at)->diffForHumans() }}</td>
										<td class="w-0 p-0" colspan="0">
											<a class="strage-things" style="position: absolute; inset: 0px; width: 100%" href="{{ route("user.support.show", $data->ticket_id ) }}"><span class="sr-only">{{ __('View') }}</span></a>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>					
						</div>
					</div>                      
				</div>     
				
				<div class="col-lg-5 col-md-5 col-sm-12 mb-5">
					<div class="card border-0 pb-4">
						<div class="card-header pt-4 pb-0 border-0">
							<div class="mt-3">
								<h3 class="card-title mb-2"><i class="fa-solid fa-solid fa-message-exclamation mr-2 text-muted"></i>{{ __('News & Notifications') }}</h3>
								<div class="btn-group dashboard-menu-button">
									<button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" id="export" data-bs-display="static" aria-expanded="false"><i class="fa-solid fa-ellipsis  table-action-buttons table-action-buttons-big edit-action-button"></i></button>
									<div class="dropdown-menu" aria-labelledby="export" data-popper-placement="bottom-start">								
										<a class="dropdown-item" href="{{ route('user.notifications') }}">{{ __('View All') }}</a>	
									</div>
								</div>
							</div>
						</div>
						<div class="card-body pt-2 dashboard-timeline dashboard-panel-500">					
							<div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
								@foreach ($notifications as $notification)
									<div class="vertical-timeline-item vertical-timeline-element">
										<div>
											<span class="vertical-timeline-element-icon">
												@if ($notification->data['type'] == 'Warning')
													<i class="badge badge-dot badge-dot-xl badge-secondary"> </i>
												@elseif ($notification->data['type'] == 'Info')
													<i class="badge badge-dot badge-dot-xl badge-primary"> </i>
												@elseif ($notification->data['type'] == 'Announcement')
													<i class="badge badge-dot badge-dot-xl badge-success"> </i>
												@else
													<i class="badge badge-dot badge-dot-xl badge-warning"> </i>
												@endif
												
											</span>
											<div class="vertical-timeline-element-content">
												<h4 class="fs-13"><a href="{{ route("user.notifications.show", $notification->id)  }}"><b>{{ __($notification->data['type']) }}:</b></a> {{ __($notification->data['subject']) }}</h4>
												<p>@if ($notification->data['action'] == 'Action Required') <span class="text-danger">{{ __('Action Required') }}</span> @else <span class="text-muted fs-12">{{ __('No Action Required') }}</span> @endif</p>
												<span class="vertical-timeline-element-date text-center">{{ \Carbon\Carbon::parse($notification->created_at)->format('M d, Y') }} <br> {{ \Carbon\Carbon::parse($notification->created_at)->format('H:i A') }}</span>
											</div>
										</div>
									</div>
								@endforeach
							</div>											  					
						</div>
					</div>                      
				</div>  
			</div>
		</div>

	</div>
	<!-- END USER PROFILE PAGE -->
@endsection

@section('js')
	<!-- Chart JS -->
	{{-- <script src="{{URL::asset('plugins/chart/chart.min.js')}}"></script> --}}
	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script>
		$(function() {
	
			'use strict';

			// Total New User Analysis Chart
			var userMonthlyData = JSON.parse(`<?php echo $chart_data['user_monthly_usage']; ?>`);
			var userMonthlyDataset = Object.values(userMonthlyData);

			let chartColor = "#FFFFFF";
			let gradientChartOptionsConfiguration = {
				maintainAspectRatio: false,
				plugins: {
					legend: {
						display: false,
					},
					tooltip: {
						titleAlign: 'center',
						bodySpacing: 4,
						mode: "nearest",
						intersect: 0,
						position: "nearest",
						xPadding: 20,
						yPadding: 20,
						caretPadding: 20
					},
				},			
				responsive: true,
				scales: {
					y: {
						display: 0,
						grid: 0,
						ticks: {
							display: false,
							padding: 0,
							beginAtZero: true,
						},
						grid: {
							zeroLineColor: "transparent",
							drawTicks: false,
							display: false,
							drawBorder: false,
						}
					},
					x: {
						display: 0,
						grid: 0,
						ticks: {
							display: false,
							padding: 0,
							beginAtZero: true,
						},
						grid: {
							zeroLineColor: "transparent",
							drawTicks: false,
							display: false,
							drawBorder: false,
						}
					}
				},
				layout: {
					padding: {
						left: 0,
						right: -10,
						top: 0,
						bottom: -10
					}
				},
				elements: {
					line: {
						tension : 0.4
					},
				},
			};

			let ctx2 = document.getElementById('hoursSavedChart').getContext("2d");
			let gradientStroke = ctx2.createLinearGradient(500, 0, 100, 0);
			gradientStroke.addColorStop(0, '#18ce0f');
			gradientStroke.addColorStop(1, chartColor);
			let gradientFill = ctx2.createLinearGradient(0, 170, 0, 50);
			gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
			gradientFill.addColorStop(1, "rgba(24,206,15, 0.4)");
			let myChart = new Chart(ctx2, {
				type: 'line',
				data: {
					labels: ['{{ __('Jan') }}', '{{ __('Feb') }}', '{{ __('Mar') }}', '{{ __('Apr') }}', '{{ __('May') }}', '{{ __('Jun') }}', '{{ __('Jul') }}', '{{ __('Aug') }}', '{{ __('Sep') }}', '{{ __('Oct') }}', '{{ __('Nov') }}', '{{ __('Dec') }}'],
					datasets: [{
						label: "{{ __('Words Generated') }}",
						borderColor: "#18ce0f",
						pointBorderColor: "#FFF",
						pointBackgroundColor: "#18ce0f",
						pointBorderWidth: 1,
						pointHoverRadius: 4,
						pointHoverBorderWidth: 1,
						pointRadius: 3,
						fill: true,
						backgroundColor: gradientFill,
						borderWidth: 2,
						data: userMonthlyDataset
					}]
				},
				options: gradientChartOptionsConfiguration
			});

		});

		function favoriteStatus(id) {

			let formData = new FormData();
			formData.append("id", id);

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'post',
				url: 'dashboard/favorite',
				data: formData,
				processData: false,
				contentType: false,
				success: function (data) {

					if (data['status'] == 'success') {
						if (data['set']) {
							Swal.fire('{{ __('Template Removed from Favorites') }}', '{{ __('Selected template has been successfully removed from favorites') }}', 'success');
							document.getElementById(id).style.display = 'none';	
						} else {
							Swal.fire('{{ __('Template Added to Favorites') }}', '{{ __('Selected template has been successfully added to favorites') }}', 'success');
						}
														
					} else {
						Swal.fire('{{ __('Favorite Setting Issue') }}', '{{ __('There as an issue with setting favorite status for this template') }}', 'warning');
					}      
				},
				error: function(data) {
					Swal.fire('Oops...','Something went wrong!', 'error')
				}
			})

			return false;
		}

		function favoriteStatusCustom(id) {

			let formData = new FormData();
			formData.append("id", id);

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'post',
				url: 'dashboard/favoritecustom',
				data: formData,
				processData: false,
				contentType: false,
				success: function (data) {

					if (data['status'] == 'success') {
						if (data['set']) {
							Swal.fire('{{ __('Template Removed from Favorites') }}', '{{ __('Selected template has been successfully removed from favorites') }}', 'success');
							document.getElementById(id).style.display = 'none';	
						} else {
							Swal.fire('{{ __('Template Added to Favorites') }}', '{{ __('Selected template has been successfully added to favorites') }}', 'success');
						}
														
					} else {
						Swal.fire('{{ __('Favorite Setting Issue') }}', '{{ __('There as an issue with setting favorite status for this template') }}', 'warning');
					}      
				},
				error: function(data) {
					Swal.fire('Oops...','Something went wrong!', 'error')
				}
			})

			return false;
		}

		function favoriteChatStatus(id) {

			let icon, card;
			let formData = new FormData();
			formData.append("id", id);

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'post',
				url: 'chat/favorite',
				data: formData,
				processData: false,
				contentType: false,
				success: function (data) {

					if (data['status'] == 'success') {
						if (data['set']) {
							Swal.fire('{{ __('Chat Bot Removed from Favorites') }}', '{{ __('Selected chat bot has been successfully removed from favorites') }}', 'success');
							document.getElementById(id).style.display = 'none';
							icon = document.getElementById(id + '-icon');
							icon.classList.remove("fa-solid");
							icon.classList.remove("fa-stars");
							icon.classList.add("fa-regular");
							icon.classList.add("fa-star");

							card = document.getElementById(id + '-card');
							if(card.classList.contains("professional")) {
								// do nothing
							} else {
								card.classList.remove("favorite");
								card.classList.add('border-0');
							}							
						} else {
							Swal.fire('{{ __('Chat Bot Added to Favorites') }}', '{{ __('Selected chat bot has been successfully added to favorites') }}', 'success');
							icon = document.getElementById(id + '-icon');
							icon.classList.remove("fa-regular");
							icon.classList.remove("fa-star");
							icon.classList.add("fa-solid");
							icon.classList.add("fa-stars");

							card = document.getElementById(id + '-card');
							if(card.classList.contains("professional")) {
								// do nothing
							} else {
								card.classList.add('favorite');
								card.classList.remove('border-0');
							}
						}
														
					} else {
						Swal.fire('{{ __('Favorite Setting Issue') }}', '{{ __('There as an issue with setting favorite status for this chat bot') }}', 'warning');
					}      
				},
				error: function(data) {
					Swal.fire('Oops...','Something went wrong!', 'error')
				}
			})
		}

	</script>
@endsection
