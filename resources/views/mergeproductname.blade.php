<!-- First, extends to the CRUDBooster Layout -->
@extends("crudbooster::admin_template")
@section("content")
  <!-- Your html goes here -->
  <div>	
	  <p><a title="Return" href="{{CRUDBooster::mainpath()}}"><i class="fa fa-chevron-circle-left "></i>&nbsp; Back To List Data Master Missing Product Names</a></p>

	  <div class='panel panel-default'>

	  	<div class="panel-heading">
            <strong><i class="fa fa-th-list"></i> Merge Product Names</strong>
        </div>	   
	    <div class='panel-body' style="padding: 20px 0px 0px 0px;">
	      <form class="form-horizontal" method='post' action='{{CRUDBooster::mainpath("save-to-master-brand")}}'>
	      	<input type='hidden' name='id' value="{{$id}}"  />
	      	<input type='hidden' name='source' value="{{$source}}"  />
	        {{ csrf_field() }}
	        <div class="box-body" id="parent-form-area">
		        <div class='form-group header-group-0 '>
		          <label class="control-label col-sm-2">Name</label>
		          <div class="col-sm-10">
		          		<input type='text' name='name' value="{{$name}}" class='form-control'/>
		          </div>		          
		        </div>  
		        <!-- <div class='form-group header-group-0 '>
		          <label class="control-label col-sm-2">Source</label>
		          <div class="col-sm-10">
		          		<input type='text' name='keywords' value="{{$source}}" class='form-control'/>
		          </div>		          
		        </div>   -->
		        <div class='form-group header-group-0 '>
		          <label class="control-label col-sm-2">Merge to Product Name</label>
		          <div class="col-sm-10">
		          		<select class="form-control" name="own_id" id="own_id" required="required">
		          				<option value="0"></option>
		          			@foreach ($product_names as $stn)
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