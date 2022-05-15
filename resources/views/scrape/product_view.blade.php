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
			 {{ __('Product Details') }}
		</div>
		<div class="panel-body">
			<div class="">
			
				<h1>{{ $product->product_title }}</h1>
				<p>{!! $category->name !!} </p>
				<p>{!! $product->vendor !!} </p>
				<h4> URL: </h4>
					<p>https://www.bccannabisstores.com{{ ($product->url) }} </p>
				@if(isset($short_description->meta_value))
					@php
						$short_descriptions = json_decode($short_description->meta_value);
					@endphp
					@if($short_descriptions)
						<h4>Short-description:</h4>
						@foreach($short_descriptions as $ck=>$short_description)
						<p>{{ $short_description->text }}</p>
						@endforeach
					@endif
			   
				@endif
				@if(isset($additional_info->meta_value))
					@php
						$additional_info = json_decode($additional_info->meta_value);
					@endphp
					<h4> Quantity Note: </h4>
					<p>{!! isset($additional_info[0]->value)?$additional_info[0]->value:'' !!} </p>
						<h4> Sku #: </h4>
					<p>{!! isset($additional_info[1]->value)?$additional_info[1]->value:'' !!} </p>
				@endif
				@if($product->image)
					<img src="{{ $product->image }}" width="100px">
				@endif

				<h2>Details</h2>
				{!!$product->product_description!!}
				@if(isset($no_models->meta_value) && !empty($no_models->meta_value))
					@php
						$part_data = json_decode($no_models->meta_value);
					@endphp
					{!!($part_data->no_models)!!}
				   
				@endif
				@if(isset($characteristics->meta_value))
					@php
						$chcs = json_decode($characteristics->meta_value);
					@endphp
					@if($chcs)
						@foreach($chcs as $ck=>$chc)
						<li>{{ $chc->key }}: {{ $chc->value }}</li>
						@endforeach
					@endif
				@endif
				@if(isset($terpenes->meta_value))
					<h2>Terpene Profile</h2>
					@php
						$terpenes = json_decode($terpenes->meta_value);
					@endphp
					@if($terpenes)
						{!! $terpenes !!}
					@endif
				@endif
				@if(isset($gallery->meta_value))
					<h2>Gallery</h2>
					@php
						$galleries = json_decode($gallery->meta_value);
					@endphp
					@if($galleries)
						<ul class="list-inline">
						@foreach($galleries as $gk=>$gallery)
						<li><img src="http:{{ $gallery->image }}" ></li>
						@endforeach
						</ul>
					@endif
				@endif
				@if(isset($variants->meta_value))
					<h2>Variants</h2>
					@php
						$variants = json_decode($variants->meta_value);
					@endphp
					@if($variants)
						<ul class="">
						@foreach($variants as $vk=>$variant)
						<li>{{ ($variant) }}</li>
						@endforeach
						</ul>
					@endif
				@endif
				<h2>Business Type</h2>
				<p>{{ $product->business_type=='2'?'Dispensary':'Government' }}</p>
			</div>    
		</div>
    </div>
	</div>
@endsection