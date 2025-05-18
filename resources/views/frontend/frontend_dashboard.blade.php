<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

<title>Easy - RealState </title>

<!-- Fav Icon -->
<link rel="icon" href="{{ asset('frontend/assets/images/favicon.ico') }}" type="image/x-icon">
<meta name="csrf-token" content="{{ csrf_token() }}" >
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- Stylesheets -->
<link href="{{ asset('frontend/assets/css/font-awesome-all.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/flaticon.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/owl.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/jquery.fancybox.min.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/animate.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/jquery-ui.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/nice-select.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/color/theme-color.css') }}" id="jssDefault" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/switcher-style.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/responsive.css') }}" rel="stylesheet">

 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >


</head>


<!-- page wrapper -->
<body>

    <div class="boxed_wrapper">


        <!-- preloader -->
      @include('frontend.home.preload')
        <!-- preloader end -->


        <!-- switcher menu -->

        <!-- end switcher menu -->


        <!-- main header -->
        @include('frontend.home.header')
        <!-- main-header end -->

        <!-- Mobile Menu  -->
         @include('frontend.home.mobile_menu')
       <!-- End Mobile Menu -->


       @yield('main')

        <!-- main-footer -->
       @include('frontend.home.footer')
        <!-- main-footer end -->



        <!--Scroll to top-->
        <button class="scroll-top scroll-to-target" data-target="html">
            <span class="fal fa-angle-up"></span>
        </button>
    </div>


    <!-- jequery plugins -->
    <script src="{{ asset('frontend/assets/js/jquery.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/owl.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wow.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/validation.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/appear.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/scrollbar.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/isotope.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jQuery.style.switcher.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/nav-tool.js') }}"></script>


    
    <!-- map script -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-CE0deH3Jhj6GN4YvdCFZS7DpbXexzGU"></script>
    <script src="{{ asset('frontend/assets/js/gmaps.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/map-helper.js') }}"></script>


        <!-- main-js -->
    <script src="{{ asset('frontend/assets/js/script.js') }}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>





  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  
  <script type="text/javascript">
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    })
    
    // Add To Wishlist 
    function addToWishList(property_id){
        $.ajax({
             type: "POST",
             dataType: 'json',
             url: "/add-to-wishList/"+property_id,
 
             success:function(data){

                wishlist();
 
                 // Start Message 
 
             const Toast = Swal.mixin({
                   toast: true,
                   position: 'top-end',
                   
                   showConfirmButton: false,
                   timer: 3000 
             })
             if ($.isEmptyObject(data.error)) {
                     
                     Toast.fire({
                     type: 'success',
                     icon: 'success', 
                     title: data.success, 
                     })
 
             }else{
                
            Toast.fire({
                     type: 'error',
                     icon: 'error', 
                     title: data.error, 
                     })
                 }
 
               // End Message  
 
             }
         })

    }


</script>






<script>
 @if(session()->has('message'))
 var type = "{{ Session::get('alert-type','info') }}"
 switch(type){
    case 'info':
    toastr.info(" {{ Session::get('message') }} ");
    break;

    case 'success':
    toastr.success(" {{ Session::get('message') }} ");
    break;

    case 'warning':
    toastr.warning(" {{ Session::get('message') }} ");
    break;

    case 'error':
    toastr.error(" {{ Session::get('message') }} ");
    break;
 }
 @endif
</script>


 <!-- // start load Wishlist Data  -->
 
 <script type="text/javascript">
 
    function wishlist(){
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "/get-wishlist-property/",

            success:function(response){

                $('#wishQty').text(response.wishQty);

                var rows = ""
                 $.each(response.wishlist, function(key,value){
 
                   rows += `<div class="deals-block-one">
         <div class="inner-box">
             <div class="image-box">
                 <figure class="image"><img src="/${value.property.property_thambnail}" alt=""></figure>
                 <div class="batch"><i class="icon-11"></i></div>
                 <span class="category">Featured</span>
                 <div class="buy-btn"><a href="#">For ${value.property.property_status}</a></div>
             </div>
             <div class="lower-content">
                 <div class="title-text"><h4><a href="">${value.property.property_name}</a></h4></div>
                 <div class="clearfix price-box">
                     <div class="price-info pull-left">
                         <h6>Start From</h6>
                         <h4>$${value.property.lowest_price}</h4>
                     </div>
                      
                 </div>
                
                 <ul class="clearfix more-details">
                     <li><i class="icon-14"></i>${value.property.bedrooms} Beds</li>
                     <li><i class="icon-15"></i>${value.property.    bathrooms} Baths</li>
                     <li><i class="icon-16"></i>${value.property.    property_size} Sq Ft</li>
                 </ul>
                 <div class="clearfix other-info-box">
                     
                     <ul class="clearfix other-option pull-right">
                        
                         <li><a type="submit" class="text-body" id="${value.id}" onclick="wishlistRemove(this.id)" ><i class="fa fa-trash"></i></a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </div> `  
                 });
 
       $('#wishlist').html(rows);       

            }
        })
    }
    wishlist();



     // Wishlist Remove Start 
 
     function wishlistRemove(id){
         $.ajax({
             type: "GET",
             dataType: 'json',
             url: "/wishlist-remove/"+id,
 
             success:function(data){
                 wishlist();
 
                  // Start Message 
 
             const Toast = Swal.mixin({
                   toast: true,
                   position: 'top-end',
                   
                   showConfirmButton: false,
                   timer: 3000 
             })
             if ($.isEmptyObject(data.error)) {
                     
                     Toast.fire({
                     type: 'success',
                     icon: 'success', 
                     title: data.success, 
                     })
 
             }else{
                
            Toast.fire({
                     type: 'error',
                     icon: 'error', 
                     title: data.error, 
                     })
                 }
 
               // End Message  
 
 
             }
         })
 
     }
 
     /// End Wishlist Remove 
