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
				 {{ __('Product Edit') }}
			</div>
			<div class="panel-body">
				<div class="">
					<div class="col-12">
						<form method="post" action="{{ route('scrape.bcc.product_update',$product->id) }}" autocomplete="off" enctype="multipart/form-data">
							@csrf
							
							<input type="hidden" name="product_id" value="{{ $product->id }}">
							<input type="hidden" name="master_brands_strains_id" value="{{ $product->master_brands_strains_id }}">
							<input type="hidden" name="producer_id1" value="{{$product->producer_id}}" id="producer_id1">
							<div class="row">
								<div class="col-sm-10 form-group{{ $errors->has('product_title') ? ' has-danger' : '' }}">
									<label for="cat_id">{{ __('Category') }}</label>
									<select name="cat_id" class="form-control select2_option{{ $errors->has('cat_id') ? ' is-invalid' : '' }}" id="cat_id" >
										@foreach($categories as $key=>$category)
										<option value="{{ $category->id }}" {{ $product->cat_id == $category->id?'selected':'' }}>{!! $category->name !!}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-10 form-group{{ $errors->has('product_title') ? ' has-danger' : '' }}">
									<label for="product_title">{{ __('Title') }}</label>
									<input type="text" name="product_title" class="form-control{{ $errors->has('product_title') ? ' is-invalid' : '' }}" placeholder="{{ __('Product Name') }}" value="{{ $product->product_title }}" id="product_title" >
								</div>
							</div>

							<div class="row">
								<div class="col-sm-10 form-group{{ $errors->has('product_description') ? ' has-danger' : '' }}">
									<label for="product_description">{{ __('Description') }}</label>
									<textarea rows="5" name="product_description" class="editor form-control{{ $errors->has('product_description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" id="product_description" style="max-height: initial;" >{{ $product->product_description }}</textarea>
								</div>
							</div>
							
							@if(isset($short_description->meta_value))
								@php
									$short_descriptions = json_decode($short_description->meta_value);
								@endphp
								@if(count($short_descriptions))
									@php
									$s_text = [];
									@endphp
									@foreach($short_descriptions as $skey=>$short_description)
										@php
											$s_text[] = $short_description->text;
										@endphp
									@endforeach
									<div class="row">
										<div class="col-sm-10 form-group{{ $errors->has('short_description') ? ' has-danger' : '' }}">
											<label for="short_description">{{ __('Short Description') }}</label>
											<textarea rows="5" name="short_description" class="editor form-control{{ $errors->has('short_description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" id="short_description" style="max-height: initial;" >{{ implode(' ',$s_text) }}</textarea>
										</div>
									</div>
								@endif
							@endif

							<div class="row">
								<div class="col-sm-10 {{ $errors->has('image') ? ' has-danger' : '' }}">
									<label for="image">{{ __('Image') }}</label>
									<input type="file" name="image" class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}"  id="image">
									@if($product->image)
										@php
										$parsed = parse_url($product->image);
										if(empty($parsed['scheme'])){
											$urlStr = asset($product->image);
										}else{
											 $urlStr = $product->image;
										}
										@endphp
									<img src="{{ $urlStr }}" width="100px">
									@endif
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-10 {{ $errors->has('gallery') ? ' has-danger' : '' }}">
									<label for="gallery">{{ __('Gallery') }}</label>
									<input type="file" name="gallery[]" class="form-control {{ $errors->has('gallery') ? ' is-invalid' : '' }}"  id="gallery" multiple>
									@if(isset($galleries->meta_value) && $galleries->meta_value)
										@php
											$galleries = json_decode($galleries->meta_value);
										@endphp
										
										@if($galleries)
											<ul class="list-inline">
											@foreach($galleries as $keyG=>$g_image)
											
												@if($g_image)
													@php
													$parsed = parse_url($g_image->image);
													if(empty($parsed['scheme'])){
														$urlStr = asset($g_image->image);
													}else{
														 $urlStr = $g_image->image;
													}
													@endphp
													<li>
														<img src="{{ $urlStr }}" width="100px">
														<p><a href="{{ route('scrape.bcc.product_remove_gallery_link', [$keyG,$product->id] ) }}" class="remove_g_links" onclick="return confirm('Are you sure?');">Remove Image</a></p>
													</li>
												@endif
											
											@endforeach
											</ul>
										@endif
									@endif
								</div>
							</div>
							@if(count($attributes))
							<div class="row">
								@foreach($attributes as $a_key=>$attribute)
									<div class="col-sm-10 form-group{{ $errors->has('product_description') ? ' has-danger' : '' }}">
										<label for="{{ $attribute->meta_key }}{{$a_key}}">{{ __(ucwords(str_replace('_', ' ', $attribute->meta_key))) }}</label>
										<input type="text" name="attribute[{{ $attribute->meta_key }}]" class="form-control" placeholder="{{ __('Description') }}" id="{{ $attribute->meta_key }}{{$a_key}}" value="{{ $attribute->meta_value }}" >
									</div>
									@if($attribute->meta_key == 'producer')
										
										<div class="col-sm-10 form-group{{ $errors->has('producer_id') ? ' has-danger' : '' }}">
											<label for="producer_id">{{ __('OEM Brand') }}</label>
												
											<select name="producer_id" id="producer_id" class="form-control">
												<option value="">Select</option>
												@if($master_brands)
												<option value="{{$master_brands->id}}" selected>{{ $master_brands->name }}</option>
												@endif
												
											</select>
										</div>
										
									@endif
									
									@if($attribute->meta_key == 'brand')
										
										<div class="col-sm-10 form-group{{ $errors->has('brand_id') ? ' has-danger' : '' }}">
											<label for="brand_id">{{ __('Product Brand') }}</label>
											<select name="brand_id" id="brand_id" class="form-control select2_option">
												<option value="">Select</option>
												@foreach($brands as $bKey=>$brand)
													<option value="{{ $brand->brand_id }}" {{ $brand->brand_id==$product->brand_id?'selected':'' }}>{{ $brand->brand->name }}</option>
												@endforeach
											</select>
										</div>
										
									@endif
								
								@endforeach
							</div>
							@endif
							
							@if(isset($variants->meta_value))
								<div class="row">
									@php
										$variants = json_decode($variants->meta_value);
									@endphp
									@if($variants)
										<div class="col-sm-10 form-group{{ $errors->has('variants') ? ' has-danger' : '' }}">
											<label for="variants_0">{{ __('Variants') }}</label>
											@foreach($variants as $vk=>$variant)
												<input type="text" name="variants[]" class="form-control" placeholder="{{ __('Variants') }}" id="variants_{{ $vk }}" value="{{ $variant }}" >
											@endforeach
										</div>
									@endif
								</div>
							@endif
							
							<div class="row">
								<div class="col-sm-10 form-group{{ $errors->has('business_type') ? ' has-danger' : '' }}">
									<label for="business_type">{{ __('Business Type	') }}</label>
									<select name="business_type" class="form-control select2_option{{ $errors->has('business_type') ? ' is-invalid' : '' }}" id="business_type" >
										<option value="2" {{ $product->business_type == '2'?'selected':'' }}>Dispensary</option>
										<option value="3" {{ $product->business_type == '3'?'selected':'' }}>Government</option>
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