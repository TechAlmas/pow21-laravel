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

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><i class='{{CRUDBooster::getCurrentModule()->icon}}'></i> {!! $page_title or "Page Title" !!}</strong>
            </div>

            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <?php
                $action = (@$row) ? CRUDBooster::mainpath("edit-save/$row->id") : CRUDBooster::mainpath("add-save");
                $return_url = ($return_url) ?: g('return_url');
                ?>
                <form class='form-horizontal' method='post' id="form" enctype="multipart/form-data" action='{{$action}}'>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type='hidden' name='return_url' value='{{ @$return_url }}'/>
                    <input type='hidden' name='ref_mainpath' value='{{ CRUDBooster::mainpath() }}'/>
                    <input type='hidden' name='ref_parameter' value='{{urldecode(http_build_query(@$_GET))}}'/>
                    @if($hide_form)
                        <input type="hidden" name="hide_form" value='{!! serialize($hide_form) !!}'>
                    @endif
                    <div class="box-body" id="parent-form-area">

                        @if($command == 'detail')
                            @include("crudbooster::default.form_detail")
                        @else
                            @include("crudbooster::default.form_body")
                        @endif
						
						<div class="form-group header-group-0" id="form-group-pricedetail">
							<div class="col-sm-12">
								<div id="panel-form-attributedetail" class="panel panel-default">
									<div class="panel-heading">
										<i class="fa fa-bars"></i> Pricing Detail
									</div>
									
									<div class="panel-body">
										<div class="panel panel-default">
											<div class="panel-body no-padding table-responsive" style="max-height: 400px;overflow: auto;">
												<table id="table-attributedetail" class="table table-striped table-bordered">
													<thead>
													<tr>
														<th>Date</th>
														<th>Price</th>
														<th>Weight</th>
														<th>Sources</th>
														<th>Locations</th>
													</tr>
													</thead>
													<tbody>
														@if($pricings_data)
															@foreach($pricings_data as $pkey=>$pricing)
															<tr class="trNull">
																<td>{{  Carbon\Carbon::parse($pricing['created_at'] )->format('M d, Y') }}</td>
																<td>${{ $pricing['price'] }}</td>
																<td>{{ $pricing['mass'] }}</td>
																<td>{{ $pricing['source_count'] }} Sources</td>
																<td>{{ $pricing['location_count'] }} Locations</td>
															</tr>
															@endforeach
														@endif
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<!-- /.box-body -->
								</div>
							</div>
						</div>
						
						<div class="form-group header-group-0" id="form-group-pricedetail">
							<div class="col-sm-12">
								<div id="panel-form-attributedetail" class="panel panel-default">
									<div class="panel-heading">
										<i class="fa fa-bars"></i> Store Pricing Detail
									</div>
									
									<div class="panel-body">
										<div class="panel panel-default">
											<div class="panel-body no-padding table-responsive" style="max-height: 400px;overflow: auto;">
												<table id="table-attributedetail" class="table table-striped table-bordered">
													<thead>
													<tr>
														<th>Date</th>
														<th>Store</th>
														<th>Price</th>
														<th>Weight</th>
														<th>City</th>
														<th>Prov/State</th>
														<th>Country</th>
													</tr>
													</thead>
													<tbody>
														@if($pricings_data)
															@foreach($pricings_data as $pkey=>$pricing)
															<tr class="trNull">
																<td>{{  Carbon\Carbon::parse($pricing['created_at'] )->format('M d, Y') }}</td>
																<td>{{ $pricing['dis'] }} </td>
																<td>${{ $pricing['price'] }}</td>
																<td>{{ $pricing['mass'] }}</td>
																<td>{{ $pricing['city'] }}</td>
																<td>{{ $pricing['state'] }} </td>
																<td>{{ $pricing['country'] }}</td>
															</tr>
															@endforeach
														@endif
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<!-- /.box-body -->
								</div>
							</div>
						</div>
                    </div><!-- /.box-body -->

                    <div class="box-footer" style="background: #F5F5F5">

                        <div class="form-group">
                            <label class="control-label col-sm-2"></label>
                            <div class="col-sm-10">
                                @if($button_cancel && CRUDBooster::getCurrentMethod() != 'getDetail')
                                    @if(g('return_url'))
                                        <a href='{{g("return_url")}}' class='btn btn-default'><i
                                                    class='fa fa-chevron-circle-left'></i> {{trans("crudbooster.button_back")}}</a>
                                    @else
                                        <a href='{{CRUDBooster::mainpath("?".http_build_query(@$_GET)) }}' class='btn btn-default'><i
                                                    class='fa fa-chevron-circle-left'></i> {{trans("crudbooster.button_back")}}</a>
                                    @endif
                                @endif
                                @if(CRUDBooster::isCreate() || CRUDBooster::isUpdate())

                                    @if(CRUDBooster::isCreate() && $button_addmore==TRUE && $command == 'add')
                                        <input type="submit" name="submit" value='{{trans("crudbooster.button_save_more")}}' class='btn btn-success'>
                                    @endif

                                    @if($button_save && $command != 'detail')
                                        <input type="submit" name="submit" value='{{trans("crudbooster.button_save")}}' class='btn btn-success'>
                                    @endif

                                @endif
                            </div>
                        </div>


                    </div><!-- /.box-footer-->

                </form>

            </div>
        </div>
    </div><!--END AUTO MARGIN-->

@endsection