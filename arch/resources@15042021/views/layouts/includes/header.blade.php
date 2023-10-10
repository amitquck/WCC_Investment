
 <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
     <div class="container">
                 <a href="{{route('home')}}"> <img src="{{asset('website/image/logo.png')}}" alt="gghj" width="100"></a>
                   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                   <span class="navbar-toggler-icon"></span>
                    </button>
             <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link" href="{{route('home')}}">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{{route('about')}}">About Us</a>
                    </li> 
                    <li class="nav-item">
                    <a class="nav-link" href="{{route('contact')}}">Contact Us</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#">Assets</a>
                    </li>
                    </ul>
                 <div class="ml-auto pt-1">
                    <a href="{{route('loginCustomer')}}" class="btn btn-secondary mr-2">LogIn</a>
                    <a href="{{route('registerCustomer')}}"> <button type="button" class="btn btn-primary">Registration</button></a>
                   
                </div>
              
        </div>
  </div>
</nav>