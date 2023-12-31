

<div class="main-header">
  <a class="nav-link nav-icon rounded-circle" href="#" data-toggle="sidebar"><i class="material-icons">menu</i></a>
  <?php /*<form class="form-inline ml-3 d-none d-md-flex">
    <span class="input-icon">
      <i class="material-icons">search</i>
      <input type="search" placeholder="Search..." class="form-control shadow-none bg-light border-0">
    </span>
  </form>*/ ?>
  <ul class="nav nav-circle nav-gap-x-1 ml-auto">
    <li class="nav-item d-md-none"><a class="nav-link nav-icon" data-toggle="modal" href="#searchModal"><i class="material-icons">search</i></a></li>
    <?php /*<li class="nav-item d-none d-sm-block"><a class="nav-link nav-icon" href="#" id="refreshPage"><i class="material-icons">refresh</i></a></li>
    <li class="nav-item dropdown nav-notif">
      <a class="nav-link nav-icon dropdown-toggle no-caret" href="#" data-toggle="dropdown" data-display="static">
        <i class="material-icons">color_lens</i>
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow border-0 p-3">
        <form>
          <h6>Navigation theme</h6>
          <div class="custom-color custom-color-lg">
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-blue.min.css')}}" id="sidebar-theme-blue" class="custom-control-input" checked>
              <label class="rounded-circle" for="sidebar-theme-blue" style="background-color:#2b579a"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-cyan.min.css')}}" id="sidebar-theme-cyan" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-cyan" style="background-color:#006064"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-gray.min.css')}}" id="sidebar-theme-gray" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-gray" style="background-color:#37474f"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-green.min.css')}}" id="sidebar-theme-green" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-green" style="background-color:#217346"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-pink.min.css')}}" id="sidebar-theme-pink" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-pink" style="background-color:#ad1457"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-purple.min.css')}}" id="sidebar-theme-purple" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-purple" style="background-color:#7151c8"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-red.min.css')}}" id="sidebar-theme-red" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-red" style="background-color:#b7472a"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-dark.min.css')}}" id="sidebar-theme-dark" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-dark" style="background-color:#272822"></label>
            </div>
            <div class="color-item color-item-light">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-white.min.css')}}" id="sidebar-theme-white" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-white" style="background-color:#fff"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-royal.min.css')}}" id="sidebar-theme-royal" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-royal" style="background-color:#243b55"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-ash.min.css')}}" id="sidebar-theme-ash" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-ash" style="background-color:#606c88"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-crimson.min.css')}}" id="sidebar-theme-crimson" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-crimson" style="background-color:#573662"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-namn.min.css')}}" id="sidebar-theme-namn" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-namn" style="background-color:#9b3333"></label>
            </div>
            <div class="color-item">
              <input type="radio" name="sidebar-theme" value="{{asset('dist/css/sidebar-frost.min.css')}}" id="sidebar-theme-frost" class="custom-control-input">
              <label class="rounded-circle" for="sidebar-theme-frost" style="background-color:#00275a"></label>
            </div>
          </div>
        </form>
      </div>
    </li>
    <li class="nav-item dropdown nav-notif">
      <a class="nav-link nav-icon has-badge dropdown-toggle no-caret" href="#" data-toggle="dropdown" data-display="static">
        <i class="material-icons">notifications</i>
        <span class="badge badge-pill badge-primary">4</span>
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow border-0 p-0">
        <form>
          <div class="card border-0">
            <div class="card-header bg-primary text-white">
              <i class="material-icons mr-2">notifications</i> 4 Notifications
            </div>
            <div class="card-body p-1 font-size-sm">
              <div class="list-group list-group-sm list-group-flush">
                <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                  <div class="media">
                    <span class="bg-primary-faded text-primary btn-icon rounded-circle"><i class="material-icons">person_add</i></span>
                    <div class="media-body ml-2">
                      5 New members joined today
                      <div class="small text-muted mt-1 d-flex align-items-center">
                        <i class="material-icons mr-1 font-size-sm">access_time</i> 5 minutes ago
                      </div>
                    </div>
                  </div>
                </a>
                <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                  <div class="media">
                    <span class="bg-info-faded text-info btn-icon rounded-circle"><i class="material-icons">comment</i></span>
                    <div class="media-body ml-2">
                      2 New comments
                      <div class="small text-muted mt-1 d-flex align-items-center">
                        <i class="material-icons mr-1 font-size-sm">access_time</i> 10 minutes ago
                      </div>
                    </div>
                  </div>
                </a>
                <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                  <div class="media">
                    <span class="bg-success-faded text-success btn-icon rounded-circle"><i class="material-icons">shopping_cart</i></span>
                    <div class="media-body ml-2">
                      10+ New Orders
                      <div class="small text-muted mt-1 d-flex align-items-center">
                        <i class="material-icons mr-1 font-size-sm">access_time</i> 15 minutes ago
                      </div>
                    </div>
                  </div>
                </a>
                <a href="javascript:void(0)" class="list-group-item list-group-item-action">
                  <div class="media">
                    <span class="bg-warning-faded text-warning btn-icon rounded-circle"><i class="material-icons">person</i></span>
                    <div class="media-body ml-2">
                      Complete your account details
                      <div class="small text-muted mt-1 d-flex align-items-center">
                        <i class="material-icons mr-1 font-size-sm">access_time</i> 20 minutes ago
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
            <div class="card-footer text-center">
              <a href="javascript:void(0)">See all notifications &rsaquo;</a>
            </div>
          </div>
        </form>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link nav-icon has-badge" href="#chatModal" data-toggle="modal">
        <i class="material-icons">chat</i>
        <span class="badge badge-pill badge-warning">2</span>
      </a>
    </li>*/ ?>
  </ul>
  <ul class="nav nav-pills">
    <li class="nav-link-divider mx-2"></li>
    <li class="nav-item dropdown">
      <a class="nav-link has-img dropdown-toggle px-2" href="#" data-toggle="dropdown">
        <img src="{{asset('img/user.svg')}}" alt=" @if(\Auth::user()->name === null) @if(\Auth::user()->login_type == 'superadmin') 'Admin' @elseif(\Auth::user()->login_type == 'associate') Associate @elseif(\Auth::user()->login_type == 'customer') Customer @elseif(\Auth::user()->login_type == 'employee') Employee @endif @endif " class="rounded-circle mr-2">
        <span class="d-none d-sm-block">{{ucwords(\Auth::user()->name)}}</span> 
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow border-0 p-0">
        <div class="card border-0">
          <div class="card-header flex-column px-5">
            <img src="{{asset('img/user.svg')}}" alt="@if(\Auth::user() == 'superadmin') 'Admin' @endif " class="rounded-circle mb-2" width="75" height="75">
            <h6>{{ucwords(\Auth::user()->name)}}</h6>
            <small class="text-muted">{{ucwords(\Auth::user()->login_type)}}</small>
          </div>
          <div class="list-group list-group-flush list-group-sm list-group-borderless py-2">
            <?php /*<a href="#" class="list-group-item list-group-item-action has-icon"><i class="material-icons mr-2">person</i>My Profile</a>
            <a href="#" class="list-group-item list-group-item-action has-icon"><i class="material-icons mr-2">settings</i>Settings</a>*/ ?>
            <a href="{{route('auth.changePassword')}}" class="list-group-item list-group-item-action has-icon text-info">
            <i class="material-icons mr-2">vpn_key</i>
              <form action="{{route('changePassword')}}" method="get" id="">
              {{ csrf_field() }}
              </form>
            Change Password</a>
          
            <?php /*<a href="#" class="list-group-item list-group-item-action has-icon"><i class="material-icons mr-2">person</i>My Profile</a>
            <a href="#" class="list-group-item list-group-item-action has-icon"><i class="material-icons mr-2">settings</i>Settings</a>*/ ?>
            <a href="{{route('customerlogout')}}" class="list-group-item list-group-item-action has-icon text-danger" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="material-icons mr-2">exit_to_app</i>
              <form action="{{route('customerlogout')}}" method="get" id="logout-form">
              {{ csrf_field() }}
              </form>
            Logout</a>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div>