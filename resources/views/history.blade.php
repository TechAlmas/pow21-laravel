<?php

//Loading Assets
$asset_already = [];
foreach($forms as $form) {

$type = @$form['type'] ?: 'text';

if (in_array($type, $asset_already)) continue;

?>
@if(file_exists(base_path('/vendor/crocodicstudio/crudbooster/src/views/default/type_components/'.$type.'/asset.blade.php')))
    @include('crudbooster::default.type_components.'.$type.'.asset')
@elseif(file_exists(resource_path('views/vendor/crudbooster/type_components/'.$type.'/asset.blade.php')))
    @include('vendor.crudbooster.type_components.'.$type.'.asset')
@endif
<?php
$asset_already[] = $type;
} //end forms
?>

@push('head')
    <style type="text/css">
        #table-detail tr:nth-child(odd) {
            /*font-weight: bold;*/
            width: 25%;
        }
        tbody:nth-child(odd) {
            background: #ffffff;
        }


    </style>
@endpush
<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@section('content')
<div class="row">
    <div class="col-sm-3">
<select name="country" class="form-control">
    <option>Select Country</option>
    <option value="ca">Canada</option>
    <option value="us">US</option>
</select>
</div>
 <div class="col-sm-3">
<select name="state" class="form-control">
    <option>Select Location (Provience/State OR City)</option>
    <option value="ca">state1</option>
    <option value="us">state2</option>
</select>
</div>
 <div class="col-sm-3">
<select name="city" class="form-control">
     <option>Select Strain</option>
    <option value="ca">100 OG</option>
    <option value="us">100 OG</option>
</select>
</div>
 <div class="col-sm-3">
<select name="mass" class="form-control">
    
    <option value="1">1 Gram</option>
    <option value="2">2 Grams</option>
    <option value="3">3 Grams</option>
    <option value="4">4 Grams</option>
    <option value="5">5 Grams</option>
    <option value="6">Eighth</option>
    <option value="7">Quarter</option>
    <option value="8">Half Ounce</option>
    <option value="9">Ounce</option>
</select>
</div>
</div>
<br/>
<div class='table-responsive'>
    <table id='table-detail' class='table'>
        <tbody>
        <tr>
            <th>Strain Name</th>
            <th>Date</th>
            <th>Avg Price</th>
            <th>Heigh Price</th>
            <th>Low Price</th>
            <th>Differ Price</th>
            <th>Differ Percent</th>

        </tr>
        </tbody>
        <tbody>
        <tr>
            <td rowspan="2">8 ball kush</td>
            <td>Apr 2019</td>
            <td>16.00</td>
            <td>16.00</td>
            <td>16.00</td>
            <td>1</td>
            <td>0</td>
           

        </tr>
         <tr>
          <td>Jan 2019</td>
            <td>16.00</td>
            <td>16.00</td>
            <td>16.00</td>
            <td>1</td>
            <td>0</td>
        </tr>
        
        
        </tbody>
        <tbody>
        
         <tr>
            <td rowspan="2">707 Headband</td>
            <td>Apr 2019</td>
            <td>17.00</td>
            <td>17.00</td>
            <td>17.00</td>
            <td>1</td>
            <td>0</td>
             
        </tr>
        <tr>
          <td>Jan 2019</td>
            <td>17.00</td>
            <td>17.00</td>
            <td>17.00</td>
            <td>1</td>
            <td>0</td>
        </tr>
       
    </tbody>
     <tbody>
        
         <tr>
            <td rowspan="2">707 Headband</td>
            <td>Apr 2019</td>
            <td>17.00</td>
            <td>17.00</td>
            <td>17.00</td>
            <td>1</td>
            <td>0</td>
             
        </tr>
        <tr>
          <td>Jan 2019</td>
            <td>17.00</td>
            <td>17.00</td>
            <td>17.00</td>
            <td>1</td>
            <td>0</td>
        </tr>
       
    </tbody>
    </table>
@endsection