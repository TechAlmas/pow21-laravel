<!DOCTYPE html >
<html dir="ltr" lang="{{ app()->getLocale() }}" ng-app="mioApp">
<head>

	@include('includes.head')

</head>

<body class="stretched device-xl">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">
		
		<div id="top-bar" class="d-none d-md-block">
			@include('includes.topbar')
		</div>

		<!-- Header
		============================================= -->
		<header id="header">

			@include('includes.header')

		</header><!-- #header end -->

		
		@yield('content')

		<!-- Footer
		============================================= -->
		<footer id="footer" class="dark">

			 @include('includes.footer')

		</footer><!-- #footer end -->

	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="icon-angle-up"></div>

	@include('includes.foot')

	@yield('scripts');

</body>
</html>