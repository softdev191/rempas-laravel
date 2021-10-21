@if ($user != NULL)
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar" style="padding-top: 0">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <a href="{{ backpack_url('dashboard') }}" class="logo">
            <div style="width: 100%; min-height: 120px; padding: 5px">
                <img src="{{ asset('uploads/logo/'.$company->logo) }}" style="width: 100%">
            </div>
        </a>
        <ul class="sidebar-menu" data-widget="tree">
          <!-- ================================================ -->
          <!-- ==== Recommended place for admin menu items ==== -->
          <!-- ================================================ -->

          @include('backpack::inc.sidebar_content')

          <!-- ======================================= -->
          {{-- <li class="header">Other menus</li> --}}
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
