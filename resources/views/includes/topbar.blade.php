<div class="container clearfix">

<div class="col_half nobottommargin">

	<p class="nobottommargin"><strong>Call:</strong> 1800-547-2145 | <strong>Email:</strong> info@canvas.com</p>

</div>

<div class="col_half col_last fright nobottommargin">

	<!-- Top Links
	============================================= -->
	<div class="top-links">
		<ul>
			<li><a href="#">USD</a>
				<ul>
					<li><a href="#">EUR</a></li>
					<li><a href="#">AUD</a></li>
					<li><a href="#">GBP</a></li>
				</ul>
			</li>
			<li><a href="#">EN</a>
				<ul>
					<li><a href="#"><img src="{{ asset('images/icons/flags/french.png')}}" alt="French"> FR</a></li>
					<li><a href="#"><img src="{{ asset('images/icons/flags/italian.png')}}" alt="Italian"> IT</a></li>
					<li><a href="#"><img src="{{ asset('images/icons/flags/german.png')}}" alt="German"> DE</a></li>
				</ul>
			</li>
			
				@guest
					<li><a href="{{ route('login') }}">Login</a></li>
				@else
					<li><a href="#">{{ Auth::user()->name }}</a>
						<ul>
							<li><a href="#">My Account</a></li>
							<li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
						</ul>
					</li>
					 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
				@endguest
			
		</ul>
	</div><!-- .top-links end -->

</div>

</div>