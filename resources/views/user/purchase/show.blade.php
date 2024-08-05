@extends('layouts.app')

@section('css')
	<link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
	<!-- PAGE HEADER -->
	<div class="page-header mt-5-7 justify-content-center">
		<div class="page-leftheader text-center">
			<h4 class="page-title mb-0">{{ __('View Order Details') }}</h4>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-money-check-pen mr-2 fs-12"></i>{{ __('User') }}</a></li>
				<li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.purchases') }}"> {{ __('Orders') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('View Order Details') }}</a></li>
			</ol>
		</div>
	</div>
	<!-- END PAGE HEADER -->
@endsection

@section('content')						
	<div class="row justify-content-center">
		<div class="col-lg-6 col-md-6 col-xm-12">
			<div class="card overflow-hidden border-0">
				<div class="card-header">
					<h3 class="card-title">{{ __('Order') }} ID: <span class="text-info">{{ $id->order_id }}</span></h3>
				</div>
				<div class="card-body pt-5">		

					<div class="row">
						<div class="col-lg-4 col-md-4 col-12">
							<div class="prepaid-view-box text-center">
								<h6 class="text-muted fs-12 mb-1">{{ __('Plan Type') }} </h6>
								<span class="fs-14 font-weight-semibold">{{ ucfirst($id->frequency) }} {{__('plan') }}</span>
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
								<h6 class="text-muted fs-12 mb-1">{{ __('Date') }} </h6>
								<span class="fs-14 font-weight-semibold">{{ date_format($id->created_at, 'M d Y, H:i:s') }}</span>
							</div>
						</div>						
					</div>

					<div class="row pt-5">
						<div class="col-lg-4 col-md-4 col-12">
							<div class="prepaid-view-box text-center">
								<h6 class="text-muted fs-12 mb-1">{{ __('Payment Gateway') }} </h6>
								<span class="fs-14 font-weight-semibold">{{ $id->gateway }}</span>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<div class="prepaid-view-box text-center">
								<h6 class="text-muted fs-12 mb-1">{{ __('Total') }} </h6>
								<span class="fs-14 font-weight-semibold">{{ $id->price }} {{ $id->currency }}</span>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-12">
							<div class="prepaid-view-box text-center">
								<h6 class="text-muted fs-12 mb-1">{{ __('Status') }} </h6>
								<span class="fs-14 font-weight-semibold">{{ ucfirst($id->status) }}</span>
							</div>
						</div>						
					</div>	

					<div class="row pt-7 justify-content-center text-center">
						<div class="col-md-6 col-12">
							<h6 class="font-weight-bold mb-2">{{ __('Transaction Invoice') }}: </h6>
							<a href="{{ route('user.payments.invoice.show', $id) }}" class="btn btn-primary ripple pl-5 pr-5">{{ __('Download Invoice') }}</a>						
						</div>
						@if ($id->gateway == 'BankTransfer')
							<div class="col-md-6 col-12">
								<h6 class="font-weight-bold mb-2">{{ __('Payment Confirmation') }}: </h6>
								@if (is_null($id->invoice))
									<a id="{{ $id->id }}" href="#" class="uploadConfirmation btn btn-primary ripple pl-5 pr-5">{{ __('Upload Confirmation') }}</a>
								@else
									<a href="{{ URL::asset($id->invoice) }}" download class="btn btn-primary ripple pl-5 pr-5">{{ __('Download Confirmation') }}</a>	
								@endif
													
							</div>
						@endif
					</div>	

					<!-- SAVE CHANGES ACTION BUTTON -->
					<div class="border-0 text-center mb-2 mt-7">
						<a href="{{ route('user.purchases') }}" class="btn btn-cancel ripple pl-7 pr-7">{{ __('Return') }}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
	<script type="text/javascript">
		$(function () {
			"use strict";

			// UPLOAD INVOICE
			$(document).on('click', '.uploadConfirmation', function(e) {

				e.preventDefault();

				Swal.fire({
					title: '{{ __('Upload Payment Confirmation') }}',
					showCancelButton: true,
					confirmButtonText: '{{ __('Upload') }}',
					reverseButtons: true,	
					input: 'file',
				}).then((file) => {
					if (file.value) {
						var formData = new FormData();
						var file = $('.swal2-file')[0].files[0];
						formData.append("confirmation", file);
						formData.append("id", $(this).attr('id'));
						$.ajax({
							headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
							method: 'post',
							url: '/user/purchases/invoice/upload',
							data: formData,
							processData: false,
							contentType: false,
							success: function (data) {
								if (data == 'success') {
									Swal.fire('{{ __('Confirmation Uploaded') }}', '{{ __('Payment confirmation has been successfully uploaded') }}', 'success');
								} else {
									Swal.fire('{{ __('Upload Error') }}', '{{ __('Make sure you are uploading an image or pdf file') }}', 'error');
								}      
							},
							error: function(data) {
								Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
							}
						})
					} else if (file.dismiss !== Swal.DismissReason.cancel) {
						Swal.fire('{{ __('No File Selected') }}', '{{ __('Make sure you are uploading an image or pdf file') }}', 'error')
					}
				})
			});

		});
	</script>
@endsection

