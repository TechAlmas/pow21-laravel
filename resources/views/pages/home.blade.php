@extends('layouts.default')
@section('content')	



	<div class="content-wrap" ng-controller="homeController">

		<div class="container clearfix">

			<div class="col_full">				
				<h3>Compare local dispensaries, retailers and the open social market. Thousands of cannabis strain prices and brands in one place.</h3>
			</div>					

		</div>

		<div class="section nobottommargin converter-box">

			<div class="container-fullwidth clearfix nopadding nomargin">

				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="row" id="converter-alert" name="converterAlert" method="post" novalidate ng-submit="converterAlert.$valid && setPriceAlert('converter')">
					<div class="modal-dialog">
						<div class="modal-body">
							<div class="modal-content">								
								<div class="modal-header">
									<h4 class="modal-title" id="myModalLabel">Price Alert</h4>
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								</div>
								<div class="modal-body">
									

										<h4>@{{strName}} in @{{locName}}</h4>

										<div class="form-group">											
											<label for="converter-alert-name">Name </label>									
											<input type="text" id="converter-alert-name" name="converter-alert-name" class="sm-form-control" ng-model="converter_user_name" required="">
					  					</div>
					  					<div class="form-group">											
											<label for="converter-alert-email">Email </label>									
											<input type="email" id="converter-alert-email" name="converter-alert-email" class="sm-form-control" ng-model="converter_user_email" required="">
					  					</div>					  				

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-primary">Save changes</button>
								</div>
								
							</div>
						</div>
					</div>
					</form>
				</div>

	
				<div class="tabs clearfix" id="tab-3">
			
					<ul class="tab-nav tab-nav2 clearfix">
						<li><a href="#tabs-9" style="text-align: center;"><i class="icon-refresh norightmargin"></i>&nbsp;&nbsp;Cannabis Converter</a></li>
						<li class="last-item"><a href="#tabs-10"><i class="icon-bell norightmargin"></i>&nbsp;&nbsp;Cannabis Price Alerts</a></li>
					</ul>
				
					<div class="tab-container">
				
						<div class="tab-content clearfix" id="tabs-9">
							
							<div class="container">

								<h4 style="font-weight: bold;">Get real-time local market prices on marijuana, and the best deals in your neighbourhood. We track and compare over 2,000 strains from unlimited local sources.</h4>
								<form class="row" id="converter" name="converter" method="post" novalidate ng-submit="converter.$valid && getPriceData()">

									<div class="col-lg-3">
										<div class="form-group txtbg">
											<div class="wait-layer" ng-if="waitLayer"></div>
											<label for="converter-location">My Location</label>
											<select id="converter-location" name="converter-location" class="sm-form-control required" ng-model="selectedLocation.value" ng-change="changedLocation('converter')" required />
												<optgroup label="My City">
													<option ng-selected="{{loc.id == selectedLocation.value}}" ng-repeat="loc in my_city" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>
												<optgroup label="My State">
													<option ng-selected="{{state.id == selectedLocation.value}}" ng-repeat="state in my_state" ng-value="@{{state.id}}">@{{state.name}}</option>
												</optgroup>
												<optgroup label="My Country">
													<option ng-selected="{{country.id == selectedLocation.value}}" ng-repeat="country in my_country" ng-value="@{{country.id}}">@{{country.name}}</option>
												</optgroup>
												<optgroup label="Near By Cities">
													<option ng-selected="{{loc.id == selectedLocation.value}}" ng-repeat="loc in locations" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>
												<optgroup label="Other Cities">
													<option ng-selected="{{loc.id == selectedLocation.value}}" ng-repeat="loc in other_locations" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>
												<optgroup label="Rest Cities">
													<option ng-selected="{{loc.id == selectedLocation.value}}" ng-repeat="loc in rest_locations" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>
						  					</select>	
						  				</div>								
									</div>

									<div class="col-lg-3">
										<div class="form-group txtbg">
											<div class="wait-layer" ng-if="waitLayer"></div>
											<label for="converter-strn">My Strain </label>
											<select id="converter-strn" name="converter-strn" class="sm-form-control required" ng-model="selectedStrn.value" ng-change="changedStrains()" required />
												<option ng-selected="{{"0" == selectedStrn.value}}" ng-value="0">Marijuana</option>
												<!-- <optgroup label="-----"> -->
													<option ng-selected="{{strn.strain_id == selectedStrn.value}}" ng-repeat="strn in strains" ng-value="@{{strn.strain_id}}">@{{strn.name}}</option>
												<!-- </optgroup> -->
					  						</select>				  						
					  					</div>
									</div>

									<div class="col-lg-3">
										<div class="form-group txtbg">
											<div class="wait-layer" ng-if="waitLayer"></div>
											<label for="converter-mass">I want </label>									
											<select id="converter-mass" name="converter-mass" class="sm-form-control required"  ng-model="selectedMass.value" ng-change="" required />		
												<option ng-if="masses.length == 0" ng-selected="{{"1" == selectedMass.value}}" ng-value="1">1 Gram</option>	
												<option ng-selected="{{mass.mass_id == selectedMass.value}}" ng-repeat="mass in masses" ng-value="@{{mass.mass_id}}">@{{mass.name}}</option>
					  						</select>	
					  					</div>	
										
									</div>

									<div class="col-lg-3">	
										<div class="form-group">						
											<button class="button button-3d btn-box" type="submit" ng-disabled="waitLayer">Calculate Price</button>
										</div>
									</div>

								</form>

								<div class="container converter-ad-text">
									Add the world's most popular cannbis converter to your website for free. <a href="#">Click here.</a>
								</div>

								<div class="container converter-ad-placehoder">
									Advertising Placeholder
								</div>

								<div id="converter-result" ng-show="calculationBox">
									<div class="col-lg-12 result-item nopadding nomargin">	
										<div class="row nopadding nomargin">
											<div class="col-lg-6" style="padding:10px 20px 5px;">
												<h3 class="nomargin nopadding" style="font-size: 18px;">@{{strName}} in @{{locName}}</h3>
											</div>
											<div class="col-lg-6" style="padding:10px 20px 5px;">
												<div class="fbox-icon" style="float: right;font-size: 28px;">
													<a href="#"><i class="icon-plus-sign2"></i></a>
												</div>
												<div class="fbox-icon" style="float: right; font-size: 28px;margin-right: 20px;">
													<a href="#" ng-click="convertModel=true" data-toggle="modal" data-target="#myModal" ><i class="icon-bell"></i></a>
												</div>
											</div>
										</div>										
										<div class="row nopadding nomargin">
											<div class="col-lg-6 nomargin nopadding">
												<div class="res-left-data">
													<h4>Retail Market</h4>
													<span class="res-data col-lg-3">@{{massName}}</span>
													<span class="res-data col-lg-3"><i class="icon-arrow-right norightmargin"></i></span>
													<span class="res-data col-lg-3">$@{{avgPrice.avg_price | number : 2}}<br/>
														<i class="icon-arrow-up norightmargin avg-ind"></i> <sapn class="rate">$@{{avgPrice.differ_price | number : 2}}</sapn> <sapn class="percent">(+@{{avgPrice.differ_percent  | number : 0}}%)</sapn>
													</span>
													<span class="res-data col-lg-3" style="font-size: 13px;">H $@{{avgPrice.high_price | number : 2}}<br/>L $@{{avgPrice.low_price | number : 2}}</span>
												</div>
												<div class="res-left-data-bottom">
													<h4>Social Market (Recreational)</h4>
													<span class="res-data col-lg-4">@{{massName}}</span>
													<span class="res-data col-lg-4"><i class="icon-arrow-right norightmargin"></i></span>
													<span class="res-data col-lg-4">$@{{avgPrice | number : 2}}</span>
												</div>
											</div> 
											<div class="col-lg-6 res-right-data" style="border-left: 1px solid #D4D4D4;">
												
												<h4 style="font-size: 14px;">Find the best deal in @{{locName}}</h4>
												<div class="res-data-1 col-lg-12" ng-repeat="disp in dispData">
													<span class="res-data-1 col-lg-2">
														<a href="#">
															<img src="images/clients/3.png" style="width: 50px;" alt="Clients">
														</a>
													</span>
													<span class="res-data-1 col-lg-7" style="padding: 5px;">@{{disp.name}}</span>
													<span class="res-data-1 col-lg-3" style="padding: 5px;font-size: 16px;">$@{{disp.price | number : 2}}<br/>@{{massName}}</span>	
												</div>

												<span class="res-data-1 col-lg-12" style="text-align: center;"><a href="#" class="button button-3d button button-rounded button-dirtygreen">Compare more prices</a></span>										
											</div>
										</div>
										<div class="row nopadding nomargin">
											<div class="col-lg-6" style="padding: 20px;">
												<div class="feature-box fbox-small fbox-plain fbox-dark" style="float: left;width: 70%">
													<div class="fbox-icon">
														<a href="#"><i class="icon-book3"></i></a>
													</div>
													<h3>How much did you pay?</h3>
													<p>Share what you paid for marijuana and help others find the best deal in your area.</p>	
												</div>
												<a href="#" class="button button-3d button-mini button-rounded button-dirtygreen" style="float: right;">SignUp</a>
											</div> 
											<div class="col-lg-6" style="padding: 20px;">
												<div class="feature-box fbox-small fbox-plain fbox-dark" style="float: left;width: 70%">
													<div class="fbox-icon">
														<a href="#"><i class="icon-book3"></i></a>
													</div>
													<h3>Get the Best Price</h3>
													<p>Get notified when the price of your favorite strains changes or is on</p>
												</div>
												<a href="#" class="button button-3d button-mini button-rounded button-dirtygreen pull-right" style="float: right;">SignUp</a>
											</div>
										</div>
									</div>
								</div>					
								
							</div>
						</div>
						<div class="tab-content clearfix" id="tabs-10">
							<div class="container">	
								<h4 style="font-weight: bold;">Get notified in real-time when marijuana prices change in your neightbourhood.</h4>

								<form class="row" id="price-alert" name="priceAlert" method="post" novalidate ng-submit="priceAlert.$valid && setPriceAlert()">

									<div class="col-lg-3">
										<div class="form-group txtbg">
											<div class="wait-layer" ng-if="waitLayer"></div>
											<label for="price-alert-name">Name </label>									
											<input type="text" id="price-alert-name" name="price-alert-name" class="sm-form-control required"  ng-model="user_name" required />
					  					</div>	
										
									</div>

									<div class="col-lg-3">
										<div class="form-group txtbg">
											<div class="wait-layer" ng-if="waitLayer"></div>
											<label for="price-alert-email">Email Address </label>
											<input type="email" id="price-alert-email name="price-alert-email" class="sm-form-control required"  ng-model="user_email" required />				  						
					  					</div>
									</div>

									<div class="col-lg-3">
										<div class="form-group txtbg">
											
											<div class="wait-layer" ng-if="waitLayer"></div>
											<label for="price-alert-location">My Location</label>	

											<select id="price-alert-location" name="price-alert-location" class="sm-form-control required" ng-model="selectedLocationPriceAlert.value" ng-change="changedLocation('price-alert')" required />											

												<optgroup label="My City">
													<option ng-selected="{{loc.id == selectedLocationPriceAlert.value}}" ng-repeat="loc in my_city" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>
												<optgroup label="My State">
													<option ng-selected="{{state.id == selectedLocationPriceAlert.value}}" ng-repeat="state in my_state" ng-value="@{{state.id}}">@{{state.name}}</option>
												</optgroup>
												<optgroup label="My Country">
													<option ng-selected="{{country.id == selectedLocationPriceAlert.value}}" ng-repeat="country in my_country" ng-value="@{{country.id}}">@{{country.name}}</option>
												</optgroup>
												<optgroup label="Near By Cities">
													<option ng-selected="{{loc.id == selectedLocationPriceAlert.value}}" ng-repeat="loc in locations" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>
												<optgroup label="Other Cities">
													<option ng-selected="{{loc.id == selectedLocationPriceAlert.value}}" ng-repeat="loc in other_locations" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>
												<optgroup label="Rest Cities">
													<option ng-selected="{{loc.id == selectedLocationPriceAlert.value}}" ng-repeat="loc in rest_locations" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>	


						  					</select>	
						  				</div>								
									</div>

									<div class="col-lg-3">
										<div class="form-group txtbg">
											<div class="wait-layer" ng-if="waitLayer"></div>
											<label for="price-alert-strn">My Strain </label>
											<select id="price-alert-strn" name="price-alert-strn" class="sm-form-control required" ng-model="selectedStrnPriceAlert.value" required />

												<option ng-selected="{{"0" == selectedStrnPriceAlert.value}}" ng-value="0">Marijuana</option>
												<!-- <optgroup label="-----"> -->
													<option ng-selected="{{strn.strain_id == selectedStrnPriceAlert.value}}" ng-repeat="strn in strainsPriceAlert" ng-value="@{{strn.strain_id}}">@{{strn.name}}</option>
												<!-- </optgroup> -->
					  						</select>				  						
					  					</div>
									</div>

									<div class="col-lg-9">	
										<div class="feature-box fbox-plain" id="price-info-box">
											<div class="fbox-icon">
												<a href="#"><i style="color: #F2D100;" class="icon-play"></i></a>
											</div>
											<h3>Got a target price in mind?</h3>
											<p>Set a marijuana price based on a specific target price, and for any (or all) dispensary. For example, if OG Kush price drops to $5.50 a gram at ABC Dispensary or retailer, alert me...</p>
										</div>
									</div>

									<div class="col-lg-3">	
										<div class="form-group">						
											<button style="float: right;background: #0088CC" class="button button-3d button-xlarge button-rounded button-dirtygreen" type="submit">Set Price Alert</button>
										</div>
									</div>

								</form>
							</div>
						</div>
				
					</div>
				
				</div>

			</div>
		</div>		


		<!-- <div class="container clearfix">
		
			<div class="col_full">	
		
				<h3>Compare Canabis Price</h3>
				<h4>@{{my_city.name}}</h4>
		
				<div class="row" ng-repeat="data1 in compareData">
					<div class="col-lg-3">@{{data1.name}}</div>
					<div class="col-lg-3">$@{{data1.avg_price | number : 2}}</div>
					<div class="col-lg-3">
						<label ng-repeat="dis in data1.disp">@{{dis.name}} => @{{dis.price}}</label>
					</div>
					<div class="col-lg-3">@{{data1.state_avg_price | number : 2}}</div>
				</div>
		
			</div>					
		
		</div>	 -->

		<div class="container clearfix">
			<div class="col_full">
				<h3 style="text-align: center;">Find the strains that tailor to your desired mood</h3>
				<div class="row clearfix">
					<div class="col-lg-8" style="float: none;margin:0 auto;">
						I want to find&nbsp; <select style="width: 100px;" id="contextual-strain" name="contextual-strain" ng-model="selectedContextualLocation.value"><option ng-selected="{{strn.strain_id == selectedContextualLocation.value}}" ng-repeat="strn in strainsContextual" ng-value="@{{strn.strain_id}}">@{{strn.name}}</option></select> 
						
						At&nbsp; <select style="width: 200px;" id="contextual-location" name="contextual-location" class="" ng-model="selectedLocation.value" ng-change="changedLocation('converter')" required />
												<optgroup label="My City">
													<option ng-selected="{{loc.id == selectedLocation.value}}" ng-repeat="loc in my_city" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>
												<optgroup label="My State">
													<option ng-selected="{{state.id == selectedLocation.value}}" ng-repeat="state in my_state" ng-value="@{{state.id}}">@{{state.name}}</option>
												</optgroup>
												<optgroup label="My Country">
													<option ng-selected="{{country.id == selectedLocation.value}}" ng-repeat="country in my_country" ng-value="@{{country.id}}">@{{country.name}}</option>
												</optgroup>
												<optgroup label="Near By Cities">
													<option ng-selected="{{loc.id == selectedLocation.value}}" ng-repeat="loc in locations" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>
												<optgroup label="Other Cities">
													<option ng-selected="{{loc.id == selectedLocation.value}}" ng-repeat="loc in other_locations" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>
												<optgroup label="Rest Cities">
													<option ng-selected="{{loc.id == selectedLocation.value}}" ng-repeat="loc in rest_locations" ng-value="@{{loc.id}}">@{{loc.name}}</option>
												</optgroup>
						  					</select><br/><br/>
						  					that make me feel  <select style="width: 100px;" id="contextual-positive-1" name="contextual-positive-1" ng-model="selectedContextualPostive1.value">
						  						<option ng-selected="{{"0" == selectedContextualPostive1.value}}" ng-value="0">--Select--</option>
						  						<option ng-selected="{{strn.id == selectedContextualPostive1.value}}" ng-repeat="strn in postiveContextual1" ng-value="@{{strn.id}}">@{{strn.name}}</option>
						  					</select>&nbsp;&nbsp;  
						  					<select id="contextual-and-or-1" name="contextual-and-or-1" ng-model="selectedAndOR1.value">
						  						<option ng-selected="{{"1" == selectedAndOR1.value}}" ng-value="1">AND</option>
						  						<option ng-selected="{{"0" == selectedAndOR1.value}}" ng-value="0">OR</option>
						  					</select>
						  					&nbsp;&nbsp;creates the mood of&nbsp;&nbsp; <select style="width: 100px;" id="contextual-positive-1" name="contextual-positive-1" ng-model="selectedContextualPostive2.value">
						  						<option ng-selected="{{"0" == selectedContextualPostive2.value}}" ng-value="0">--Select--</option>
						  						<option ng-selected="{{strn.id == selectedContextualPostive2.value}}" ng-repeat="strn in postiveContextual2" ng-value="@{{strn.id}}">@{{strn.name}}</option>
						  					</select>&nbsp;&nbsp;<a href="#">Add More</a><br/><br/> 
						  					Negative Effect like<select style="width: 100px;" id="contextual-negative-1" name="contextual-negative-1" ng-model="selectedContextualNegative1.value">
						  						<option ng-selected="{{"0" == selectedContextualNegative1.value}}" ng-value="0">--Select--</option>
						  						<option ng-selected="{{strn.id == selectedContextualNegative1.value}}" ng-repeat="strn in negativeContextual1" ng-value="@{{strn.id}}">@{{strn.name}}</option>
						  					</select>
						  					&nbsp;&nbsp;  
						  					<select id="contextual-and-or-2" name="contextual-and-or-2" ng-model="selectedAndOR2.value">
						  						<option ng-selected="{{"1" == selectedAndOR2.value}}" ng-value="1">AND</option>
						  						<option ng-selected="{{"0" == selectedAndOR2.value}}" ng-value="0">OR</option>
						  					</select>
						  					Negative Effect like<select style="width: 100px;" id="contextual-negative-2" name="contextual-negative-2" ng-model="selectedContextualNegative2.value">
						  						<option ng-selected="{{"0" == selectedContextualNegative1.value}}" ng-value="0">--Select--</option>
						  						<option ng-selected="{{strn.id == selectedContextualNegative2.value}}" ng-repeat="strn in negativeContextual2" ng-value="@{{strn.id}}">@{{strn.name}}</option>
						  					</select>&nbsp;&nbsp;<a href="#">Add More</a>
					</div>
				</div>
			</div>
		</div>

	</div>

