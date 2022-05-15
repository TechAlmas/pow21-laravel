@extends('crudbooster::admin_template')
@section('content')
	<div>
		@if(CRUDBooster::getCurrentMethod() != 'getProfile' && $button_cancel)
			@if(g('return_url'))
				<p><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i>
						&nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>
			@else
				<p><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i>
						&nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>
			@endif
		@endif
		<div class='panel panel-default'>
			<div class='panel-heading'>
				 {{ __('Edit Product Name') }}
			</div>
		
			<div class="panel-body">
				<div class="">
					<div class="col-12">
						<form method="post" action="{{CRUDBooster::mainpath('update-product-name/'.$product_name->id)}}" autocomplete="off" enctype="multipart/form-data">
							@csrf
							
							<input type="hidden" name="product_name_id" value="{{ $product_name->id }}">
							
							<div class="row">
								<div class="col-sm-10 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
									<label for="name">{{ __('Product Name') }}</label>
									<input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Product Name') }}" value="{{ $product_name->name }}" id="name" required >
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-10 form-group{{ $errors->has('tags') ? ' has-danger' : '' }}">
									<label for="tags">{{ __('Tags') }}</label>
									<textarea name="tags" id="tags" class="form-control">{{ $product_name->tags }}</textarea>
								</div>
							</div>
																							
							<div class="row">
								<div class="col-sm-10 form-group{{ $errors->has('source') ? ' has-danger' : '' }}">
									<label for="source">{{ __('Source') }}</label>
									<select name="source" class="form-control select2_option{{ $errors->has('source') ? ' is-invalid' : '' }}" id="source" >
										@foreach($sources as $key=>$source)
										<option value="{{ $key }}" {{ $key==strtolower($product_name->source)?'selected':'' }}>{{ $source }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-10 form-group{{ $errors->has('product_brand') ? ' has-danger' : '' }}">
									<label for="product_brand">{{ __('Product Brand') }}</label>
									<select name="product_brand" class="form-control select2_option{{ $errors->has('source') ? ' is-invalid' : '' }}" id="product_brand" >
										@foreach($product_brands as $pbkey=>$product_brand)
										<option value="{{ $product_brand->brand_id }}" {{ $product_brand->brand_id==$product_name->product_brand?'selected':'' }}>{!! $product_brand->brand->name !!}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-10 form-group{{ $errors->has('product_types') ? ' has-danger' : '' }}">
									<label for="product_types">{{ __('Product Types') }}</label>
									<select name="product_types[]" class="form-control select2_option{{ $errors->has('product_types') ? ' is-invalid' : '' }}" id="product_types" multiple>
										@foreach($product_types as $key=>$product_type)
										<option value="{{ $product_type->id }}" {{ in_array($product_type->id,$p_product_type)?'selected':'' }}>{!! $product_type->name !!}</option>
										@endforeach
									</select>
								</div>
							</div>
													
							<button type="submit" class="btn btn-fill btn-primary">{{ __('Update') }}</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	@push('head')
    <link rel='stylesheet' href='<?php echo asset("vendor/crudbooster/assets/select2/dist/css/select2.min.css")?>'/>
    <style>
    .select2-container--default .select2-selection--single {border-radius: 0px !important; }
   	.select2-container .select2-selection--single {height: 35px}
   </style>
@endpush

@push('bottom')
<script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
<script>
	$(function () {
		$('.select2_option').select2();
	
	$('#producer_id').select2({
		ajax: {
			url: "{{ route('scrape.bcc.load_brands') }}",
			dataType: 'json',
			delay: 250,
			data: function (query){
				return {
					q: query.term,
					producer_id: $('#producer_id1').val(),
					format: 'json'
				};
			},
			processResults: function(data, params){
				return {
					results: data
				};
			},
			cache: true
		}
	});
	});

</script>
@endpush
	@endsection