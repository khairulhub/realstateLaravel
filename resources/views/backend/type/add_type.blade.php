@extends('admin.admin_dashboard')
 @section('admin')

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

 <div class="page-content">
     <!--breadcrumb-->
     <div class="mb-3 page-breadcrumb d-none d-sm-flex align-items-center">
    
         <div class="ps-3">
             <nav aria-label="breadcrumb">
                 <ol class="p-0 mb-0 breadcrumb">
                     <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                     </li>
                     <li class="breadcrumb-item active" aria-current="page">Add Type</li>
                 </ol>
             </nav>
         </div>
        
     </div>
     <!--end breadcrumb-->
     
 
     <div class="row">
         <div class="mx-auto col-xl-10">
             
             <div class="card">
                 <div class="p-4 card-body">
                     <h5 class="mb-4">Add 
                         Type
                     </h5>
                     <form id="myForm" action="{{ route('store.type') }}" method="POST" class="row g-3" enctype="multipart/form-data">
                         @csrf
                         <div class="form-group col-md-6">
                             <label for="input1" class="form-label">Type Name</label>
                             <input type="text" class="form-control @error('type_name') is_invalid @enderror" id="input1" name="type_name" >
                                @error('type_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                         </div>
                         <div class="col-md-6">
                             
                         </div>
                         <div class="form-group col-md-6">
                             <label for="image" class="form-label">Type Icon</label>
                             <input class="form-control @error('type_icon') is_invalid @enderror" type="text" id="type_icon"  name="type_icon" placeholder="bx bx-home-alt">
                                @error('type_icon')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                         </div>
                         
                        
                       
                         <div class="col-md-12">
                             <div class="gap-3 d-md-flex d-grid align-items-center">
                                 <button type="submit" class="px-4 btn btn-primary">Submit</button>
                                 
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
 
       
 
 
         </div>
     </div>
 
 
    
 </div>
 
 
 
 
 
 <script type="text/javascript">
     $(document).ready(function() {
         $('#image').change(function(e) {
             let reader = new FileReader();
             reader.onload = (e) => {
                 $('#showImage').attr('src', e.target.result);
             }
             reader.readAsDataURL(this.files[0]);
         });
     });
 </script>
 
 
 
 <script type="text/javascript">
     $(document).ready(function (){
         $('#myForm').validate({
             rules: {
                 type_name: {
                     required : true,
                 }, 
                 type_icon: {
                     required : true,
                 }, 
                 
             },
             messages :{
                type_name: {
                     required : 'Please Enter type Name',
                 }, 
                 type_icon: {
                     required : 'Enter  type icon',
                 }, 
                  
 
             },
             errorElement : 'span', 
             errorPlacement: function (error,element) {
                 error.addClass('invalid-feedback');
                 element.closest('.form-group').append(error);
             },
             highlight : function(element, errorClass, validClass){
                 $(element).addClass('is-invalid');
             },
             unhighlight : function(element, errorClass, validClass){
                 $(element).removeClass('is-invalid');
             },
         });
     });
     
 </script>
@endsection
 