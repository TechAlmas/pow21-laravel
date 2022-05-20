@extends('crudbooster::admin_template')
@section('content')

  <div>
        <p><a title="Return" href= "{{ url('/admin/claim_listings') }}"><i class="fa fa-chevron-circle-left "></i>&nbsp; Back To List Data Claim Listings</a></p>

        <div class='panel panel-default'>
            <div class="panel-heading">
                <strong><i class="fa fa-th-list"></i>Detail Claim Listings</strong>
            </div>
            <div class='box-body' id="parent-form-area">   
				<div class="table-responsive">
					<table id="table-detail" class="table table-striped">
						<tbody>
						
							<tr>
								<td><b>Id</b></td>
								<td>{{ $row->id}}</td>
							</tr>
							<tr>
								<td><b>First Name</b></td>
								<td>{{ $row->first_name}}</td>
							</tr>
							<tr>
								<td><b>Last Name</b></td>
								<td>{{ $row->last_name}}</td>
							</tr>
							<tr>
								<td><b>Telephone</b></td>
								<td>{{ $row->telephone}}</td>
							</tr>
							<tr>
								<td><b>E Mail</b></td>
								<td>{{ $row->e_mail}}</td>
							</tr>
							<tr>
								<td><b>Verification Details</b></td>
								<td>{{ $row->verification_details}}</td>
							</tr>
							<tr>
								<td><b>Files</b></td>
								<td>
									@if(!empty($file))
									@foreach($file as $fileVal)
									@if(!empty($fileVal))
									<div class="image-view">

										<a href="{{asset('/uploads/claims/').'/'.$fileVal}}" class="mr-3" target="_blank">
										   {{$fileVal}}
										</a>
									</div>
									@endif
									@endforeach
									@else
									<span class="text-center">No Files Uploaded</span>
									@endif
								    <!-- <a href="{{asset('/uploads/1/2019-02/logo_default_dispensary.png')}}" target="_blank">
									   <img src="{{ asset('/uploads/1/2019-02/logo_default_dispensary.png') }}" height="100" width="100">
									</a>
								    <img src="{{ storage_path('/static/admin/storage/app/uploads/claims/6488-2.png') }}" height="100" width="100"></td> -->
								
								<!--<td>{{ $row->files}}</td>-->
							</tr>
							<tr>
								<td><b>Status</b></td>
								<td>{{ $row->status}}</td>
							</tr>
							<tr>
							@if(!empty($row->status) && $row->status == 'Pending')
								<td><b>Action</b></td>
								<td>
									<a class="btn btn-xs btn-success" title="Approve" onclick="
									swal({   
										title: &quot;Confirmation&quot;,
										text: &quot;Are you sure want to do this action?&quot;,
										type: &quot;warning&quot;,
										showCancelButton: true,
										confirmButtonColor: &quot;#DD6B55&quot;,
										confirmButtonText: &quot;Yes!&quot;,
										cancelButtonText: &quot;No&quot;,
										closeOnConfirm: true, }, 
										function(){  location.href='{{\CRUDBooster::mainpath('change-status/approve/').$row->id}}'});        

									" href="javascript:;"><i class=""></i> Approve</a>
									<a class="btn btn-xs btn-danger" title="Reject" onclick="
										swal({   
											title: &quot;Confirmation&quot;,
											text: &quot;Are you sure want to do this action?&quot;,
											type: &quot;warning&quot;,
											showCancelButton: true,
											confirmButtonColor: &quot;#DD6B55&quot;,
											confirmButtonText: &quot;Yes!&quot;,
											cancelButtonText: &quot;No&quot;,
											closeOnConfirm: true, }, 
											function(){  location.href='{{\CRUDBooster::mainpath('change-status/reject/').$row->id}}'});        

										" href="javascript:;"><i class=""></i> Reject</a>
								</td>
								
							@endif
							</tr>
						</tbody>
					</table>
				</div>
            </div>
        </div>	
  </div>
@endsection