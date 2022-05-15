<!DOCTYPE html >
<html dir="ltr" lang="{{ app()->getLocale() }}" ng-app="mioApp">
<head>

    @include('includes.head')

</head>

<body class="stretched device-xl">

    <!-- Document Wrapper
    ============================================= -->
    <div id="wrapper" class="clearfix"> 

    <section id="content">       
        @yield('content')       
    </section>

    </div><!-- #wrapper end -->   

    @include('includes.foot')

</body>
</html>