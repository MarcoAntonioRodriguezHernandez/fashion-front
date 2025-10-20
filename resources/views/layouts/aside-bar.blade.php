 <!--begin::Sidebar-->
 <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
     data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
     <div class="app-sidebar-header d-flex flex-stack d-none d-lg-flex pt-8 pb-2" id="kt_app_sidebar_header">
         <!--begin::Logo-->
         <a href="{{ route('dashboard') }}" class="app-sidebar-logo">
             <img alt="Logo" src="{{ asset('media/logos/cmLogo.png') }}"
                 class="h-60px d-none d-sm-inline app-sidebar-logo-default theme-light-show" />
             <img alt="Logo" src="{{ asset('media/logos/cmLogoDark.png') }}"
                 class="h-60px h-lg-60px theme-dark-show" />
         </a>
         <!--end::Logo-->
         <!--begin::Sidebar toggle-->
         <div id="kt_app_sidebar_toggle"
             class="app-sidebar-toggle btn btn-sm btn-icon bg-light btn-color-gray-700 btn-active-color-primary d-none d-lg-flex rotate"
             data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
             data-kt-toggle-name="app-sidebar-minimize">
             <i class="ki-outline ki-text-align-right rotate-180 fs-1"></i>
         </div>
         <!--end::Sidebar toggle-->
     </div>
     <!--begin::Navs-->
     <div class="app-sidebar-navs flex-column-fluid py-6" id="kt_app_sidebar_navs">
         <div id="kt_app_sidebar_navs_wrappers" class="app-sidebar-wrapper hover-scroll-y my-2" data-kt-scroll="true"
             data-kt-scroll-activate="true" data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_app_sidebar_header" data-kt-scroll-wrappers="#kt_app_sidebar_navs"
             data-kt-scroll-offset="5px">
             <!--begin::Sidebar menu-->
             <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                 class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary">
                 <!--begin::Heading-->
                 <div class="menu-item mb-2">
                     <div class="menu-heading text-uppercase fs-7 fw-bold">Administración</div>
                     <!--begin::Separator-->
                     <div class="app-sidebar-separator separator"></div>
                     <!--end::Separator-->
                 </div>
                 <!--end::Heading-->

                 <!--begin:Menu items-->
                 @foreach ($asideLinks as $key => $item)
                    <x-layouts.aside-menu :mainTitle="$key" :content="$item" />
                 @endforeach
                 <!--end:Menu items-->
             </div>
             <!--end::Sidebar menu-->
         </div>
     </div>
     <!--end::Navs-->
 </div>
 <!--end::Sidebar-->
