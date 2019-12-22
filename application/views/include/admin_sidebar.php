  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= base_url() ?>public/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= ucwords($this->session->userdata('name')); ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
     
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <!--<ul class="sidebar-menu">
        <li id="dashboard" class="treeview">
            <a href="<?= base_url('admin/dashboard'); ?>">
              <i class="fal fa-tachometer-alt"></i> <span>Tableau de bord</span>              
            </a>
          </li>
      </ul>
        //-->
        <ul class="sidebar-menu">
            <li id="people" class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Personnes</span>
                    <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                </a>
                <ul class="treeview-menu">
                    <li id="users"><a href="<?= base_url('admin/users'); ?>"><i class="fa fa-user"></i> Utilisateurs</a></li>
                    <li id="import"><a href="<?= base_url('admin/import'); ?>"><i class="fa fa-download"></i> Import</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu">
            <li id="order" class="treeview">
                <a href="<?= base_url('admin/order'); ?>">
                    <i class="fa fa-shopping-cart"></i> <span>Commandes</span>
                </a>
            </li>
        </ul>
        <ul class="sidebar-menu">
            <li id="calculation" class="treeview">
                <a href="<?= base_url('admin/calculation'); ?>">
                    <i class="fa fa-calculator-alt"></i> <span>Calculs</span>
                </a>
            </li>
        </ul>
      <!--<ul class="sidebar-menu">
        <li id="take" class="treeview">
            <a href="<?= base_url('admin/take'); ?>">
              <i class="fal fa-poll"></i> <span>Sessions</span>
            </a>
          </li>
      </ul>//-->

        <ul class="sidebar-menu">
            <li id="calculator" class="treeview">
                <a href="<?= base_url('admin/calculator'); ?>">
                    <i class="fa fa-calculator"></i> <span>Calculateurs</span>
                </a>
            </li>
        </ul>

        <ul class="sidebar-menu">
            <li id="calculator" class="treeview">
                <a href="<?= base_url('admin/factor'); ?>">
                    <i class="fa fa-abacus"></i> <span>Facteurs</span>
                </a>
            </li>
        </ul>

        <ul class="sidebar-menu">
            <li id="project" class="treeview">
                <a href="<?= base_url('admin/project'); ?>">
                    <i class="fa fa-folder"></i> <span>Projets</span>
                </a>
            </li>
        </ul>

        <ul class="sidebar-menu">
            <li id="partner" class="treeview">
                <a href="<?= base_url('admin/partner'); ?>">
                <i class="fa fa-handshake-alt"></i> <span>Partenaires</span>
                </a>
            </li>
        </ul>

        <ul class="sidebar-menu">
            <li id="code" class="treeview">
                <a href="<?= base_url('admin/code'); ?>">
                    <i class="fa fa-tag"></i> <span>Codes promo</span>
                </a>
            </li>
        </ul>

        <ul class="sidebar-menu">
            <li id="preset" class="treeview">
                <a href="<?= base_url('admin/preset'); ?>">
                    <i class="fa fa-box-usd"></i> <span>Presets</span>
                </a>
            </li>
        </ul>

        <ul class="sidebar-menu">
            <li id="tools" class="treeview">
                <a href="#">
                    <i class="fa fa-toolbox"></i>
                    <span>Outils</span>
                    <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                </a>
                <ul class="treeview-menu">
                    <li id="configs"><a href="<?= base_url('admin/config'); ?>"><i class="fa fa-wrench"></i> Configs</a></li>
                    <li id="logs"><a href="<?= base_url('admin/logs'); ?>"><i class="fa fa-file-alt"></i> Logs</a></li>
                    <li id="dbtools"><a href="<?= base_url('admin/dbtools'); ?>"><i class="fa fa-database"></i> DB</a></li>
                    <li id="export"><a href="<?= base_url('admin/tools/export_all'); ?>"><i class="fas fa-file-export"></i>  Export all</a></li>
                </ul>
            </li>
        </ul>
           
      <!--<ul class="sidebar-menu">
        <li id="example" class="treeview">
            <a href="#">
              <i class="fa fa-snowflake-o"></i> <span>Codeigniter Examples</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class=""><a href="<?= base_url('admin/example/simple_datatable'); ?>"><i class="fa fa-circle-o"></i>Simple Datatable</a></li>
              <li class=""><a href="<?= base_url('admin/example/ajax_datatable'); ?>"><i class="fa fa-circle-o"></i>Ajax Datatable</a></li>
              <li class=""><a href="<?= base_url('admin/example/pagination'); ?>"><i class="fa fa-circle-o"></i>Pagination</a></li>
              <li class=""><a href="<?= base_url('admin/example/advance_search'); ?>"><i class="fa fa-circle-o"></i>Advance Search</a></li>
              <li class=""><a href="<?= base_url('admin/example/file_upload'); ?>"><i class="fa fa-circle-o"></i>File Upload</a></li>
            </ul>
          </li>
      </ul>
      <ul class="sidebar-menu">
        <li id="users" class="treeview">
            <a href="#">
              <i class="fa fa-user"></i> <span>Users</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li id=""><a href="<?= base_url('admin/users'); ?>"><i class="fa fa-circle-o"></i>Users List</a></li>
              <li id="user_add"><a href="<?= base_url('admin/users/add'); ?>"><i class="fa fa-circle-o"></i>Add Users</a></li>
              <li id="group"><a href="<?= base_url('admin/group'); ?>"><i class="fa fa-circle-o"></i>User Group </a></li>
              <li id=""><a href="<?= base_url('admin/profile'); ?>"><i class="fa fa-circle-o"></i>Admin Profile</a></li>
              <li id=""><a href="<?= base_url('admin/profile/change_pwd'); ?>"><i class="fa fa-circle-o"></i>Change Password</a></li>
            </ul>
          </li>
      </ul>
      <ul class="sidebar-menu">
        <li id="joins" class="treeview">
            <a href="#">
              <i class="fa fa-i-cursor"></i> <span>Database Joins</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class=""><a href="<?= base_url('admin/joins'); ?>"><i class="fa fa-circle-o"></i> Serverside Join </a></li>
              <li class=""><a href="<?= base_url('admin/joins/simple'); ?>"><i class="fa fa-circle-o"></i> Simple Join</a></li>
            </ul>
          </li>
      </ul>

      <ul class="sidebar-menu">
        <li id="invoices" class="treeview">
            <a href="#">
              <i class="fa fa-money"></i> <span>Invoiceing System</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class=""><a href="<?= base_url('admin/invoices'); ?>"><i class="fa fa-circle-o"></i> Invoice List </a></li>
              <li class=""><a href="<?= base_url('admin/invoices/add'); ?>"><i class="fa fa-circle-o"></i> Add Invoice </a></li>
            </ul>
          </li>
      </ul> 

      <ul class="sidebar-menu">
        <li id="export" class="treeview">
            <a href="#">
              <i class="fa fa-life-ring"></i> <span>Backup & Export</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class=""><a href="<?= base_url('admin/export'); ?>"><i class="fa fa-circle-o"></i> Database Backup </a></li>
            </ul>
          </li>
      </ul>  

      <ul class="sidebar-menu">
        <li id="ui" class="treeview">
            <a href="#">
              <i class="fa fa-laptop"></i> <span>UI Components</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li id="general"><a href="<?= base_url('admin/ui/general'); ?>"><i class="fa fa-circle-o"></i> General</a></li>
              <li id="widgets"><a href="<?= base_url('admin/ui/widgets'); ?>"><i class="fa fa-circle-o"></i> Widgets</a></li>
              <li id="icons"><a href="<?= base_url('admin/ui/icons'); ?>"><i class="fa fa-circle-o"></i> Icons</a></li>
              <li id="buttons"><a href="<?= base_url('admin/ui/buttons'); ?>"><i class="fa fa-circle-o"></i> Buttons</a></li>
              <li id="sliders"><a href="<?= base_url('admin/ui/sliders'); ?>"><i class="fa fa-circle-o"></i> Sliders</a></li>
              <li id="timeline"><a href="<?= base_url('admin/ui/timeline'); ?>"><i class="fa fa-circle-o"></i> Timeline</a></li>
              <li id="modals"><a href="<?= base_url('admin/ui/modals'); ?>"><i class="fa fa-circle-o"></i> Modals</a></li>
            </ul>
          </li>
      </ul> 
      
      <ul class="sidebar-menu">
        <li id="forms" class="treeview">
            <a href="#">
              <i class="fa fa-edit"></i> <span>Forms</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li id="gen"><a href="<?= base_url('admin/forms/general'); ?>"><i class="fa fa-circle-o"></i> General</a></li>
              <li id="advanced"><a href="<?= base_url('admin/forms/advanced'); ?>"><i class="fa fa-circle-o"></i> Advance</a></li>
              <li id="editors"><a href="<?= base_url('admin/forms/editors'); ?>"><i class="fa fa-circle-o"></i> Editors</a></li>
            </ul>
        </li>
      </ul> 

      <ul class="sidebar-menu">
        <li id="charts" class="treeview">
            <a href="#">
              <i class="fa fa-pie-chart"></i>
              <span>Charts</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li id="chartjs"><a href="<?= base_url('admin/charts/chartjs'); ?>"><i class="fa fa-circle-o"></i> ChartJS</a></li>
              <li id="morris"><a href="<?= base_url('admin/charts/morris'); ?>"><i class="fa fa-circle-o"></i> Morris</a></li>
              <li id="flot"><a href="<?= base_url('admin/charts/flot'); ?>"><i class="fa fa-circle-o"></i> Flot</a></li>
              <li id="inline"><a href="<?= base_url('admin/charts/inline'); ?>"><i class="fa fa-circle-o"></i> Inline charts</a></li>
          </ul>
        </li>
      </ul>
      
      <ul class="sidebar-menu">  
        <li id="calender">
          <a href="<?= base_url('admin/calendar'); ?>">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-red">3</small>
              <small class="label pull-right bg-blue">17</small>
            </span>
          </a>
        </li>
      </ul>
        
      <ul class="sidebar-menu">
        <li id="mailbox" class="treeview">
          <a href="">
            <i class="fa fa-envelope"></i> <span>Mailbox</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="inbox">
              <a href="<?= base_url('admin/mailbox/inbox'); ?>">Inbox
                <span class="pull-right-container">
                  <span class="label label-primary pull-right">13</span>
                </span>
              </a>
            </li>
            <li id="compose"><a href="<?= base_url('admin/mailbox/compose'); ?>">Compose</a></li>
            <li id="read"><a href="<?= base_url('admin/mailbox/read_mail'); ?>">Read</a></li>
          </ul>
        </li>
      </ul>

      <ul class="sidebar-menu">
        <li class="treeview">
          <a href="#">
            <i class="fa fa-share"></i> <span>Multilevel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
            <li>
              <a href="#"><i class="fa fa-circle-o"></i> Level One
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                <li>
                  <a href="#"><i class="fa fa-circle-o"></i> Level Two
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                  </ul>
                </li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
          </ul>
        </li>
      </ul>
//-->

    </section>
    <!-- /.sidebar -->
  </aside>

  
<script>
  $("#<?= $cur_tab ?>").addClass('active');
</script>
