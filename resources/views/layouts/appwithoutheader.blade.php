<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>Welcome Myremap</title>
    <!-- Bootstrap CSS -->
   {{-----<link href="{{ URL::asset('css/font-awesome.min.css') }}" rel="stylesheet">-----------}}
	  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
     <link rel="stylesheet" href="{{ URL::asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/responsive.css') }}">
   <!-- js-->
  
<!---------------google  analytics code ------------------>
	@include('layouts/googleanalytics')
	@include('layouts/googleaddsense')
<!--------------- // google analytics code ------------------>
	  
	  <!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-149001991-1"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-149001991-1');
		</script>
	  
 </head>
  
  
<body>


 @yield('content')
 
 
  <footer class="footer">
	<div class="container text-center">
		<p>Copyright © 2019 My Remaps. All Rights Reserved</p>
		<ul class="social">
		 <li><a href="https://www.facebook.com/remappingfileportal" target="_blank"><i class="fa fa-facebook-f"></i></a></li>
		 <li><a href="mailto:sales@myremaps.com"><i class="fa fa-envelope"></i></a></li>
		</ul>
	</div>
</footer>


  <script src="{{ URL::asset('js/jquery-3.2.1.min.js') }}"></script>
  <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ URL::asset('js/popper.min.js') }}"></script>
  <script src="{{ URL::asset('js/owl.carousel.min.js') }}"></script>

<script>
    $(document).ready(function() {
		var owl = $('.banner-silder');
		owl.owlCarousel({
			items:1,
			loop:true,
			autoplayTimeout:5000,
			autoplayHoverPause:true,
			margin:0,
			autoplay:true,
			responsive: {
						  0: {
							items: 1
						  },
						  600: {
							items: 1
						  },
						  1000: {
							items: 1
						  }
						}
		});
      
		$('#domain_prefix').keyup(function(){
			var domain_prefix = $(this).val();
			domain_prefix = domain_prefix.replace(/\s/g, '');
			var domain_link = domain_prefix + '.myremaps.com';
			$("#domain_link").val( $.trim(domain_link) );
		});
		
    });
</script>

</html>
