@extends('layouts.appnew')

@section('content')
	<!--slider-start-->
		<div class="banner-slider-outer">
		   <div class="container">
			 <div class="banner-inst-text text-center">
			   <h1>Compare Prices</h1>
			   <p>Looking for a High Quality Remapping file supplier? Look no further. Whether you need a Stage 1, EGR Delete or even a DPF off, simply browse the selected companies below. The more info button also shows basic information about the capabilities of each company.  Once you have chosen, click visit to work with your selected company.</p>
				 <br>
				 <h3 style="color:#fff;"> Simple - Smart - Fast </h3>
			 </div>
		   </div>
		</div>

<style>
	.sorticon{
		margin-top:-15px;
	}
</style>
	<!--slider-section-end-->
    <div class="client-table-outer">
		<div class="container">
			<div class="client-table">
				<div class="table-responsive">
					<table class="table table-bordered text-center">
						<tr>
							<th>
								Name

							</th>
							<th>Logo</th>
							<th>Tuning Credits</th>
							<!---<th>Subscription period</th>---->
							<th>More info</th>
							<th>
								Rating
								@php
									$url = "compare-prices?keyword=rating&sort=ASC" ;
								@endphp
								<!----<a href="{{url($url)}}">
									<i class="fa fa-sort-up" aria-hidden="true"></i>
								</a>
								<div class="sorticon"></div>
								<a href="{{url($url)}}">
									<i class="fa fa-sort-down" aria-hidden="true"></i>
								</a>----->

							</th>
							<th>Visit</th>
						</tr>

						@php
								$i=1;

						@endphp
						@foreach($company as $val)
							@php
								$childTable = $val['tuning_credit_groups'];
								$j =0;
								$datas =[];
								foreach($childTable as $childs):
									foreach($childs['tuning_credit_tires'] as $pivot){
										if($childs['set_default_tier'] ==1){

											$datas[$j] = array(
												'from_credit'=>$pivot['pivot']['from_credit'],
												'for_credit'=>$pivot['pivot']['for_credit'],
												'amount'=>$pivot['amount']
											);

											$j++;
										}
									}
								endforeach;
						$from = $for ='';
						if(!empty($datas)){
								$maxAmount = max(array_column($datas, 'amount'));
								$minAmount = min(array_column($datas, 'amount'));

								foreach($datas as $vals){
									if($vals['amount'] == $minAmount){
										$from =  min($vals['from_credit'],$vals['for_credit']);
										$from = $minAmount == 0 ? 0 : $from/$minAmount;
									}
									if($vals['amount'] == $maxAmount){
										$for =  min($vals['from_credit'],$vals['for_credit']);
										$for = $maxAmount == 0 ? 0 :$for/$maxAmount;
									}
								}
						}
								/*
									$max1 = max(array_column($datas, 'from_credit'));
									$max = $max2 = max(array_column($datas, 'for_credit'));
									if($max1 >$max2){
										$max = $max1;
									}

									$min1 = min(array_column($datas, 'from_credit'));
									$min = $min2 = min(array_column($datas, 'for_credit'));
								*/
						@endphp
								<?php
									/*if($min1 <= $min2):
										$min = $min1;
									endif;*/
								?>
								@php
									$logo = 'uploads/logo/'.$val['logo'];
									$link =$val['domain_link'];
								@endphp
								<tr>
									<td class="">
											@php echo $val['name'] @endphp
									</td>
									<td>

										<div class="clt-lg">
											<a href="@php echo $link @endphp" target="_blank">
												<img src="{{ URL::asset("$logo") }}" alt="logo" class="tbl-logo">
											</a>
										</div>

									</td>
									<td>
										@php
											if(!empty($from)){
												$max = max($from,$for);
												$min = min($from,$for);
												echo 'from  £'.$min.' to  £'.$max;
											}else{
												echo '-';
											}
										@endphp
									</td>
									<!------<td>2019 - 2020</td>------->
									<td>
										@php
											//print_r($val);
											$id = $val['id'];
											$name = $val['name'];
											$addressLine1 = $val['address_line_1'];
											$country = $val['country'];
											$state = $val['state'];
											$town = $val['town'];

											$rating = $val['rating'];
											$ratings = $val['rating']*20;
											$email = $val['support_email_address'];
											$moreinfo = $val['more_info'];
										@endphp
										<div style="display:none" class="moreInfo_<?php echo $id; ?>">@php echo $moreinfo; @endphp</div>
										<a href="javascript:void(0)" data-toggle="modal" data-target="#myModal"
										   onclick="moreInfo(@php echo $id @endphp,'<?php echo $name; ?>','<?php echo $addressLine1; ?>','<?php echo $country; ?>','<?php echo $state; ?>','<?php echo $town; ?>','<?php echo $link; ?>','<?php echo $email; ?>')" class="more-btn">More Info</a>
									</td>
									<td>
										<div class="rating-box">
											<div class="ratings">
												  <div class="empty-stars"></div>
												  <div class="full-stars" style="width:@php echo $ratings.'%'; @endphp"></div>
											</div>
											<!----
											@for($i=1;$i<=5;$i++)

												@php
													if($i <= $rating){
												@endphp
														<span class="fa fa-star checked"></span>
												@php
													}else{
												@endphp
														<span class="fa fa-star"></span>
												@php
													}
												@endphp
											@endfor
											----->
										</div>
									</td>

									<td>
										<div class="clt-lg client-table">
										  	<center>
												  <a class="more-btn" href="@php echo $link @endphp" target="_blank">
													Visit
													</a>
										  	</center>
									 	 </div>
									</td>
								</tr>
						@php $i++; @endphp
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>


