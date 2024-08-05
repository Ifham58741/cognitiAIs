@extends('layouts.app')
@section('css')
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('plugins/highlight/highlight.dark.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">{{ __($chat->name) }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-messages-question mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{route('user.chat')}}"> {{ __('AI Chat Assistants') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __($chat->name) }}</a></li>
			</ol>
		</div>
		<div class="page-rightheader">
			<div id="balance-status">
				<x-balance-chat />
			</div>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')
	<form id="openai-form" action="" method="GET" enctype="multipart/form-data">		
		@csrf
		<div class="row justify-content-md-center">
			
			<div class="chat-main-container">
				<div class="chat-sidebar-container">
					<div class="chat-sidebar-search">	
						<div class="input-box relative">				
							<input id="chat-search" class="form-control" type="text" placeholder="{{ __('Search') }}">	
							<i class="fa-solid fa-magnifying-glass fs-14 text-muted chat-search-icon"></i>	
						</div>			
					</div>		

					<div class="row justify-content-center">
						<div class="col-sm-12 text-center pb-3">									
							<a class="btn btn-primary ripple pt-1 pb-1 pl-4 pr-4 mt-3" id="new-chat-button"><i class="fa-solid fa-plus fs-8 mr-2"></i> {{ __('New Conversation') }}</a>
						</div>
					</div>

					<div class="chat-sidebar-messages pt-0 mb-4">						
						@foreach ($messages as $key => $message)
							<div class="chat-sidebar-message @if ($loop->first) selected-message @endif" id="{{ $message->conversation_id }}">
								<h6 class="chat-title" id="title-{{ $message->conversation_id }}">
									{{ __($message->title) }}
								</h6>
								<div class="chat-info">
									<div class="chat-count"><span>{{ $message->messages }}</span> {{ __('messages') }}</div>
									<div class="chat-date">{{ \Carbon\Carbon::parse($message->updated_at)->diffForhumans() }}</div>
								</div>
								<div class="chat-actions d-flex">
									<a href="#" class="chat-edit fs-12" id="{{ $message->conversation_id }}"><i class="fa-sharp fa-solid fa-pen-to-square" data-tippy-content="{{ __('Edit Name') }}"></i></a>
									<a href="#" class="chat-delete fs-12 ml-2" id="{{ $message->conversation_id }}"><i class="fa-sharp fa-solid fa-trash" data-tippy-content="{{ __('Delete Chat') }}"></i></a>
								</div>
							</div>						
						@endforeach						
					</div>
				</div>

				<div class="chat-message-container" id="chat-system">
					<div class="card-header">
						<div class="w-100 pt-2 pb-2">
							<div class="d-flex">
								<div class="overflow-hidden mr-4"><img alt="Avatar" class="chat-avatar" src="{{ URL::asset($chat->logo) }}"></div>
								<div class="widget-user-name"><span class="font-weight-bold">{{ __($chat->name) }}</span><br><span class="text-muted">{{ __($chat->sub_name) }}</span></div>
							</div>
						</div>
						@if ($internet)
							<div class="form-group text-right w-30" id="chat-internet-button">
								<label class="custom-switch mb-0">
									<input type="checkbox" name="google-search" class="custom-switch-input" id="google-search">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">{{ __('Use Internet Access') }}</span>
								</label>
							</div>
						@endif
						<div class="text-right">											
							<a id="expand" class="template-button" href="#"><i class="fa-solid fa-bars table-action-buttons table-action-buttons-big edit-action-button" data-tippy-content="{{ __('Show Chat Conversations') }}"></i></a>
							<div class="btn-group" id="chat-export-button">
								<button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" id="export" data-bs-display="static" aria-expanded="false" data-tippy-content="{{ __('Export Chat Conversation') }}"><i class="fa-solid fa-bars table-action-buttons table-action-buttons-big edit-action-button"></i></button>
								<div class="dropdown-menu" aria-labelledby="export" data-popper-placement="bottom-start">						
									<a class="dropdown-item" id="export-txt" onclick="exportTXT();"><i class="fa-solid fa-text-size fs-13 text-muted mr-2"></i>{{ __('Text File') }}</a>								
									<a class="dropdown-item" id="export-word" onclick="exportWord();"><i class="fa-sharp fa-solid fa-file-word fs-13 text-muted mr-2"></i>{{ __('MS Word') }}</a>
									<a class="dropdown-item" id="export-pdf" onclick="exportPDF();"><i class="fa-sharp fa-solid fa-file-pdf fs-13 text-muted mr-2"></i>{{ __('PDF File') }}</a>
								</div>
							</div>							
						</div>
					</div>
					<div class="card-body pl-0 pr-0">
						<div class="row">						
							<div class="col-md-12 col-sm-12" >									
								<div id="chat-container">
									<div class="msg left-msg">
										<div class="message-img" style="background-image: url({{ $chat->logo }})"></div>
										<div class="message-bubble">					
											<div class="msg-text">{{ __($chat->description) }}</div>
										</div>
									</div>

									<div id="dynamic-inputs"></div>
									<div id="generating-status" class="text-center">
										<img src='{{ URL::asset("/img/svgs/code.svg") }}'>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="row">						
							<div class="col-sm-12">	
								
								<div class="input-box mb-0">								
									<div class="chat-controllers">										
										<textarea type="message" class="form-control @error('message') is-danger @enderror" rows="1" id="message" name="message" placeholder="{{ __('Type your message here...') }}"></textarea>
										<div class="chat-button-box"><a class="btn chat-button-icon" href="javascript:void(0)" id="mic-button"><i class="fa-solid fa-microphone"></i></a></div>
										<div class="chat-button-box no-margin-right"><a class="btn chat-button-icon" href="javascript:void(0)" id="stop-button"><i class="fa-solid fa-circle-stop"></i></a></div>
										<div><button class="btn ripple chat-button" id="chat-button">{{ __('Send') }} <i class="fa-solid fa-paper-plane-top ml-1"></i></button></div>										
									</div> 
									<div class="flex mt-3">
										<a class="btn btn-primary-chat fs-11 text-muted mb-2" href="javascript:void(0)" id="ai-model" data-bs-toggle="modal" data-bs-target="#aiModel">
											<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>
											<span>{{ __('GPT-3.5 Turbo') }}</span>
										</a>
										@if ($brands_feature)
											<a class="btn btn-primary-chat fs-11 text-muted mb-2" href="javascript:void(0)" id="brand-voice" data-bs-toggle="modal" data-bs-target="#brandVoice"><i class="fa-solid fa-signature mr-1"></i> <span>{{ __('Brand Voice') }}</span></a>
										@endif	
										<a class="btn btn-primary-chat fs-11 text-muted mb-2" href="javascript:void(0)" id="prompt-button-main" data-bs-toggle="modal" data-bs-target="#promptModal"><i class="fa-solid fa-notebook"></i> <span>{{ __('Prompt Library') }}</span></a>
										@if (config('settings.vision_for_chat_feature_user') == 'allow')
											<input type="file" id="image-input" style="display: none;" accept="image/png, image/jpeg, image/webp">
											<a class="btn btn-primary-chat fs-11 text-muted mb-2" href="javascript:void(0)" id="upload-button-main"><i class="fa-solid fa-image"></i> <span>{{ __('Upload Image') }}</span></a>
										@endif
									</div>
									@error('message')
										<p class="text-danger">{{ $errors->first('message') }}</p>
									@enderror
								</div> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="modal fade" id="promptModal" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		  	<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body pl-5 pr-5">
					<h6 class="text-center font-weight-extra-bold fs-16"><i class="fa-solid fa-notebook text-primary mr-2"></i> {{ __('Prompt Library') }}</h6>

					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 p-4">
							<div id="chat-search-panel">
								<div class="search-template">
									<div class="input-box">								
										<div class="form-group prompt-search-bar-dark">							    
											<input type="text" class="form-control" id="search-template" placeholder="{{ __('Search for prompts...') }}">
										</div> 
									</div> 
								</div>
							</div>
						</div>	
					</div>				
					
					<div class="prompts-panel">
			
						<div class="tab-content" id="myTabContent">
			
							<div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
								<div class="row" id="templates-panel">			
									@foreach ($prompts as $prompt)
										<div class="col-md-6 col-sm-12" id="{{ $prompt->group }}">
											<div class="prompt-boxes">
												<div class="card border-0" onclick='applyPrompt("{{ __($prompt->prompt) }}")'>
													<div class="card-body pt-3">
														<div class="template-title">
															<h6 class="mb-2 fs-15 number-font">{{ __($prompt->title) }}</h6>
														</div>
														<div class="template-info">
															<p class="fs-13 text-muted mb-2">{{ __($prompt->prompt) }}</p>
														</div>							
													</div>
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
	</div>

	<div class="modal fade" id="brandVoice" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
		  	<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body pl-5 pr-5">
					<h6 class="text-center font-weight-extra-bold fs-16 mb-4"><i class="fa-solid fa-signature text-primary mr-2"></i> {{ __('Brand Voice') }}</h6>			
					
					<div class="prompts-panel">
			
						<div class="tab-content" id="myTabContent">
			
							<div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
								<div class="row" id="templates-panel">			
									<div class="col-sm-12">
										<div class="form-group mb-5">	
											<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Select Company') }}</h6>								
											<select id="company" name="company" class="form-select"  onchange="updateService(this)">		
												<option value="none"> {{ __('Select your Company / Brand') }}</option>
												@foreach ($brands as $brand)
													<option value="{{ $brand->id }}"> {{ __($brand->name) }}</option>
												@endforeach									
											</select>
										</div>
									</div>
		
									<div class="col-sm-12">
										<div class="form-group mb-5">
											<h6 class="fs-11 mb-2 font-weight-semibold">{{ __('Select Product / Service') }} </h6>
											<select id="service" name="service" class="form-select">
												<option value="none">{{ __('Select your Product / Service') }}</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="text-center">	
								<button type="button" class="btn-primary ripple btn pl-7 pr-7" data-bs-dismiss="modal">{{ __('Apply') }}</button>
							</div>							
						</div>
					</div>
					
				</div>
		  	</div>
		</div>
	</div>

	<div class="modal fade" id="aiModel" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		  	<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body pl-5 pr-5">
					<h6 class="text-center font-weight-extra-bold fs-16 mb-4"><i class="fa-solid fa-microchip-ai text-primary mr-2"></i> {{ __('AI Models') }}</h6>			
					
					<div class="prompts-panel">			
						<div class="tab-content" id="myTabContent">			
							<div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
								<div class="flex" id="templates-panel">			
									<div class="row chat-model-box pl-5 pr-5 pt-2 pb-2">

										<x-original-chat-models />

									</div>	
								</div>
							</div>
							<div class="text-center mt-3">	
								<button type="button" class="btn-primary ripple btn pl-7 pr-7" data-bs-dismiss="modal">{{ __('Apply') }}</button>
							</div>							
						</div>
					</div>
					
				</div>
		  	</div>
		</div>
	</div>

@endsection

@section('js')
<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
<script src="{{URL::asset('plugins/pdf/html2canvas.min.js')}}"></script>
<script src="{{URL::asset('plugins/pdf/jspdf.umd.min.js')}}"></script>
<script src="{{URL::asset('plugins/highlight/highlight.min.js')}}"></script>
<script src="{{URL::asset('plugins/highlight/showdown.min.js')}}"></script>
<script src="{{URL::asset('js/export-chat.js')}}"></script>
<script type="text/javascript">
	const main_form = get("#openai-form");
	const input_text = get("#message");
	const msgerChat = get("#chat-container");
	const dynamicList = get("#dynamic-inputs");
	const msgerSendBtn = get("#chat-button");
	const bot_avatar = "{{ $chat->logo }}";
	const user_avatar = "{{ URL::asset(auth()->user()->profile_photo_path) }}";	
	const mic = document.querySelector('#mic-button');
	let eventSource = null;
	let isTranscribing = false;
	let chat_code = "{{ $chat->chat_code }}";	
	let active_id;
	let default_message;
	let uploaded_image = '';
	let chat_type = "{{ $chat->type }}";
	let chat_model = "{{ $default_model }}";
	// Process deault conversation
	$(document).ready(function() {
		$(".chat-sidebar-message").first().focus().trigger('click');

		let check_messages = document.querySelectorAll('.chat-sidebar-message').length;
		if (check_messages == 0) {
			let id = makeid(10);

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'POST',
				url: '/user/chat/conversation',
				data: { 'conversation_id': id, 'chat_code': chat_code},
				success: function (data) {

					if (data == 'success') {
						$('#dynamic-inputs').html('');

						$('.chat-sidebar-messages').prepend(`<div class="chat-sidebar-message selected-message" id=${id}>
								<div class="chat-title" id="title-${id}">
									{{ __('New Chat') }}
								</div>
								<div class="chat-info">
									<div class="chat-count"><span>0</span> {{ __('messages') }}</div>
									<div class="chat-date">{{ __('Now') }}</div>
								</div>
								<div class="chat-actions d-flex">
									<a href="#" class="chat-edit fs-12" id="${id}"><i class="fa-sharp fa-solid fa-pen-to-square" data-tippy-content="{{ __('Edit Name') }}"></i></a>
									<a href="#" class="chat-delete fs-12 ml-2"  id="${id}"><i class="fa-sharp fa-solid fa-trash" data-tippy-content="{{ __('Delete Chat') }}"></i></a>
								</div>
							</div>`);
						active_id = id;	
					} else {
						toastr.warning('{{ __('There was an issue while deleting chat conversation') }}');
					}		
								
				},
				error: function(data) {
					toastr.warning('{{ __('There was an issue while deleting chat conversation') }}');
				}
			});
		}

		let model = '';
		let logo = '';

		switch (chat_model) {
			case 'gpt-3.5-turbo-0125':
				model = 'GPT 3.5 Turbo';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'gpt-4':
				model = 'GPT 4';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'gpt-4o':
				model = 'GPT 4o';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'gpt-4o-mini':
				model = 'GPT 4o mini';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'gpt-4-0125-preview':
				model = 'GPT 4 Turbo';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'gpt-4-turbo-2024-04-09':
				model = 'GPT 4 Turbo Vision';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'claude-3-opus-20240229':
				model = 'Claude 3 Opus';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 inline-block h-4 w-4 align-text-top" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="M3.512 6.065c-.777 1.87-1.772 4.262-2.212 5.317l-.8 1.92 1.229.017c.676.01 1.24.006 1.255-.008.015-.014.221-.505.459-1.09l.431-1.066h4.718l.117.293.44 1.089.321.797h1.258c.98 0 1.25-.018 1.224-.082a3382.243 3382.243 0 0 1-3.78-9.073l-.625-1.512H4.924L3.512 6.065ZM9.68 2.788c.018.066 1.007 2.466 2.198 5.332l2.165 5.213h1.244c.684 0 1.23-.021 1.213-.048-.047-.074-4.3-10.303-4.354-10.472-.047-.144-.061-.146-1.274-.146-1.16 0-1.224.006-1.192.12ZM6.576 6.195c.178.439.515 1.265.75 1.838l.425 1.04-.739.019c-.406.01-1.089.01-1.517 0l-.78-.019.358-.878.748-1.837c.215-.528.4-.96.41-.96.012 0 .167.36.345.797Z" clip-rule="evenodd"></path></svg>';
				break;
			case 'claude-3-sonnet-20240229':
				model = 'Claude 3 Sonnet';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 inline-block h-4 w-4 align-text-top" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="M3.512 6.065c-.777 1.87-1.772 4.262-2.212 5.317l-.8 1.92 1.229.017c.676.01 1.24.006 1.255-.008.015-.014.221-.505.459-1.09l.431-1.066h4.718l.117.293.44 1.089.321.797h1.258c.98 0 1.25-.018 1.224-.082a3382.243 3382.243 0 0 1-3.78-9.073l-.625-1.512H4.924L3.512 6.065ZM9.68 2.788c.018.066 1.007 2.466 2.198 5.332l2.165 5.213h1.244c.684 0 1.23-.021 1.213-.048-.047-.074-4.3-10.303-4.354-10.472-.047-.144-.061-.146-1.274-.146-1.16 0-1.224.006-1.192.12ZM6.576 6.195c.178.439.515 1.265.75 1.838l.425 1.04-.739.019c-.406.01-1.089.01-1.517 0l-.78-.019.358-.878.748-1.837c.215-.528.4-.96.41-.96.012 0 .167.36.345.797Z" clip-rule="evenodd"></path></svg>';
				break;
			case 'claude-3-haiku-20240307':
				model = 'Claude 3 Haiku';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 inline-block h-4 w-4 align-text-top" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="M3.512 6.065c-.777 1.87-1.772 4.262-2.212 5.317l-.8 1.92 1.229.017c.676.01 1.24.006 1.255-.008.015-.014.221-.505.459-1.09l.431-1.066h4.718l.117.293.44 1.089.321.797h1.258c.98 0 1.25-.018 1.224-.082a3382.243 3382.243 0 0 1-3.78-9.073l-.625-1.512H4.924L3.512 6.065ZM9.68 2.788c.018.066 1.007 2.466 2.198 5.332l2.165 5.213h1.244c.684 0 1.23-.021 1.213-.048-.047-.074-4.3-10.303-4.354-10.472-.047-.144-.061-.146-1.274-.146-1.16 0-1.224.006-1.192.12ZM6.576 6.195c.178.439.515 1.265.75 1.838l.425 1.04-.739.019c-.406.01-1.089.01-1.517 0l-.78-.019.358-.878.748-1.837c.215-.528.4-.96.41-.96.012 0 .167.36.345.797Z" clip-rule="evenodd"></path></svg>';
				break;
			case 'gemini_pro':
				model = 'Gemini Pro';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 48 48" class="mr-1 inline-block h-4 w-4 align-text-top" aria-hidden="true"><path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"></path><path fill="#FF3D00" d="m6.306 14.691 6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"></path><path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"></path><path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"></path></svg>';
				break;
			default:
				model = 'GPT 3.5 Turbo';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
		}

		let ai = document.getElementById('ai-model');
		ai.innerHTML = logo + model;
	
		
	});
	

	// Create new chat conversation
	$("#new-chat-button").on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
		let id = makeid(10);
		var element = document.getElementById(active_id);
		if (element) {
			element.classList.remove("selected-message");
		}

		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			method: 'POST',
			url: '/user/chat/conversation',
			data: { 'conversation_id': id, 'chat_code': chat_code},
			success: function (data) {

				if (data == 'success') {
					$('#dynamic-inputs').html('');

					$('.chat-sidebar-messages').prepend(`<div class="chat-sidebar-message selected-message" id=${id}>
							<div class="chat-title" id="title-${id}">
								{{ __('New Chat') }}
							</div>
							<div class="chat-info">
								<div class="chat-count"><span>0</span> {{ __('messages') }}</div>
								<div class="chat-date">{{ __('Now') }}</div>
							</div>
							<div class="chat-actions d-flex">
								<a href="#" class="chat-edit fs-12" id="${id}"><i class="fa-sharp fa-solid fa-pen-to-square" data-tippy-content="{{ __('Edit Name') }}"></i></a>
								<a href="#" class="chat-delete fs-12 ml-2"  id="${id}"><i class="fa-sharp fa-solid fa-trash" data-tippy-content="{{ __('Delete Chat') }}"></i></a>
							</div>
						</div>`);
					active_id = id;	
				} else {
					toastr.warning('{{ __('There was an issue while creating chat conversation') }}');
				}		
							
			},
			error: function(data) {
				toastr.warning('{{ __('There was an issue while creating chat conversation') }}');
			}
		});
    });


	// Show chat history for conversation
	$(document).on('click', ".chat-sidebar-message", function (e) { 

		$('.chat-sidebar-message').removeClass('selected-message');
		$(this).addClass('selected-message');
		$('#dynamic-inputs').html('');
		$('#generating-status').addClass('show-chat-loader');
		active_id = this.id;
		let code = makeid(10);

		$('.chat-sidebar-container').removeClass('extend');

		$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'POST',
				url: '/user/chat/history',
				data: { 'conversation_id': active_id,},
				success: function (data) {

					$('#dynamic-inputs').html('');
					$('#generating-status').removeClass('show-chat-loader');

					for (const key in data) {

						if(data[key]['prompt']) {
							appendMessage(user_avatar, "right", data[key]['prompt'], '', data[key]['images']);
						}

						if (data[key]['response']) {
							appendMessageSpecial(bot_avatar, "left", data[key]['response'], code);
						}
					}		
					
					hljs.highlightAll();
				},
				error: function(data) {
					toastr.warning('{{ __('There was an issue while retrieving chat history') }}');
				}
			});
	});


	// Rename conversation title
	$(document).on('click', '.chat-edit', function(e) {

		e.preventDefault();

		Swal.fire({
			title: '{{ __('Rename Chat Title') }}',
			showCancelButton: true,
			confirmButtonText: '{{ __('Rename') }}',
			reverseButtons: true,
			input: 'text',
		}).then((result) => {
			if (result.value) {
				var formData = new FormData();
				formData.append("name", result.value);
				formData.append("conversation_id", $(this).attr('id'));
				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: '/user/chat/rename',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						if (data['status'] == 'success') {
							toastr.success('{{ __('Chat title has been updated successfully') }}');
							document.getElementById("title-"+data['conversation_id']).innerHTML =  result.value;
						} else {
							toastr.error('{{ __('Chat title was not updated correctly') }}');
						}      
					},
					error: function(data) {
						Swal.fire('Update Error', data.responseJSON['error'], 'error');
					}
				})
			} else if (result.dismiss !== Swal.DismissReason.cancel) {
				Swal.fire('{{ __('No Title Entered') }}', '{{ __('Make sure to provide a new chat title before updating') }}', 'warning')
			}
		})
	});


	// Delete conversation	
	$(document).on('click', '.chat-delete', function(e) {

		e.preventDefault();

		Swal.fire({
			title: '{{ __('Confirm Chat Deletion') }}',
			text: '{{ __('It will permanently delete this chat history') }}',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: '{{ __('Delete') }}',
			reverseButtons: true,
		}).then((result) => {
			if (result.isConfirmed) {
				var formData = new FormData();
				formData.append("conversation_id", $(this).attr('id'));
				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					method: 'post',
					url: '/user/chat/delete',
					data: formData,
					processData: false,
					contentType: false,
					success: function (data) {
						
						if (data['status'] == 'success') {
							toastr.success('{{ __('Chat history has been successfully deleted') }}');

							$("#" + active_id).remove();	
							$('#dynamic-inputs').html('');	
							$(".chat-sidebar-message").first().focus().trigger('click');
							let check_messages = document.querySelectorAll('.chat-sidebar-message').length;

							if (check_messages == 0) {
								let id = makeid(10);

								$.ajax({
									headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
									method: 'POST',
									url: '/user/chat/conversation',
									data: { 'conversation_id': id, 'chat_code': chat_code},
									success: function (data) {

										if (data == 'success') {
											$('#dynamic-inputs').html('');

											$('.chat-sidebar-messages').prepend(`<div class="chat-sidebar-message selected-message" id=${id}>
													<div class="chat-title" id="title-${id}">
														{{ __('New Chat') }}
													</div>
													<div class="chat-info">
														<div class="chat-count"><span>0</span> {{ __('messages') }}</div>
														<div class="chat-date">{{ __('Now') }}</div>
													</div>
													<div class="chat-actions d-flex">
														<a href="#" class="chat-edit fs-12" id="${id}"><i class="fa-sharp fa-solid fa-pen-to-square" data-tippy-content="{{ __('Edit Name') }}"></i></a>
														<a href="#" class="chat-delete fs-12 ml-2"  id="${id}"><i class="fa-sharp fa-solid fa-trash" data-tippy-content="{{ __('Delete Chat') }}"></i></a>
													</div>
												</div>`);
											active_id = id;	
										} else {
											toastr.warning('{{ __('There was an issue while deleting chat conversation') }}');
										}		
													
									},
									error: function(data) {
										toastr.warning('{{ __('There was an issue while deleting chat conversation') }}');
									}
								});
							}						
						} else if (data['status'] == 'empty') { 
							$('#dynamic-inputs').html('');	
								
						}else {
							toastr.warning('{{ __('There was an issue while deleting chat conversation') }}');
						}      
					},
					error: function(data) {
						Swal.fire('Oops...','Something went wrong!', 'error')
					}
				})
			} 
		})
	});

	// Check textarea input
	$(function () {		
		main_form.addEventListener("submit", event => {
			event.preventDefault();
			const message = input_text.value;
			if (!message) {
				toastr.warning('{{ __('Type your message first before sending') }}');
				return;
			}

			appendMessage(user_avatar, "right", message, '', uploaded_image);
			input_text.value = "";
			process(message)
		});

	});


	// Send chat message
	function process(message) {
		msgerSendBtn.disabled = true;
		let google = '';
		if ($('#google-search').is(':checked')) {
			google = 'on';
		} else {
			google = '';
		}
		let model = document.querySelector('input[name="model"]:checked').value;
		let company = document.getElementById("company").value;
		let service = document.getElementById("service").value;

		let formData = new FormData();
		formData.append('message', message);
		formData.append('chat_code', chat_code);
		formData.append('conversation_id', active_id);
		formData.append('image', uploaded_image);
		formData.append('google_search', google);
		formData.append('model', model);
		formData.append('company', company);
		formData.append('service', service);
		let code = makeid(10);
		appendMessage(bot_avatar, "left", "", code);
        let $msg_txt = $("#" + code);
		let $div = $("#chat-bubble-" + code);
		fetch('/user/chat/process', {
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: 'POST', 
				body: formData
			})		
			.then(response => response.json())
			.then(function(result){

				// if (result['old'] && result['current']) {
				// 	animateValue("balance-number", result['old'], result['current'], 300);
				// }
		
				if (result['status'] == 'error') {
					Swal.fire('{{ __('Chat Notification') }}', result['message'], 'warning');
					clearConversationInvalid();
				}
			})	
			.then(data => {
				
				eventSource = new EventSource("/user/chat/generate?conversation_id=" + active_id);				
				const response = document.getElementById(code);
				const chatbubble = document.getElementById('chat-bubble-' + code);
				let msg = '';
                let i = 0;

				eventSource.onopen = function(e) {
					response.innerHTML = '';					
				};

				eventSource.onmessage = function (e) {

					if (e.data == "[DONE]") {
						msgerSendBtn.disabled = false
						eventSource.close();
						$msg_txt.html(escape_html(msg));
						$div.data('message', msg);						
						hljs.highlightAll();						
						uploaded_image = '';

					} else {
						let txt;
						if (uploaded_image == '') {
							if (model == 'claude-3-haiku-20240307' || model == 'claude-3-sonnet-20240229' || model == 'claude-3-opus-20240229' || model == 'gemini_pro') {
								txt = e.data;
							} else {
								txt = JSON.parse(e.data).choices[0].delta.content;
							}
							
						} else {
							txt = e.data
						}

						if (txt !== undefined) {
							msg = msg + txt;

							let str = msg;
							if (model != 'gemini_pro') {
								if(str.indexOf('<') === -1){
									str = escape_html(msg)
								} else {
									str = str.replace(/[&<>"'`{}()\[\]]/g, (match) => {
										switch (match) {
											case '<':
												return '&lt;';
											case '>':
												return '&gt;';
											case '{':
												return '&#123;';
											case '}':
												return '&#125;';
											case '(':
												return '&#40;';
											case ')':
												return '&#41;';
											case '[':
												return '&#91;';
											case ']':
												return '&#93;';
											default:
												return match;
										}
									});
									str = str.replace(/(?:\r\n|\r|\n)/g, '<br>');
								}	
							}
							

							$msg_txt.html(str);
							if (model != 'gemini_pro') {
								hljs.highlightAll();
							}
                            

							//response.innerHTML += txt.replace(/(?:\r\n|\r|\n)/g, '<br>');
						}
						msgerChat.scrollTop += 100;
					}
				};
				eventSource.onerror = function (e) {
					msgerSendBtn.disabled = false
					console.log(e);
					eventSource.close();
				};
				
			})
			.catch(function (error) {
				console.log(error);
				msgerSendBtn.disabled = false;
				eventSource.close();
			});

	}

	function clearConversation() {
		document.getElementById("dynamic-inputs").innerHTML = "";

		fetch('/user/chat/clear', {
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: 'POST', 
			})		
			.then(response => response.json())
			.then(function(result){

				if (result.status == 'success') {
					toastr.success('{{ __('Chat conversation has been cleared successfully') }}');
				}

			})	
			.catch(function (error) {
				console.log(error);
				msgerSendBtn.disabled = false
			});
	}

	function clearConversationInvalid() {
		document.getElementById("dynamic-inputs").innerHTML = "";

		fetch('/user/chat/clear', {
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: 'POST', 
			})		
			.then(response => response.json())
			.then(function(result){})	
			.catch(function (error) {
				console.log(error);
				msgerSendBtn.disabled = false
			});
	}

	// Counter for words
	function animateValue(id, start, end, duration) {
		if (start === end) return;
		var range = end - start;
		var current = start;
		var increment = end > start? 1 : -1;
		var stepTime = Math.abs(Math.floor(duration / range));
		var obj = document.getElementById(id);
		var timer = setInterval(function() {
			current += increment;
			if (current > 0) {
				obj.innerHTML = current;
			} else {
				obj.innerHTML = 0;
			}
			
			if (current == end) {
				clearInterval(timer);
			}
		}, stepTime);
	}

	// Display chat messages (bot and user)
	function appendMessage(img, side, text, code, url) {
		let msgHTML;
		text = escape_html(text);

		if (side == 'left' && text == '') {
			msgHTML = `
			<div class="msg ${side}-msg">
			<div class="message-img" style="background-image: url(${img})"></div>
			<div class="message-bubble" id="chat-bubble-${code}" data-message="${text}">
				<div class="msg-text" id="${code}"><img src='{{ URL::asset("/img/svgs/chat.svg") }}'></div>
				<a href="#" class="copy"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" fill="currentColor" width="20"> <path d="M180 975q-24 0-42-18t-18-42V312h60v603h474v60H180Zm120-120q-24 0-42-18t-18-42V235q0-24 18-42t42-18h440q24 0 42 18t18 42v560q0 24-18 42t-42 18H300Zm0-60h440V235H300v560Zm0 0V235v560Z"></path> </svg></a>
				<a href="#" class="listen"><svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16 9C16.5 9.5 17 10.5 17 12C17 13.5 16.5 14.5 16 15M19 6C20.5 7.5 21 10 21 12C21 14 20.5 16.5 19 18M13 3L7 8H5C3.89543 8 3 8.89543 3 10V14C3 15.1046 3.89543 16 5 16H7L13 21V3Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></a>	
			</div>
			</div>`;
		} else {
			if (side == 'left') {
				msgHTML = `
				<div class="msg ${side}-msg">
				<div class="message-img" style="background-image: url(${img})"></div>
				<div class="message-bubble" id="chat-bubble-${code}" data-message="${text}">
					<div class="msg-text" id="${code}">${text}</div>
					<a href="#" class="copy"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" fill="currentColor" width="20"> <path d="M180 975q-24 0-42-18t-18-42V312h60v603h474v60H180Zm120-120q-24 0-42-18t-18-42V235q0-24 18-42t42-18h440q24 0 42 18t18 42v560q0 24-18 42t-42 18H300Zm0-60h440V235H300v560Zm0 0V235v560Z"></path> </svg></a>
					<a href="#" class="listen"><svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16 9C16.5 9.5 17 10.5 17 12C17 13.5 16.5 14.5 16 15M19 6C20.5 7.5 21 10 21 12C21 14 20.5 16.5 19 18M13 3L7 8H5C3.89543 8 3 8.89543 3 10V14C3 15.1046 3.89543 16 5 16H7L13 21V3Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></a>	
				</div>
				</div>`;
			} else {
				if (url == '' || url == null) {
					msgHTML = `
					<div class="msg ${side}-msg">
					<div class="message-img" style="background-image: url(${img})"></div>
					<div class="message-bubble" id="chat-bubble-${code}">
						<div class="msg-text" id="${code}">${text}</div>
					</div>
					</div>`;
				} else {
					msgHTML = `
					<div class="msg ${side}-msg">
					<div class="message-img" style="background-image: url(${img})"></div>
					<div class="message-bubble" id="chat-bubble-${code}">
						<div class="msg-text" id="${code}">${text}</div>
						<div class="msg-image mt-2 text-center"><img src="${url}" style="height:200px; border-radius:10px;"></div>						
					</div>
					
					</div>`;
				}
			}
			
		}

		dynamicList.insertAdjacentHTML("beforeend", msgHTML);
		msgerChat.scrollTop += 500;
	}

	function appendMessageSpecial(img, side, text, code, code) {
		let msgHTML;
		let copy_text = text;
		text = escape_html(text);

		msgHTML = `
		<div class="msg ${side}-msg">
		<div class="message-img" style="background-image: url(${img})"></div>
		<div class="message-bubble" id="chat-bubble-${code}" data-message="${copy_text}">
			<div class="msg-text" id="${code}">${text}</div>
			<a href="#" class="copy"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" fill="currentColor" width="20"> <path d="M180 975q-24 0-42-18t-18-42V312h60v603h474v60H180Zm120-120q-24 0-42-18t-18-42V235q0-24 18-42t42-18h440q24 0 42 18t18 42v560q0 24-18 42t-42 18H300Zm0-60h440V235H300v560Zm0 0V235v560Z"></path> </svg></a>
			<a href="#" class="listen"><svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16 9C16.5 9.5 17 10.5 17 12C17 13.5 16.5 14.5 16 15M19 6C20.5 7.5 21 10 21 12C21 14 20.5 16.5 19 18M13 3L7 8H5C3.89543 8 3 8.89543 3 10V14C3 15.1046 3.89543 16 5 16H7L13 21V3Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></a>	
		</div>
		</div>`;
			
		dynamicList.insertAdjacentHTML("beforeend", msgHTML);
		msgerChat.scrollTop += 500;
	}

	function get(selector, root = document) {
		return root.querySelector(selector);
	}

	// Generate a random value
	function makeid(length) {
		let result = '';
		const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		const charactersLength = characters.length;
		let counter = 0;
		while (counter < length) {
			result += characters.charAt(Math.floor(Math.random() * charactersLength));
			counter += 1;
		}
		return result;
	}

	function nl2br (str, is_xhtml) {
     	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
     	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
  	} 

	$('#upload-button').click(function() {
        $('#image-input').click();
    });

	$('#upload-button-mobile').click(function() {
        $('#image-input').click();
    });

	$('#upload-button-main').click(function() {
        $('#image-input').click();
    });

	$("#expand").on('click', function (e) {
        $('.chat-sidebar-container').toggleClass('extend');
    });

	// Process image upload
	const image_input = document.getElementById("image-input");

	const convertBase64 = (file) => {
		return new Promise((resolve, reject) => {
			const fileReader = new FileReader();
			fileReader.readAsDataURL(file);

			fileReader.onload = () => {
				//resolve(fileReader.result);
				var img = new Image();
				img.src = fileReader.result;
				img.onload = function() {
					var canvas = document.createElement('canvas');
					var ctx = canvas.getContext('2d');
					canvas.height = img.height * 500 / img.width;
					canvas.width = 500;
					ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
					var base64 = canvas.toDataURL('image/png');
					uploaded_image = base64;
				}
			};

			fileReader.onerror = (error) => {
				reject(error);
			};
		});
	};

	const uploadImage = async (event) => {
		const file = event.target.files[0];
		const base64 = await convertBase64(file);
	};

	if (image_input) {
		image_input.addEventListener("change", (e) => {
			uploadImage(e);
		});
	}
	
	// Search chat history
	$('#chat-search').on('keyup', function () {
        var search = $(this).val().toLowerCase();
        $('.chat-sidebar-messages').find('.chat-sidebar-message').each(function () {
            if ($(this).filter(function() {
                return $(this).find('h6').text().toLowerCase().indexOf(search) > -1;
            }).length > 0 || search.length < 1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });


	// Send via keyboard shortcuts
	$('#message').on('keypress', function (e) {
		if (e.keyCode == 13 && !e.shiftKey) {
			e.preventDefault();
			const message = input_text.value;
			if (!message) {
				toastr.warning('{{ __('Type your message first before sending') }}');
				return;
			}			

			appendMessage(user_avatar, "right", message, '', uploaded_image);
			input_text.value = "";
			process(message)
		}
    });


	// Capture input text via microphone
    if(mic) {
        if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
            const speechRecognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();

            speechRecognition.continuous = true;

            speechRecognition.addEventListener('start', () => {
                $("#mic-button").find('i').removeClass('fa-microphone').addClass('fa-stop-circle');
            });

            speechRecognition.addEventListener('result', (event) => {
                const transcript = event.results[0][0].transcript;
                $("#message").val($("#message").val() + transcript + ' ');

                mic.click();
            });

            speechRecognition.addEventListener('end', () => {
                $("#mic-button").find('i').addClass('fa-microphone').removeClass('fa-stop-circle');
                isTranscribing = false;
            });

            mic.addEventListener('click', () => {
                if (!isTranscribing) {
                    speechRecognition.start();
                    isTranscribing = true;
                } else {
                    speechRecognition.stop();
                    isTranscribing = false;
                }
            });
        } else {
            console.log('Web Speech Recognition API not supported by this browser');
            $("#mic-button").hide()
        }
    }


	// Stop chat response
	$('#stop-button').on('click', function(e){
        e.preventDefault();

        if(eventSource){
            eventSource.close();
			msgerSendBtn.disabled = false
        }
    });


	// Apply prompt
	function applyPrompt(prompt) {
		$('#message').text(prompt);
	}


	// Search prompt
	$(document).on('keyup', '#search-template', function () {
		var searchTerm = $(this).val().toLowerCase();
		$('#templates-panel').find('> div').each(function () {
			if ($(this).filter(function() {
				return (($(this).find('h6').text().toLowerCase().indexOf(searchTerm) > -1) || ($(this).find('p').text().toLowerCase().indexOf(searchTerm) > -1));
			}).length > 0 || searchTerm.length < 1) {
				$(this).show();
			} else {
				$(this).hide();
			}
		});
	});


	function escape_html (str) {
        let converter = new showdown.Converter({openLinksInNewWindow: true});
        converter.setFlavor('github');
        str = converter.makeHtml(str);

        /* add copy button */
        str = str.replaceAll('</code></pre>', '</code><button type="button" class="copy-code" onclick="copyCode(this)"><span class="label-copy-code">{{ __('Copy') }}</span></button></pre>');

        return str;
    }

	function copyCode(button) {
		const pre = button.parentElement;
		const code = pre.querySelector('code');
		const range = document.createRange();
		range.selectNode(code);
		window.getSelection().removeAllRanges();
		window.getSelection().addRange(range);
		document.execCommand("copy");
		window.getSelection().removeAllRanges();
		toastr.success('{{ __('Code has been copied successfully') }}');
	}

	$(document).on('click', ".copy", function (e) {

		e.preventDefault();

		var textArea = document.createElement("textarea");
		textArea.value = $(this).parents('.message-bubble').data('message');
		textArea.style.top = "0";
		textArea.style.left = "0";
		textArea.style.position = "fixed";
		document.body.appendChild(textArea);
		textArea.focus();
		textArea.select();

		try {
			document.execCommand('copy');
		} catch (err) {
		}

		document.body.removeChild(textArea);
		toastr.success('{{ __('Response has been copied successfully') }}');
	});

	var sound = document.createElement('audio');
	sound.id="msg-player";
 	sound.setAttribute('autoplay','false');
 	sound.setAttribute('src','');
	document.body.appendChild(sound);

	$(document).on('click', ".listen", function (e) {

		e.preventDefault();

		let text = $(this).parents('.message-bubble').data('message');		

		var formData = new FormData();
		formData.append("text", text);
		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			method: 'post',
			url: '/user/chat/listen',
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				if (data['status'] == 'success') {
 					sound.setAttribute("src", data['url']);
 					$('#msg-player')[0].play();

				} else if (data['status'] == 'error') {
					toastr.error(data['message']);
				} else {
					toastr.error('{{ __('Audio player is not available, please try again or contact support') }}');
				}      
			},
			error: function(data) {
				toastr.error('{{ __('Audio player is not available, please try again or contact support') }}');
			}
		})


	});

	function updateService(input) {

		let brand = document.getElementById('brand-voice');
		let selected = input.options[input.selectedIndex].text;
		if (input.value != 'none') {
			brand.innerHTML = '<i class="fa-solid fa-signature mr-1"></i>' + selected;
		} else {
			brand.innerHTML = '<i class="fa-solid fa-signature mr-1"></i>{{ __('Brand Voice') }}';
		}
		

		if (input.value != 'none') {

			let services = document.getElementById('service');			


			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'POST',
				url: '/user/templates/brand',
				data: { 'brand': input.value},
				success: function (data) {					
					if (data['status'] == 'success') {
						removeOptions(document.getElementById('service'));
						services.options.add( new Option("{{ __('Select your Product / Service') }}", 'none') )
						let result = data['products'];
						for(let i = 0; i < result.length; i++) {
							let obj = result[i];
							services.options.add( new Option(obj.name, i) )
						}
					} else {						
						
					}
				},
				error: function(data) {
					toastr.warning('{{ __('There was an issue') }}');
				}
			});
		}
	}

	function removeOptions(selectElement) {
		var i, L = selectElement.options.length - 1;
		for(i = L; i >= 0; i--) {
			selectElement.remove(i);
		}
	}

	function handleClick(radio) {
		
		let model = '';
		let logo = '';

		switch (radio.value) {
			case 'gpt-3.5-turbo-0125':
				model = 'GPT 3.5 Turbo';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'gpt-4':
				model = 'GPT 4';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'gpt-4o':
				model = 'GPT 4o';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'gpt-4o-mini':
				model = 'GPT 4o mini';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'gpt-4-0125-preview':
				model = 'GPT 4 Turbo';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'gpt-4-turbo-2024-04-09':
				model = 'GPT 4 Turbo Vision';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
			case 'claude-3-opus-20240229':
				model = 'Claude 3 Opus';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 inline-block h-4 w-4 align-text-top" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="M3.512 6.065c-.777 1.87-1.772 4.262-2.212 5.317l-.8 1.92 1.229.017c.676.01 1.24.006 1.255-.008.015-.014.221-.505.459-1.09l.431-1.066h4.718l.117.293.44 1.089.321.797h1.258c.98 0 1.25-.018 1.224-.082a3382.243 3382.243 0 0 1-3.78-9.073l-.625-1.512H4.924L3.512 6.065ZM9.68 2.788c.018.066 1.007 2.466 2.198 5.332l2.165 5.213h1.244c.684 0 1.23-.021 1.213-.048-.047-.074-4.3-10.303-4.354-10.472-.047-.144-.061-.146-1.274-.146-1.16 0-1.224.006-1.192.12ZM6.576 6.195c.178.439.515 1.265.75 1.838l.425 1.04-.739.019c-.406.01-1.089.01-1.517 0l-.78-.019.358-.878.748-1.837c.215-.528.4-.96.41-.96.012 0 .167.36.345.797Z" clip-rule="evenodd"></path></svg>';
				break;
			case 'claude-3-sonnet-20240229':
				model = 'Claude 3 Sonnet';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 inline-block h-4 w-4 align-text-top" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="M3.512 6.065c-.777 1.87-1.772 4.262-2.212 5.317l-.8 1.92 1.229.017c.676.01 1.24.006 1.255-.008.015-.014.221-.505.459-1.09l.431-1.066h4.718l.117.293.44 1.089.321.797h1.258c.98 0 1.25-.018 1.224-.082a3382.243 3382.243 0 0 1-3.78-9.073l-.625-1.512H4.924L3.512 6.065ZM9.68 2.788c.018.066 1.007 2.466 2.198 5.332l2.165 5.213h1.244c.684 0 1.23-.021 1.213-.048-.047-.074-4.3-10.303-4.354-10.472-.047-.144-.061-.146-1.274-.146-1.16 0-1.224.006-1.192.12ZM6.576 6.195c.178.439.515 1.265.75 1.838l.425 1.04-.739.019c-.406.01-1.089.01-1.517 0l-.78-.019.358-.878.748-1.837c.215-.528.4-.96.41-.96.012 0 .167.36.345.797Z" clip-rule="evenodd"></path></svg>';
				break;
			case 'claude-3-haiku-20240307':
				model = 'Claude 3 Haiku';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 inline-block h-4 w-4 align-text-top" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="M3.512 6.065c-.777 1.87-1.772 4.262-2.212 5.317l-.8 1.92 1.229.017c.676.01 1.24.006 1.255-.008.015-.014.221-.505.459-1.09l.431-1.066h4.718l.117.293.44 1.089.321.797h1.258c.98 0 1.25-.018 1.224-.082a3382.243 3382.243 0 0 1-3.78-9.073l-.625-1.512H4.924L3.512 6.065ZM9.68 2.788c.018.066 1.007 2.466 2.198 5.332l2.165 5.213h1.244c.684 0 1.23-.021 1.213-.048-.047-.074-4.3-10.303-4.354-10.472-.047-.144-.061-.146-1.274-.146-1.16 0-1.224.006-1.192.12ZM6.576 6.195c.178.439.515 1.265.75 1.838l.425 1.04-.739.019c-.406.01-1.089.01-1.517 0l-.78-.019.358-.878.748-1.837c.215-.528.4-.96.41-.96.012 0 .167.36.345.797Z" clip-rule="evenodd"></path></svg>';
				break;
			case 'gemini_pro':
				model = 'Gemini Pro';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 48 48" class="mr-1 inline-block h-4 w-4 align-text-top" aria-hidden="true"><path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"></path><path fill="#FF3D00" d="m6.306 14.691 6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"></path><path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"></path><path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"></path></svg>';
				break;
			default:
				model = 'Fine Tuned';
				logo = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" class="mr-1 h-4 w-4 align-text-top"><g clip-path="url(#OpenAI_svg__a)"><path fill="currentColor" fill-rule="evenodd" d="M7.385.007a4.156 4.156 0 0 0-2.978 1.568c-.24.304-.463.699-.594 1.055l-.058.156-.219.056A4.031 4.031 0 0 0 .72 5.452a3.94 3.94 0 0 0 .23 3.13c.14.271.315.535.483.729l.12.139-.039.131a3.696 3.696 0 0 0-.159 1.133c0 .657.132 1.214.423 1.788a4.087 4.087 0 0 0 2.817 2.154c.49.1 1.106.116 1.516.039l.177-.033.214.201a4 4 0 0 0 1.561.935c.521.17.985.228 1.528.191 1.337-.092 2.469-.739 3.194-1.825.157-.236.244-.402.374-.718l.103-.249.111-.027a4.308 4.308 0 0 0 1.804-.94 4.082 4.082 0 0 0 1.24-2.16 3.994 3.994 0 0 0-.75-3.26c-.078-.1-.156-.2-.174-.225-.03-.042-.029-.06.03-.29.097-.378.128-.623.128-1.017 0-1.036-.386-1.985-1.132-2.78-.591-.63-1.53-1.096-2.445-1.214a5.048 5.048 0 0 0-1.183.022l-.179.034-.197-.19A4.116 4.116 0 0 0 8.645.125a4.586 4.586 0 0 0-1.26-.117Zm.872 1.098c.235.05.547.159.752.26.176.086.52.304.566.358.016.02-.373.25-1.648.975-.918.522-1.705.98-1.748 1.02a.593.593 0 0 0-.122.16c-.042.089-.043.109-.053 2.394l-.01 2.304-.64-.363c-.353-.2-.666-.379-.696-.398l-.055-.035V5.833c0-1.222.008-2.01.021-2.116a3.08 3.08 0 0 1 .588-1.466 3.82 3.82 0 0 1 .664-.642c.353-.247.856-.456 1.234-.513.09-.013.177-.027.193-.031.088-.021.815.01.954.04Zm3.819 1.232c1.554.275 2.64 1.657 2.514 3.198a2.432 2.432 0 0 1-.03.265c-.005.009-.615-.332-1.354-.756-2.133-1.222-2.065-1.185-2.171-1.2a.593.593 0 0 0-.185.01c-.051.013-.938.505-2.067 1.147a142.354 142.354 0 0 1-2.012 1.134c-.034.008-.036-.036-.036-.771 0-.718.003-.782.035-.81.068-.057 3.305-1.885 3.507-1.98.236-.11.552-.207.812-.246.285-.044.712-.04.986.01ZM3.63 7.971a.558.558 0 0 0 .133.16c.044.033.96.56 2.034 1.17a115.76 115.76 0 0 1 1.953 1.122c0 .027-1.333.77-1.38.77-.045 0-3.031-1.683-3.421-1.928a3.04 3.04 0 0 1-1.333-1.937 3.356 3.356 0 0 1 0-1.165c.095-.422.223-.728.439-1.045.36-.53.832-.905 1.452-1.154l.05-.02.011 1.963.01 1.965.052.099Zm8.7-2.226c1.896 1.08 1.735.985 1.947 1.146a2.955 2.955 0 0 1 1.169 2.35c0 .513-.097.927-.328 1.383a3.15 3.15 0 0 1-1.05 1.149c-.134.084-.54.29-.577.29-.008 0-.018-.875-.02-1.946l-.006-1.946-.047-.092a.654.654 0 0 0-.111-.153c-.036-.034-.952-.568-2.035-1.187-1.083-.62-1.98-1.137-1.995-1.15-.02-.018.123-.107.65-.406.371-.212.69-.384.708-.385.018 0 .781.426 1.695.947Zm-2.921.76.857.485v2.02l-.864.49c-.475.27-.871.494-.88.497a20.71 20.71 0 0 1-.896-.494l-.881-.5V6.995l.873-.497c.496-.283.886-.493.903-.487.017.006.416.228.888.495Zm2.259 1.29c.318.181.613.351.655.378l.076.048v1.947c0 2.075-.003 2.143-.102 2.53-.475 1.861-2.613 2.811-4.34 1.928-.205-.105-.555-.34-.54-.362.004-.008.758-.44 1.676-.962 1.097-.623 1.698-.975 1.752-1.028.162-.157.153-.003.153-2.575v-2.28l.046.023.624.353Zm-1.4 2.845v.797l-1.66.942c-.913.518-1.733.978-1.822 1.022a3.405 3.405 0 0 1-.742.247c-.183.04-.267.047-.598.047-.29 0-.429-.01-.558-.036-.982-.202-1.779-.814-2.193-1.683a2.683 2.683 0 0 1-.278-1.163c-.01-.278.01-.574.041-.604.005-.005.748.412 1.65.925.904.514 1.674.95 1.713.97a.511.511 0 0 0 .341.04c.062-.014.803-.425 2.095-1.16 1.1-.626 2.002-1.14 2.005-1.14.003-.001.005.357.005.796Z" clip-rule="evenodd"></path></g><defs><clipPath id="OpenAI_svg__a"><path fill="#fff" d="M.5 0h16v16H.5z"></path></clipPath></defs></svg>';
				break;
		}

		let ai = document.getElementById('ai-model');
		ai.innerHTML = logo + model;

		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			method: 'POST',
			url: '/user/chat/model',
			data: { 'model': radio.value},
			success: function (data) {					
				let balance = document.getElementById('balance-number');
				let model = document.getElementById('model-name');
				balance.innerHTML =  data['balance'];
				model.innerHTML =  data['model'];

			},
			error: function(data) {
			}
		});

	}


</script>
@endsection