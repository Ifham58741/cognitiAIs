@extends('layouts.app')
@section('css')
	<!-- Data Table CSS -->
	<link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
	<!-- Green Audio Players CSS -->
	<link href="{{ URL::asset('plugins/audio-player/green-audio-player.css') }}" rel="stylesheet" />
	<!-- Sweet Alert CSS -->
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
	<div class="row mt-24 justify-content-center">

		@if ($type == 'Regular License' || $type == '')
			<div class="row text-center justify-content-center">
				<p class="fs-14" style="background:#FFE2E5; color:#ff0000; padding:1rem 2rem; border-radius: 0.5rem; max-width: 1200px;">{{ __('Extended License is required in order to have access to these features') }}</p>
			</div>			
		@else
			<div class="row no-gutters justify-content-center">
				<div class="col-lg-9 col-md-11 col-sm-12 text-center">
					<h3 class="card-title mt-2 fs-20"><i class="fa-solid fa-photo-film mr-2 text-primary"></i></i>{{ __('AI Photo Studio') }}</h3>
					<h6 class="text-muted mb-7">{{ __('State-of-the-art AI image processing for the creation and enhancement of visual contents') }}</h6>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12">
				<div class="card border-0">
					<div class="card-header pt-4 border-0">
						<p class="fs-11 text-muted mb-0 text-left"><i class="fa-sharp fa-solid fa-bolt-lightning mr-2 text-primary"></i>{{ __('Your Balance is') }} <span class="font-weight-semibold" id="balance-number">@if (auth()->user()->available_sd_images == -1) {{ __('Unlimited') }} @else {{ number_format(auth()->user()->available_sd_images + auth()->user()->available_sd_images_prepaid) }}@endif {{ __('SD Images') }}</span></p>
					</div>
					<form id="photo-studio-form" action="{{ route('user.photo.studio.generate') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="card-body pt-2 pl-6 pr-6 pb-5" id="">
							<div class="photo-studio-tools mb-5">
								<div class="nav-item dropdown w-100">
									<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
										<span class="dropdown-item-icon mr-3 ml-1" id="active-template-icon"><i class="fa-solid fa-aperture"></i></span>
										<h6 class="dropdown-item-title fs-13 font-weight-semibold" id="active-template-name">{{ __('ReImagine') }}</h6>	
									</a>
									<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">										
										<a class="dropdown-item d-flex" href="#"  id="reimagine" name="{{ __('Reimagine') }}" icon="<i class='fa-solid fa-aperture'></i>">
											<span class="dropdown-item-icon mr-3 ml-1 text-muted"><i class="fa-solid fa-aperture"></i></span>
											<h6 class="dropdown-item-title fs-12">{{ __('ReImagine') }} <span class="fs-9 text-muted">({{ $studio->sd_photo_studio_reimagine }} {{ __('credits per image') }})</span></h6>										
										</a>	
										<a class="dropdown-item d-flex" href="#"  id="erase" name="{{ __('Erase Objects') }}" icon="<i class='fa-solid fa-droplet'></i>">
											<span class="dropdown-item-icon mr-3 ml-1 text-muted"><i class="fa-solid fa-droplet"></i></span>
											<h6 class="dropdown-item-title fs-12">{{ __('Erase Objects') }} <span class="fs-9 text-muted">({{ $studio->sd_photo_studio_erase_object }} {{ __('credits per image') }})</span></h6>										
										</a>
										<a class="dropdown-item d-flex" href="#"  id="inpaint" name="{{ __('Inpaint Image') }}" icon="<i class='fa-solid fa-hexagon-image'></i>">
											<span class="dropdown-item-icon mr-3 ml-1 text-muted"><i class="fa-solid fa-hexagon-image"></i></span>
											<h6 class="dropdown-item-title fs-12">{{ __('Inpaint Image') }} <span class="fs-9 text-muted">({{ $studio->sd_photo_studio_inpaint }} {{ __('credits per image') }})</span></h6>										
										</a>
										<a class="dropdown-item d-flex" href="#"  id="outpaint" name="{{ __('Outpaint Image') }}" icon="<i class='fa-solid fa-images'></i>">
											<span class="dropdown-item-icon mr-3 ml-1 text-muted"><i class="fa-solid fa-images"></i></span>
											<h6 class="dropdown-item-title fs-12">{{ __('Outpaint Image') }} <span class="fs-9 text-muted">({{ $studio->sd_photo_studio_outpaint }} {{ __('credits per image') }})</span></h6>										
										</a>
										<a class="dropdown-item d-flex" href="#"  id="replace" name="{{ __('Search and Replace') }}" icon="<i class='fa-solid fa-eye'></i>">
											<span class="dropdown-item-icon mr-3 ml-1 text-muted"><i class="fa-solid fa-eye"></i></span>
											<h6 class="dropdown-item-title fs-12">{{ __('Search and Replace') }} <span class="fs-9 text-muted">({{ $studio->sd_photo_studio_search_replace }} {{ __('credits per image') }})</span></h6>										
										</a>
										<a class="dropdown-item d-flex" href="#"  id="background" name="{{ __('Remove Background') }}" icon="<i class='fa-solid fa-image-slash'></i>">
											<span class="dropdown-item-icon mr-3 ml-1 text-muted"><i class="fa-solid fa-image-slash"></i></span>
											<h6 class="dropdown-item-title fs-12">{{ __('Remove Background') }} <span class="fs-9 text-muted">({{ $studio->sd_photo_studio_remove_background }} {{ __('credits per image') }})</span></h6>										
										</a>
										<a class="dropdown-item d-flex" href="#"  id="sketch" name="{{ __('Sketch to Image') }}" icon="<i class='fa-solid fa-pen-clip'></i>">
											<span class="dropdown-item-icon mr-3 ml-1 text-muted"><i class="fa-solid fa-pen-clip"></i></span>
											<h6 class="dropdown-item-title fs-12">{{ __('Sketch to Image') }} <span class="fs-9 text-muted">({{ $studio->sd_photo_studio_sketch }} {{ __('credits per image') }})</span></h6>										
										</a>
										<a class="dropdown-item d-flex" href="#"  id="structure" name="{{ __('Structure to Image') }}" icon="<i class='fa-solid fa-camera-viewfinder'></i>">
											<span class="dropdown-item-icon mr-3 ml-1 text-muted"><i class="fa-solid fa-camera-viewfinder"></i></span>
											<h6 class="dropdown-item-title fs-12">{{ __('Structure to Image') }} <span class="fs-9 text-muted">({{ $studio->sd_photo_studio_structure }} {{ __('credits per image') }})</span></h6>										
										</a>
										<a class="dropdown-item d-flex" href="#"  id="upscale_conservative" name="{{ __('Conservative Upscale') }}" icon="<i class='fa-sharp fa-solid fa-high-definition'></i>">
											<span class="dropdown-item-icon mr-3 ml-1 text-muted"><i class="fa-sharp fa-solid fa-high-definition"></i></span>
											<h6 class="dropdown-item-title fs-12">{{ __('Conservative Upscale') }} <span class="fs-9 text-muted">({{ $studio->sd_photo_studio_conservative_upscaler }} {{ __('credits per image') }})</span></h6>										
										</a>											
										<a class="dropdown-item d-flex" href="#"  id="upscale_creative" name="{{ __('Creative Upscale') }}" icon="<i class='fa-sharp fa-solid fa-high-definition'></i>">
											<span class="dropdown-item-icon mr-3 ml-1 text-muted"><i class="fa-sharp fa-solid fa-high-definition"></i></span>
											<h6 class="dropdown-item-title fs-12">{{ __('Creative Upscale') }} <span class="fs-9 text-muted">({{ $studio->sd_photo_studio_creative_upscaler }} {{ __('credits per image') }})</span></h6>										
										</a>
										<a class="dropdown-item d-flex" href="#"  id="text" name="{{ __('Text to Image') }}" icon="<i class='fa-solid fa-wand-magic-sparkles'></i>">
											<span class="dropdown-item-icon mr-3 ml-1 text-muted"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
											<h6 class="dropdown-item-title fs-12">{{ __('Text to Image') }} <span class="fs-9 text-muted">({{ $studio->sd_photo_studio_text }} {{ __('credits per image') }})</span></h6>										
										</a>
									</div>
								</div>
							</div>

							<div class="input-box" style="position: relative">
								<div id="image-drop-box">
									<h6 class="text-muted font-weight-semibold">{{ __('Target Image') }}</h6>
									<div class="image-drop-area text-center mt-2 file-drop-border photo-studio-upload">
											
										<input type="file" class="main-image-input" name="image" id="image" accept="image/png, image/jpeg, image/webp" onchange="loadImage(event)" required>
										<div class="image-upload-icon">
											<i class="fa-sharp fa-solid fa-camera fs-28 text-muted"></i>
										</div>
										<p class="text-muted fs-12 font-weight-semibold mb-0 mt-1">
											{{ __('Drag and drop your image or') }}
											<a href="javascript:void(0);" class="text-primary">{{ __('Browse') }}</a>
										</p>
										<p class="mb-5 file-name fs-12 text-muted">
											<small class="text-muted fs-10">({{ __('PNG | JPG | WEBP') }})</small>
										</p>
										<img id="source-image-variations" class="p-4">
									</div>
								</div>
							</div>

							{{-- <div class="input-box" style="position: relative">
								<div id="image-drop-box">
									<h6 class="text-muted font-weight-semibold">{{ __('Image Mask') }}</h6>
									<div class="image-drop-area text-center mt-2 file-drop-border photo-studio-upload">
											
										<input type="file" class="main-image-input" name="image_mask" id="image_mask" accept="image/png, image/jpeg, image/webp" onchange="loadImageMask(event)">
										<div class="image-upload-icon">
											<i class="fa-sharp fa-solid fa-camera-viewfinder fs-28 text-muted"></i>
										</div>
										<p class="text-muted fs-12 font-weight-semibold mb-0 mt-1">
											{{ __('Drag and drop your image mask or') }}
											<a href="javascript:void(0);" class="text-primary">{{ __('Browse') }}</a>
										</p>
										<p class="mb-5 file-name fs-12 text-muted">
											<small class="text-muted fs-10">({{ __('PNG | JPG | WEBP') }})</small>
										</p>
										<img id="source-image-variations-mask" class="p-4">
									</div>
								</div>
							</div> --}}

							<div class="row">
								<div class="col-sm-12">	
									<div class="input-box">	
										<h6 class="text-muted">{{ __('Prompt') }}</h6>							
										<textarea class="form-control" name="prompt" rows="5" id="prompt" placeholder="{{ __('Provide your image prompt description...') }}" required></textarea>	
									</div>											
								</div>	

								<div class="col-sm-12 hidden" id="search-prompt-box">	
									<div class="input-box">	
										<h6 class="text-muted">{{ __('Search Prompt') }}</h6>							
										<textarea class="form-control" name="search_prompt" rows="1" id="search_prompt" placeholder="{{ __('What would you like to replace?') }}"></textarea>	
									</div>											
								</div>	
								
								<div class="row hidden" id="outpaint-sizes">
									<div class="col-md-6 col-sm-12">								
										<div class="input-box">								
											<h6 class="fs-11 mb-2 font-weight-semibold text-muted">{{ __('Left') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('The number of pixels to outpaint on the left side of the image. At least one outpainting direction must be supplied with a non-zero value.') }}"></i></h6>
											<div class="form-group">							    
												<input type="number" min="0" max="2000" class="form-control" id="left" name="left" value="0">
											</div> 
										</div> 
									</div>
									<div class="col-md-6 col-sm-12">								
										<div class="input-box">								
											<h6 class="fs-11 mb-2 font-weight-semibold text-muted">{{ __('Right') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('The number of pixels to outpaint on the right side of the image. At least one outpainting direction must be supplied with a non-zero value.') }}"></i></h6>
											<div class="form-group">							    
												<input type="number" min="0" max="2000" class="form-control" id="right" name="right" value="512">
											</div> 
										</div> 
									</div>
									<div class="col-md-6 col-sm-12">								
										<div class="input-box">								
											<h6 class="fs-11 mb-2 font-weight-semibold text-muted">{{ __('Up') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('The number of pixels to outpaint on the up side of the image. At least one outpainting direction must be supplied with a non-zero value.') }}"></i></h6>
											<div class="form-group">							    
												<input type="number" min="0" max="2000" class="form-control" id="up" name="up" value="0">
											</div> 
										</div> 
									</div>
									<div class="col-md-6 col-sm-12">								
										<div class="input-box">								
											<h6 class="fs-11 mb-2 font-weight-semibold text-muted">{{ __('Down') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('The number of pixels to outpaint on the down side of the image. At least one outpainting direction must be supplied with a non-zero value.') }}"></i></h6>
											<div class="form-group">							    
												<input type="number" min="0" max="2000" class="form-control" id="down" name="down" value="0">
											</div> 
										</div> 
									</div>
								</div>

								<div class="col-sm-12 hidden" id="resolution">
									<div class="input-box">	
										<h6 class="text-muted">{{ __('Result Size') }}</h6>
										<select  name="resolution_sd" class="form-select">	
											<option value='1:1' selected>1:1 ({{ __('Aspect Ratio') }})</option>
											<option value='2:3'>2:3 ({{ __('Aspect Ratio') }})</option>
											<option value='3:2'>3:2 ({{ __('Aspect Ratio') }})</option>
											<option value='4:5'>4:5 ({{ __('Aspect Ratio') }})</option>
											<option value='5:4'>5:4 ({{ __('Aspect Ratio') }})</option>
											<option value='9:16'>9:16 ({{ __('Aspect Ratio') }})</option>
											<option value='16:9'>16:9 ({{ __('Aspect Ratio') }})</option>
											<option value='9:21'>9:21 ({{ __('Aspect Ratio') }})</option>																																																																		
										</select>
									</div>
								</div>
							</div>	

							

							<div class="col-sm-12">
								<div class="divider mt-0" id="wizard-advanced">
									<div class="divider-text text-muted">
										<a class="fs-11 text-muted" id="advanced-settings-toggle" href="#">{{ __('Advanced Settings') }} <span>+</span></a>
									</div>
								</div>
							</div>

							<div id="wizard-advanced-wrapper" class="no-gutters">
								<div class="row">
									<div class="col-sm-12">	
										<div class="input-box">	
											<h6 class="text-muted">{{ __('Negative Prompt') }}</h6>							
											<textarea class="form-control" name="negative_prompt" rows="4" id="negative_prompt" placeholder="{{ __('Negative prompt description...') }}"></textarea>	
										</div>											
									</div>
									<div class="col-md-6 col-sm-12">								
										<div class="input-box">								
											<h6 class="fs-11 mb-2 font-weight-semibold text-muted">{{ __('Creativity') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('Controls the likelihood of creating additional details not heavily conditioned by the init image.') }}"></i></h6>
											<div class="form-group">							    
												<input type="number" min="0" max="1" step="0.1" class="form-control" id="creativity" name="creativity" value="0.5">
											</div> 
										</div> 
									</div>
									<div class="col-md-6 col-sm-12">								
										<div class="input-box">								
											<h6 class="fs-11 mb-2 font-weight-semibold text-muted">{{ __('Control Strength') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('How much influence, or control, the image has on the generation. Represented as a float between 0 and 1, where 0 is the least influence and 1 is the maximum.') }}"></i></h6>
											<div class="form-group">							    
												<input type="number" min="0" max="1" step="0.1" class="form-control" id="control_strength" name="control_strength" value="0.7">
											</div> 
										</div> 
									</div>
									<div class="col-md-6 col-sm-12">
										<div class="input-box">	
											<h6 class="fs-11 mb-2 font-weight-semibold text-muted">{{ __('Seed') }} <i class="ml-1 text-dark fs-12 fa-solid fa-circle-info" data-tippy-content="{{ __('A specific value that is used to guide the randomness of the generation. Use 0 to get a random seed.') }}"></i></h6>
											<input type="number" class="form-control" name="seed" value="0">
										</div>		
									</div>
								</div>	
							</div>

							<div class="text-center mt-4 mb-2">
								<button type="submit" class="btn btn-primary ripple main-action-button" id="generate" style="text-transform: none; min-width: 200px;">{{ __('Generate') }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="col-lg-6 col-md-8 col-xm-12">
				<div id="photo-studio-placeholder" class="text-center">
					<img class="mb-4" src="{{ URL::asset('img/svgs/gallery.svg') }}" alt="">
					<h6 class="text-muted">{{ __('Start generating your image') }}</h6>
				</div>
				<div id="photo-studio-result" class="hidden">
					<div class="card border-0">
						<div class="card-body p-6">
							<a href="" id="download" class="download-image text-center" download><i class="fa-sharp fa-solid fa-arrow-down-to-line" title="{{ __('Download Image') }}"></i></a>
							<img id="result" alt="">
						</div>
					</div>					
				</div>
			</div>
		@endif

	</div>
</div>
@endsection
@section('js')
	<!-- Data Tables JS -->
	<script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script type="text/javascript">
		let active_task = 'reimagine';
		let loading = `<span class="loading">
						<span style="background-color: #fff;"></span>
						<span style="background-color: #fff;"></span>
						<span style="background-color: #fff;"></span>
						</span>`;
		

		var loadImage = function(event) {
			var output = document.getElementById('source-image-variations');
			output.style.display = 'block';
			output.src = URL.createObjectURL(event.target.files[0]);
			output.onload = function() {
				URL.revokeObjectURL(output.src) // free memory
			}
		};

		var loadImageMask = function(event) {
			var output = document.getElementById('source-image-variations-mask');
			output.style.display = 'block';
			output.src = URL.createObjectURL(event.target.files[0]);
			output.onload = function() {
				URL.revokeObjectURL(output.src) // free memory
			}
		};


		function animateValue(id, start, end, duration) {
			if (start === end) return;
			var range = end - start;
			var current = start;
			var increment = end > start? 1 : -1;
			var stepTime = Math.abs(Math.floor(duration / range));
			var obj = document.getElementById(id);
			var timer = setInterval(function() {
				current += increment;
				obj.innerHTML = current;
				if (current == end) {
					clearInterval(timer);
				}
			}, stepTime);
		}


		$('.photo-studio-tools .dropdown .dropdown-menu .dropdown-item').click(function(e){
			e.preventDefault();

			let task = $(this).attr('id');
			let name = $(this).attr('name');
			let icon = $(this).attr('icon');
			let template_icon = document.getElementById('active-template-icon');
			let template_name = document.getElementById('active-template-name');
			active_task = task;
			template_name.innerHTML = name;
			template_icon.innerHTML = icon;

			if (task == 'replace') {
				$('#search-prompt-box').removeClass('hidden');
			} else {
				$('#search-prompt-box').addClass('hidden');
			}

			if (task == 'outpaint') {
				$('#outpaint-sizes').removeClass('hidden');
			} else {
				$('#outpaint-sizes').addClass('hidden');
			}

			if (task == 'text') {
				$('#resolution').removeClass('hidden');
				$('#image-drop-box').addClass('hidden');
				document.getElementById("image").required = false;
			} else {
				$('#resolution').addClass('hidden');
				$('#image-drop-box').removeClass('hidden');
				document.getElementById("image").required = true;
			}
		});


		$('#advanced-settings-toggle').on('click', function (e) {
            e.preventDefault();
            $('#wizard-advanced-wrapper').slideToggle();
            let $plus = $(this).find('span');
            if($plus.text() === '+'){
                $plus.text('-')
            } else {
                $plus.text('+')
            }
        });


		// SUBMIT FORM
		$('#photo-studio-form').on('submit', function(e) {

			e.preventDefault();

			let form = new FormData(this);
			form.append('task', active_task);

			if (active_task != 'text') {
				if (document.getElementById('image').files.length === 0) {
					Swal.fire('{{ __('Target Image Warning') }}', '{{ __('Please select an image file first for this task') }}', 'warning');
					return;
				} 
			} 

			$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: 'POST',
				url: '/user/photo-studio/generate',
				data: form,
				contentType: false,
				processData: false,
				cache: false,
				beforeSend: function() {
					$('#generate').prop('disabled', true);
					let btn = document.getElementById('generate');					
					btn.innerHTML = loading;  
					document.querySelector('#loader-line')?.classList?.remove('hidden');      
				},
				complete: function() {
					document.querySelector('#loader-line')?.classList?.add('hidden'); 
					$('#generate').prop('disabled', false);
					$('#generate').html('{{ __("Generate") }}');            
				},
				success: function (data) {		
						
					if (data['status'] == 'success') {		
						let image = data['image'];

						$('#photo-studio-placeholder').addClass('hidden');
						$('#photo-studio-result').removeClass('hidden');
						document.getElementById("result").src = image;
						$("#download").attr("href", image);

						

						toastr.success('{{ __('Image successfully generated') }}');	
						

						if (data['balance'] != 'unlimited') {
							animateValue("balance-number-sd", data['old'], data['current'], 2000);	
						}
	
					} else {						
						Swal.fire('{{ __('Image Generation Error') }}', data['message'], 'warning');
					}
				},
				error: function(data) {
					$('#image-generate').prop('disabled', false);
					$('#image-generate').html('<i class="fa-sharp fa-solid fa-wand-magic-sparkles mr-2"></i>{{ __("Generate") }}'); 
				}
			});
		});

		
	</script>
@endsection