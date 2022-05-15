<!-- First, extends to the CRUDBooster Layout -->
@extends("crudbooster::admin_template")
@section("content")
  <!-- Your html goes here -->
  <div>	
	  <p><a title="Return" href= "{{ url('/admin/missing-strains') }}"><i class="fa fa-chevron-circle-left "></i>&nbsp; Back To List Data Master Missing Strains</a></p>

	  <div class='panel panel-default'>

	  	<div class="panel-heading">
            <strong><i class="fa fa-th-list"></i> Add strain to master</strong>
        </div>	   
	    <div class='panel-body' style="padding: 20px 0px 0px 0px;">
	      <form class="form-horizontal" method='post' action='{{CRUDBooster::mainpath("save-to-master")}}'>
	      	<input type='hidden' name='raw_id' value="{{$raw_id}}"  />
	        {{ csrf_field() }}
	        <div class="box-body" id="parent-form-area">
		        <div class='form-group header-group-0 '>
		          <label class="control-label col-sm-2">Strain Name</label>
		          <div class="col-sm-10">
		          		<input type='text' name='name' value="{{$name}}" class='form-control'/>
		          </div>		          
		        </div>  
		        <div class='form-group header-group-0 '>
		          <label class="control-label col-sm-2">Keyword(s)</label>
		          <div class="col-sm-10">
		          		<input type='text' name='keywords' value="{{$name}}" class='form-control'/>
		          </div>		          
		        </div>  
		        <div class='form-group header-group-0 '>
		          <label class="control-label col-sm-2">Master Strain</label>
		          <div class="col-sm-10">
		          		<select class="form-control" name="own_id" id="own_id">
		          				<option value="0"></option>
		          			@foreach ($master_strains as $stn)
		          				<option value="{{$stn->id}}">{{$stn->name}}</option>
		          			@endforeach
		          		</select>
		          </div>		          
		        </div>    
		       
		     </div>   
	        <!-- etc .... -->

	        <div class='box-footer' style="background: #F5F5F5;">
		        <div class='form-group'>
			       <label class="control-label col-sm-2"></label>
			       <div class="col-sm-10">
		          		<input type='submit' class='btn btn-success' value='Add to Master'/>
		          	</div>
		         </div>
	        </div>	        
	      </form>
	    </div>	    
	  </div>
</div>
<div>
	<p>Note: Assigning the 3rd party raw strain data to the master strain ID or Create New strain with 3rd party raw strain name<br/> 
1. Assign the master strain ID to the new 3rd party raw data strain ID to create a relationship, if not selected then it will create a new strain with defined name<br/> 
2. The process assigns the 3rd party raw strain name/label as keyword associated to the master strain record<br/>
3. The Dispensary and Brand process runs to check the dispensary (and brand) ID of the 3rd party raw strain data. If dispensary (or brand) does not exist in the database, then we create a new dispensary (or brand) record in the master tables</p>
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
		$('#own_id').select2();
	})
</script>
@endpush
@endsection



