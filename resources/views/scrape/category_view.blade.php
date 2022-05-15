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
				<strong>{{ __('Category Details') }}</strong>
			</div>
			<div class="panel-body">  
				<div class="box-body" id="parent-form-area">
					<div class="table-responsive">
						<table id="table-detail" class="table table-striped">
							<tbody>
								<tr>
									<th>Category</th>
									<td>{{ $category->name }}</td>
								</tr>
								<tr>
									<th>Product Types</th>
									<td>
										@if($product_types)
											{{ implode(',',$product_types) }}
										@endif
									</td>
								</tr>
							</tbody>
						</table>
					</div>  
				</div>
			</div>
		</div>
	</div>
@endsection