<div class="modal fade big-popup popup-box" id="myModal">
  <div class="modal-dialog modal-dialog-centered modal-dialog-zoom">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="name"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="popup-body ">
       <p class="link"></p>
		<div class="more_info"></div>
		  <!----<ul>
		   	<li class="address_line_1"></li>
         	<li class="country"></li>
		    <li class="state"></li>
		    <li class="town"></li>

       </ul>---->
      </div>
    </div>
  </div>
</div>

	<script>
		function moreInfo(id,name,addressLine1,country,state,town,link,email){
			$(".name").html(name);
			//$(".address_line_1").html('Address : '+addressLine1);
			//$(".country").html('Country : '+country);
			//$(".state").html('State : '+state);
			//$(".town").html('Town : '+town);
			var moreinfo = $(".moreInfo_"+id).html();
			$(".more_info").html(moreinfo);

			var data = '<b>Domain Link : </b>'+link;
				//data += ' <b><br/> Email &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; : </b>'+email;
			$(".link").html(data);
			$("#myModal").show();
		}
	</script>

<!------
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-6263923552826378",
          enable_page_level_ads: true
     });
</script>----->


<!----
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:inline-block;width:700px;height:150px"
     data-ad-client="ca-pub-6263923552826378"
     data-ad-slot="1478858190"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>---->

@endsection


<style>

.ratings {
  position: relative;
  vertical-align: middle;
  display: inline-block;
  color: #b1b1b1;
  overflow: hidden;
}

.full-stars{
  position: absolute;
  left: 0;
  top: 0;
  white-space: nowrap;
  overflow: hidden;
  color: #fde16d;
}

.empty-stars:before,
.full-stars:before {
  content: "\2605\2605\2605\2605\2605";
  font-size: 14pt;
}

.empty-stars:before {
  -webkit-text-stroke: 1px #848484;
}

.full-stars:before {
  -webkit-text-stroke: 1px orange;
}

/* Webkit-text-stroke is not supported on firefox or IE */
/* Firefox */
@-moz-document url-prefix() {
  .full-stars{
    color: #ECBE24;
  }
}
/* IE */
</style>
