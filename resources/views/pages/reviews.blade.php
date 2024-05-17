@extends('home')
@section('content')
    
	<div class="page-breadcrumb">		
		<div class="row">			
			<div class="col-5 align-self-center">				
				<h4 class="page-title uppercase">DISCOVER THE TRUE STORY: GOOGLE REVIEWS FROM CUSTOMERS</h4>				
				<div class="d-flex align-items-center">
				</div>			
			</div>		
		</div>	
	</div>
	
	
    <div class="container-fluid">
	
        <div class="row">  
		
			<div class="col-lg-9">						    
				<div class="card">                    
					<div class="card-body card-border shadow">
						<div class="col-md-12">
 							<div class="google_review_wrap">
								<script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
								<div class="elfsight-app-0337460f-bc61-491b-8652-44de2ba3da35" data-elfsight-app-lazy></div>
							</div>
							
 						</div>	
					</div>				
				</div>            
			</div>						
						
			<div class="col-lg-3">
			@include('pages.nav')
			</div>
						
        </div>

        
    </div>

@section('script')
@endsection
@endsection
