
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><?=APPNAME?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?=User::get()->name;?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-comment"></i>
              <p>
              Messaging
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="messages/compose" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Compose Message</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="messages/sent" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sent Messages</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
              Contacts
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="contacts" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Contacts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="contacts/groups" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Contact Groups</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="templates" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
              Templates
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
              Reports/Analytics
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
              Settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="settings/account" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Account Settings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="settings/notification" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Notification Preferences</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="settings/api" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>API Configuration</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="help" class="nav-link">
              <i class="nav-icon fas fa-info"></i>
              <p>
              Help/Support
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a onclick="window.location='logout'" class="nav-link">
              <i class="nav-icon fas fa-power-off"></i>
              <p>
              Log Out
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>