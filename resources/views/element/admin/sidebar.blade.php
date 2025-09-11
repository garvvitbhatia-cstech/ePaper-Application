@php
$action =  Route::getCurrentRoute()->getName();
@endphp
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ url('/admin/dashboard'); }}"><img width="100%" src="https://epaper.voiceofjaipur.com//public/img/logo.jpg" /></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-item {{$action =='admin.dashboard' ?'active':''}}">
                    <a href="{{ url('/admin/dashboard'); }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>{{Session::get('admin_type')}} Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item  has-sub {{$action =='admin.update-profile' || $action =='admin.change-password'  ?'active':''}}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-person-fill"></i>
                        <span>My Profile</span>
                    </a>
                    <ul class="submenu {{$action =='admin.update-profile' || $action =='admin.change-password'  ?'active':''}}">
                        <li class="submenu-item {{$action =='admin.update-profile' ?'active':''}}">
                            <a href="{{ url('/admin/update-profile'); }}">Update Profile</a>
                        </li>
                        <li class="submenu-item {{$action =='admin.change-password' ?'active':''}} ">
                            <a href="{{ url('/admin/change-password'); }}">Change Password</a>
                        </li>
                        <!--<li class="submenu-item {{$action =='admin.settings' ?'active':''}} ">
                            <a href="{{ url('/admin/settings'); }}">Settings</a>
                        </li>-->
                    </ul>
                </li>
                <li class="sidebar-item {{$action =='admin.newspapers' ?'active':''}}">
                    <a href="{{ url('/admin/newspapers'); }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Newspapers</span>
                    </a>
                </li>
                <!--<li class="sidebar-item {{$action =='admin.magazines' ?'active':''}}">
                    <a href="{{ url('/admin/magazines'); }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Magazine</span>
                    </a>
                </li>-->
                
                <li class="sidebar-item {{$action =='admin.newsletters' || $action =='admin.edit-newsletter' || $action =='admin.add-newsletter' ?'active':''}}">
                    <a href="{{ url('/admin/newsletters'); }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Newsletters</span>
                    </a>
                </li>
                
                
                
                <li class="sidebar-item">
                    <a href="{{ url('/admin/logout'); }}" class='sidebar-link'>
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Log Out</span>
                    </a>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
