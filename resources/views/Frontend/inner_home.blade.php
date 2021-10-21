@extends('layouts.appnew')

@section('content')
   
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-6263923552826378",
          enable_page_level_ads: true
     });
</script>	
	
<!--slider-start-->
<div class="banner-slider-outer">
	   <div class="container">
         <div class="owl-carousel banner-silder">
		 @if (!empty($slider))		 
			@foreach($slider as $slide)	
            <div class="item">
            	<div class="d-flex align-items-center slider-caption"> 
				@if($slide['image'])
                  <div class="img-block">
                  	<img src="/uploads/logo/{{ $slide['image'] }}" alt="banner">
                  </div>
				@endif  
                  <div class="content-block">
                  	<h1>{{ $slide['title'] }}</h1>
                  	<p>{{ $slide['description'] }}</p>
					@if($slide['button_text'])  
                  	<div class="btn-outer">
                  		<a  href="{{ $slide['button_link'] }}" class="view-btn">{{ $slide['button_text'] }}</a>
                  	</div>
					@endif  
                  </div>
            	</div>
             </div>
			@endforeach 
			@endif
           
           </div>

       </div>
</div>    
<!--slider-section-end-->

<div class="ptb home-block-01">
	<div class="container">
		<div class="main-heading">
			<h2>how it <strong>works</strong></h2>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="card">
	              <div class="img-block"><img class="card-img" src="images/icon-01.png" alt="icon-01"></div>
	              <div class="card-body">
	                 <div class="card-title">Select your package</div>
	                  <p>Choose from monthly, yearly payments. Even host on your own domain if you choose.</p>
	             </div>
	            </div>
			</div>
			<div class="col-md-4">
				<div class="card">
	              <div class="img-block"> <img class="card-img" src="images/icon-02.png" alt="icon-01"/></div>
	              <div class="card-body">
	                 <div class="card-title">Enter your basic details</div>
	                  <p>Fill in your company details which prefills your file panel. </p>
	             </div>
	            </div>
			</div>
			<div class="col-md-4">
				<div class="card">
	              <div class="img-block"><img class="card-img" src="images/icon-03.png" alt="icon-03"></div>
	              <div class="card-body">
	                 <div class="card-title">Pay</div>
	                  <p>Make your payment and you are up and running in minutes</p>
	             </div>
	            </div>
			</div>
		</div>
	</div>
</div>
<!--home-block-01-end-->

<div class="ptb home-block-02">
	<div class="container">
		<div class="main-heading">
			<h2><strong>Core</strong> Features</h2>
			<h3>Designed for Remapping Resellers</h3> 
			<p>We consulted remapping masters with active slave networks and file service requirements and built this portal specifically to help the day to day processing of jobs from remapping professionals</p> 
		</div>
		<div class="d-flex home-block-02-content">
			<div class="box">
				<div class="card bg-blue">
	              <div class="img-block"><img class="card-img" src="images/icon-04.png" alt="icon-03"></div>
	              <div class="card-body">
	                 <div class="card-title">Customisable</div>
						<ul>		
						 <li>Email notifications</li>
					 	<li>Choose your currency</li>
						 <li>Colour themes & logo upload</li> 
						 <li>Customisable Email Delivery</li> 
						 <li>Even Host on your own domain*</li> 
						 <li>Mobile Friendly</li> 
						</ul>    
	             </div>
	            </div>
			</div>
			<div class="box">
				<div class="card bg-dark">
	              <div class="img-block"><img class="card-img" src="images/icon-05.png" alt="icon-03"></div>
	              <div class="card-body">
	                 <div class="card-title">Billing</div>
	                  <ul>		
						 <li>No "per file charges"</li> 
						 <li>PAYG - No contract </li> 
						 <li>Automatic client invoicing</li> 
						 <li>VAT or not</li> 
						 <li>PayPal Payment Handling</li> 
						 <li>Easy Reporting</li> 
					  </ul> 
	             </div>
	            </div>
			</div>
			<div class="box">
				<div class="card bg-dark">
	              <div class="img-block"><img class="card-img" src="images/icon-06.png" alt="icon-03"></div>
	              <div class="card-body">
	                 <div class="card-title">Support</div>
	                  <ul>		
						 <li>Built in ticket support</li> 
						 <li>Chat screen</li> 
						 <li>File attaching</li> 
						 <li>Mobile Support </li>
					  </ul> 
	             </div>
	            </div>
			</div>
			<div class="box">
				<div class="card bg-blue">
	              <div class="img-block"><img class="card-img" src="images/icon-07.png" alt="icon-03"></div>
	              <div class="card-body">
	                 <div class="card-title">Security</div>
	                  <ul>		
						 <li>Secure SSL Encryption</li> 
						 <li>All files are encrypted</li> 
						 <li>2gb of storage included</li> 
						 <li>Extra storage available</li>
					  </ul> 
	             </div>
	            </div>
			</div>
		</div>
	</div>
</div>
<!--home-block-02-end-->

<div class="ptb home-block-03">
	<div class="container">
		<div class="main-heading">
			<h2>MY REMAPS file service portal<strong>Pricing Plans</strong></h2>
			<p</p>
		</div>
		<div class="row">
			
			@if(!empty($packages))
				@php 
					$totlPackages = count($packages);
					$classDiv = 'col-md-4';
					if($totlPackages%2 ==0){
						$classDiv = 'col-md-6';
					}
				@endphp
				@foreach($packages as $val)
					<div class="@php echo $classDiv @endphp">
						<div class="box border-gray">
						  <div class="img-block"><img class="card-img" src="images/icon-10.png" alt="icon-10"></div>
						  <div class="title">@php echo $val['name'];@endphp</div>
						  <div class="price">@php echo $val['amount'];@endphp</div>
						  	@php echo $val['description'];@endphp
							<a href="/register-account?domain=@php echo strpos($val['name'], 'own') !== false ? 'own' : 'regular';@endphp" class="view-btn">GET STARTED</a>
						  </div>	
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>
<!--home-block-03-end-->

<div class="ptb home-block-04">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="main-heading">
			      <h2><strong>Seamless Workflow & Efficient File Management</strong></h2>
			       <p>Increase your daily file throughput. Email notifications for both the admin and client. built in ticket support with file attachments ensure files are processed quickly and efficiently.</p>
		         </div>
			</div>
			<div class="col-md-6">
				<div class="home-block-04-right">
			      	<div class="home-block-04-right-inner">
					<div class="box box-01">
						<div class="d-flex justify-content-center">
					     <div class="img-block"><img src="images/img-11.jpg" alt="img-11"/></div>
					   	 </div>
					    <div class="title">Company</div>
					    <p>The Cloud file storage, email notifications and invoicing now make our daily life easy. </p>
					</div>
					<div class="box box-02">
						<div class="d-flex justify-content-center">
					     <div class="img-block"><img src="images/img-12.jpg" alt="img-12"/></div>
					   	 </div>
					    <div class="title">Customers</div>
					    <p>I upload my file and get an email when its ready. All my files are neatly stored, its perfect. </p>
					</div>
				   </div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				<a href="#" class="view-btn">learn more</a>
			</div>
		</div>
	</div>
</div>

<!------
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-6263923552826378",
          enable_page_level_ads: true
     });
</script>



<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:inline-block;width:700px;height:150px"
     data-ad-client="ca-pub-6263923552826378"
     data-ad-slot="1478858190"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script> ------->
	
@endsection