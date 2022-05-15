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
								    <a href="{{asset('/uploads/1/2019-02/logo_default_dispensary.png')}}">
									   <img src="{{ asset('/uploads/1/2019-02/logo_default_dispensary.png') }}" height="100" width="100">
									</a>
								    <img src="{{ storage_path('/static/admin/storage/app/uploads/claims/6488-2.png') }}" height="100" width="100"></td>
								
								<!--<td>{{ $row->files}}</td>-->
							</tr>
							<tr>
								<td><b>Status</b></td>
								<td>{{ $row->status}}</td>
							</tr>
						</tbody>
					</table>
				</div>
            </div>
        </div>	
  </div>
@endsection