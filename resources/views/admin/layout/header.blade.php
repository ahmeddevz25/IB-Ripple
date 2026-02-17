   <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
       id="layout-navbar" style="width: 74rem;margin-left: 18rem;">
       <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
           <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
               <i class="bx bx-menu bx-sm"></i>
           </a>
       </div>

       <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
           <!-- Search -->
           <div class="nav-item d-flex align-items-center position-relative" style="width: 250px;">
               <i class="bx bx-search fs-4 lh-0 position-absolute"
                   style="left:10px; top:50%; transform:translateY(-50%);"></i>
               <input type="text" id="global-search" class="form-control ps-5 border-0 shadow-none"
                   placeholder="Search..." autocomplete="off">
               <ul id="search-results" class="list-group position-absolute w-100 mt-1"
                   style="z-index:1000; display:none; max-height:250px; overflow-y:auto;"></ul>
           </div>


           <!-- /Search -->

           <ul class="navbar-nav flex-row align-items-center ms-auto">

               <!-- User -->
               <li class="nav-item navbar-dropdown dropdown-user dropdown">
                   <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                       <div class="avatar avatar-online d-flex align-items-center justify-content-center bg-light rounded-circle"
                           style="width:40px; height:40px;">
                           <i class="bx bx-user fs-4 text-primary"></i>
                       </div>
                   </a>
                   <ul class="dropdown-menu dropdown-menu-end">

                       <!-- User Info -->
                       <li>
                           <a class="dropdown-item" href="#">
                               <div class="d-flex align-items-center">
                                   <div class="flex-shrink-0 me-3">
                                       <div class="avatar avatar-online d-flex align-items-center justify-content-center bg-light rounded-circle"
                                           style="width:40px; height:40px;">
                                           <i class="bx bx-user fs-4 text-primary"></i>
                                       </div>
                                   </div>
                                   <div class="flex-grow-1">
                                       <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                       <small class="text-success fw-semibold">
                                           {{ Auth::user()->getRoleNames()->first() }}
                                       </small>
                                   </div>
                               </div>
                           </a>
                       </li>

                       <li>
                           <div class="dropdown-divider"></div>
                       </li>

                       <!-- Logout -->
                       <li>
                           <form id="logout-form" action="{{ route('logout') }}" method="POST">
                               @csrf
                               <button type="submit" class="dropdown-item">
                                   <i class="bx bx-power-off me-2 text-danger"></i>
                                   <span class="align-middle">Log Out</span>
                               </button>
                           </form>
                       </li>
                   </ul>
               </li>
           </ul>

       </div>
   </nav>