@stop

@section('scripts')

<style>
             
        form.ng-submitted select.ng-untouched.ng-invalid + .select2, form.ng-submitted select.ng-touched.ng-invalid + .select2{
                border: 1px solid red;
        }

        #price-alert.ng-submitted input.ng-invalid{border: 1px solid red;}
        #price-alert.ng-submitted select.ng-untouched.ng-invalid + .select2, #price-alert.ng-submitted select.ng-touched.ng-invalid + .select2{border: 1px solid red;}

        #converter-alert.ng-submitted input.ng-invalid{border: 1px solid red;}
        
        select.ng-touched.ng-valid + .select2 {
                border: 1px solid green;
        } 

        #select2-converter-strn-results li:first-child{border-bottom: 1px solid #e8e8e8; padding-top: 10px; padding-bottom: 10px;  }
        
    </style>

<script type="text/javascript">
	'use strict';
	var token = '{!! $token !!}';
	var time = '{!!  $time !!}';
</script>

<!-- AngularJS Application Scripts -->
<script src="{{ asset('app/lib/angular/angular.min.js')}}"></script>
<script src="<?= asset('app/app.js') ?>"></script>
<script src="<?= asset('app/controllers/home.js') ?>"></script>

<script type="text/javascript">
	// Multiple Select
	$("#converter-mass").select2();

	$("#converter-strn").select2();

	$("#converter-location").select2();

	$("#price-alert-strn").select2();

	$("#price-alert-location").select2();
	

</script>

@stop