</script>

 <!-- /// Add to Carepage  -->
 <script type="text/javascript">
 
    function addToCompare(property_id){
       $.ajax({
           type: "POST",
           dataType: 'json',
           url: "/add-to-compare/"+property_id,

           success:function(data){
              
               // Start Message 

           const Toast = Swal.mixin({
                 toast: true,
                 position: 'top-end',
                 
                 showConfirmButton: false,
                 timer: 3000 
           })
           if ($.isEmptyObject(data.error)) {
                   
                   Toast.fire({
                   type: 'success',
                   icon: 'success', 
                   title: data.success, 
                   })

           }else{
              
          Toast.fire({
                   type: 'error',
                   icon: 'error', 
                   title: data.error, 
                   })
               }

             // End Message  

           }
       })

   }
   

</script>

<!-- // start load Wishlist Data  -->
 
<script type="text/javascript">
 
    function compare(){
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "/get-compare-property/",

            success:function(response){
 

                var rows = ""
                $.each(response, function(key,value){

  rows += ` <tr>
                <th>Property Info</th>
                <th>
                    <figure class="image-box"><img src="/${value.property.property_thambnail}" alt=""></figure>
                    <div class="title">${value.property.property_name}</div>
                    <div class="price">$${value.property.lowest_price}</div>
                </th>
                
               
            </tr>    
            <tr>
                <td>
                    <p>City</p>
                </td>
                <td>
                    <p>${value.property.city}</p>
                </td>
                 
            </tr>
            <tr>
                <td>
                    <p>Area</p>
                </td>
                <td>
                    <p>${value.property.property_size} Sq Ft</p>
                </td>
                 
            </tr>
            <tr>
                <td>
                    <p>Rooms</p>
                </td>
                <td>
                    <p>${value.property.bedrooms}</p>
                </td>
                 
            </tr>
            <tr>
                <td>
                    <p>Bathrooms</p>
                </td>
                <td>
                    <p>${value.property.bathrooms}</p>
                </td>
                 
            </tr>
           <tr>
                 <td>
                     <p>Action</p>
                 </td>
                 <td>
                     <a type="submit" class="text-body" id="${value.id}" onclick="compareRemove(this.id)" ><i class="fa fa-trash"></i></a>
                 </td>
                  
             </tr> `   
                });

      $('#compare').html(rows);       
            }
        })
    }

    compare();


    // Wishlist Remove Start 
 // Compare Remove Start 
 
 function compareRemove(id){
         $.ajax({
             type: "GET",
             dataType: 'json',
             url: "/compare-remove/"+id,
 
             success:function(data){
                 compare();
 
                  // Start Message 
 
             const Toast = Swal.mixin({
                   toast: true,
                   position: 'top-end',
                   
                   showConfirmButton: false,
                   timer: 3000 
             })
             if ($.isEmptyObject(data.error)) {
                     
                     Toast.fire({
                     type: 'success',
                     icon: 'success', 
                     title: data.success, 
                     })
 
             }else{
                
            Toast.fire({
                     type: 'error',
                     icon: 'error', 
                     title: data.error, 
                     })
                 }
 
               // End Message  
 
 
             }
         })
 
     }
  
      /// End Compare Remove  
    
