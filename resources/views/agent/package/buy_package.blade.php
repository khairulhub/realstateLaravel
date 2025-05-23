@extends('agent.agent_dashboard')
 @section('agent')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
 
 	<div class="page-content">
 
 				<nav class="page-breadcrumb">
 					<ol class="breadcrumb">
 						 
 					</ol>
 				</nav>
 
 				<div class="row">
 					<div class="col-md-12">
             <div class="card">
               <div class="card-body">
                 <h3 class="mt-4 mb-3 text-center">Choose a plan</h3>
                 
                 <div class="container">  
                   <div class="row">
                     <div class="col-md-4 stretch-card grid-margin grid-margin-md-0">
                       <div class="card">
                         <div class="card-body">
                           <h4 class="mt-3 mb-4 text-center">Basic</h4>
                           <i data-feather="award" class="mx-auto my-3 text-primary icon-xxl d-block"></i>
                           <h1 class="text-center">$0</h1>
                           <p class="mb-4 text-center text-muted fw-light">Limited</p>
                           <h5 class="mb-4 text-center text-primary">Up to 1 Property </h5>
                           <table class="mx-auto">
                             <tr>
                               <td><i data-feather="check" class="icon-md text-primary me-2"></i></td>
                               <td><p>Up to 1 Property</p></td>
                             </tr>
                             
                             <tr>
                               <td><i data-feather="x" class="icon-md text-danger me-2"></i></td>
                               <td><p class="text-muted">Premium Support</p></td>
                             </tr>
                           </table>
                           <div class="d-grid">
                             <button class="mt-4 btn btn-primary">Start Now</button>
                           </div>
                         </div>
                       </div>
                     </div>
                     <div class="col-md-4 stretch-card grid-margin grid-margin-md-0">
                       <div class="card">
                         <div class="card-body">
                           <h4 class="mt-3 mb-4 text-center">Business</h4>
                           <i data-feather="gift" class="mx-auto my-3 text-success icon-xxl d-block"></i>
                           <h1 class="text-center">$20</h1>
                           <p class="mb-4 text-center text-muted fw-light">Unlimited Months</p>
                           <h5 class="mb-4 text-center text-success">Up to 3 Property </h5>
                           <table class="mx-auto">
                             <tr>
                               <td><i data-feather="check" class="icon-md text-primary me-2"></i></td>
                               <td><p>Up to 3 Property</p></td>
                             </tr>
                             <tr>
                               <td><i data-feather="check" class="icon-md text-primary me-2"></i></td>
                               <td><p>Premium Support</p></td>
                             </tr>
                              
                           </table>
                           <div class="d-grid">
                            <a href="{{ route('buy.business.plan') }}" class="mt-4 btn btn-success">Start Now  </a> 
                           </div>
                         </div>
                       </div>
                     </div>
                     <div class="col-md-4 stretch-card">
                       <div class="card">
                         <div class="card-body">
                           <h4 class="mt-3 mb-4 text-center">Professional</h4>
                           <i data-feather="briefcase" class="mx-auto my-3 text-primary icon-xxl d-block"></i>
                           <h1 class="text-center">$50</h1>
                           <p class="mb-4 text-center text-muted fw-light">Unlimited Months</p>
                           <h5 class="mb-4 text-center text-primary">Up to 10 Property</h5>
                           <table class="mx-auto">
                             <tr>
                               <td><i data-feather="check" class="icon-md text-primary me-2"></i></td>
                               <td><p>Up to 10 Property</p></td>
                             </tr>
                             <tr>
                               <td><i data-feather="check" class="icon-md text-primary me-2"></i></td>
                               <td><p>Premium Support </p></td>
                             </tr>
                             
                           </table>
                           <div class="d-grid">
                            <a href="{{ route('buy.professional.plan') }}" class="mt-4 btn btn-primary">Start Now  </a> 
                           </div>
                         </div>
                       </div>
                     </div>
 
                   </div>
                 </div>
               </div>
             </div>
 					</div>
 				</div>
 			</div>
 
 
 
 
 
 
 
 
 
 @endsection
 