</script>
<script type="text/javascript">
function addToCart(propertyId, propertyName, slug, agentId, lowestPrice, propertyStatus) {
    $.ajax({
        type: "POST",
        url: "/cart/data/store/" + propertyId,
        data: {
            _token: '{{ csrf_token() }}',
            property_name: propertyName,
            property_slug: slug,
            lowest_price: lowestPrice,
            agent_id: agentId,
            property_status: propertyStatus,
           
        },
        success: function(data) {
            miniCart();
            // Start Message 
 
             const Toast = Swal.mixin({
                   toast: true,
                   position: 'top-end',
                   
                   showConfirmButton: false,
                   timer: 3000 
             })
             if ($.isEmptyObject(data.error)) {
                     
                     Toast.fire({
                     type: 'success',
                     icon: 'success', 
                     title: data.success, 
                     })
 
             }else{
                
            Toast.fire({
                     type: 'error',
                     icon: 'error', 
                     title: data.error, 
                     })
                 }
 
               // End Message  
        },
        
    });
}
</script>
{{-- /// Start Mini Cart  // --}}
  <script type="text/javascript">

    function miniCart(){
        $.ajax({
            type: 'GET',
            url: '/property/mini/cart',
            dataType: 'json',
            success:function(response){

                 $('span[id="cartSubTotal"]').text(response.cartTotal);
                $('#cartQty').text(response.cartQty);

                var miniCart = ""
                $.each(response.carts, function(key,value){
                    miniCart += `<li class="media media-card">
                            <a href="shopping-cart.html" class="media-img">
                                <img src="/${value.options.image}" alt="Cart image" style="width: 60px; height: 60px;">

                            </a>
                            <div class="media-body">
                                 <h6 style="font-size: 12px;"><a href="/property/details/${value.id}/${value.options.slug}"> ${value.name}</a></h6>
                                  
                                 <span class="d-block fs-14">$${value.price}</span>
                               
                                 <a  type="submit" class="btn btn-danger" id="${value.rowId}" onClick="miniCartRemove(this.id)">Delete </a> 

                            </div>
                        </li> 
                        `  
                });
                $('#miniCart').html(miniCart);

            }
        })
    }
    miniCart();

     function miniCartRemove(rowId){
        $.ajax({
            type: 'GET',
            url: '/minicart/property/remove/'+rowId,
            dataType: 'json',
            success:function(data){
            miniCart();
// Start Message 

const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000 
            })
            if ($.isEmptyObject(data.error)) {
                    
                    Toast.fire({
                    type: 'success', 
                    icon: 'success', 
                    title: data.success, 
                    })

            }else{
               
           Toast.fire({
                    type: 'error', 
                    icon: 'error', 
                    title: data.error, 
                    })
                }

              // End Message   


            }
        })
    }

    // End Mini Cart Remove 

 </script>
{{-- /// End Mini Cart // --}}


 {{-- /// Start MyCart  // --}}
 <script type="text/javascript">

    function cart(){
        $.ajax({
            type: 'GET',
            url: '/get-cart-property',
            dataType: 'json',
            success:function(response){

                $('span[id="cartSubTotal"]').text(response.cartTotal);

                var rows = ""
                $.each(response.carts, function(key,value){
                    rows += `
                    <tr>
                    <th scope="row">
                        <div class="media media-card">
                            <a href="property-details.html" class="media-img mr-0">
                                <img style="width: 100px; height: 100px;" src="/${value.options.image}" alt="Cart image">
                            </a>
                        </div>
                    </th>
                    <td>
                        <a href="/propery/details/${value.id}/${value.options.slug}" class="text-black font-weight-semi-bold">${value.name}</a>
                         
                    </td>
                    <td>
                        <ul class="generic-list-item font-weight-semi-bold">
                            <li class="text-black lh-18">$${value.price}</li>
                            
                        </ul>
                    </td>
                     
                    <td>
                        <button type="button" class="btn btn-danger"  data-toggle="tooltip" data-placement="top" id="${value.rowId}" onclick="cartRemove(this.id)">
                            Delete
                        </button>
                    </td>
                </tr>
                `
                });

                $('#cartPage').html(rows);


            }
        })
    }
    cart();
 // My Cart Remove Start 
     function cartRemove(rowId){
        $.ajax({
            type: 'GET',
            url: '/cart-remove/'+rowId,
            dataType: 'json',

            success:function(data){
            miniCart();
            cart();
// Start Message 

const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000 
            })
            if ($.isEmptyObject(data.error)) {
                    
                    Toast.fire({
                    type: 'success', 
                    icon: 'success', 
                    title: data.success, 
                    })

            }else{
               
           Toast.fire({
                    type: 'error', 
                    icon: 'error', 
                    title: data.error, 
                    })
                }

              // End Message   


            }
        })
    }

    // End My Cart Remove 
</script>
{{-- /// End MyCart // --}}


<script src="./home/script.blade.php"></script>
</body><!-- End of .page_wrapper -->
</html>
