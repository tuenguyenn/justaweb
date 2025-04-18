<div class="wrapper wrapper-content">
    @include('backend.dashboard.component.orderstatitics')
    @include('backend.dashboard.component.revenue')


          <div class="row">
             

              <div class="col-lg-8">

                  <div class="row">
                      <div class="col-lg-6">
                          <div class="dashboard-card ">
                              <div class="dashboard-card-header">
                                  <h5>User project list</h5>
                                  <div class="dashboard-card-tools">
                                      <a class="collapse-link">
                                          <i class="fa fa-chevron-up"></i>
                                      </a>
                                      <a class="close-link">
                                          <i class="fa fa-times"></i>
                                      </a>
                                  </div>
                              </div>
                              <div class="dashboard-card-body table-responsive">
                                  <table class="table table-hover no-margins">
                                      <thead>
                                      <tr>
                                          <th>Status</th>
                                          <th>Date</th>
                                          <th>User</th>
                                          <th>Value</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      <tr>
                                          <td><small>Pending...</small></td>
                                          <td><i class="fa fa-clock-o"></i> 11:20pm</td>
                                          <td>Samantha</td>
                                          <td class="text-navy"> <i class="fa fa-level-up"></i> 24% </td>
                                      </tr>
                                      <tr>
                                          <td><span class="label label-warning">Canceled</span> </td>
                                          <td><i class="fa fa-clock-o"></i> 10:40am</td>
                                          <td>Monica</td>
                                          <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                      </tr>
                                      <tr>
                                          <td><small>Pending...</small> </td>
                                          <td><i class="fa fa-clock-o"></i> 01:30pm</td>
                                          <td>John</td>
                                          <td class="text-navy"> <i class="fa fa-level-up"></i> 54% </td>
                                      </tr>
                                      <tr>
                                          <td><small>Pending...</small> </td>
                                          <td><i class="fa fa-clock-o"></i> 02:20pm</td>
                                          <td>Agnes</td>
                                          <td class="text-navy"> <i class="fa fa-level-up"></i> 12% </td>
                                      </tr>
                                      <tr>
                                          <td><small>Pending...</small> </td>
                                          <td><i class="fa fa-clock-o"></i> 09:40pm</td>
                                          <td>Janet</td>
                                          <td class="text-navy"> <i class="fa fa-level-up"></i> 22% </td>
                                      </tr>
                                      <tr>
                                          <td><span class="label label-primary">Completed</span> </td>
                                          <td><i class="fa fa-clock-o"></i> 04:10am</td>
                                          <td>Amelia</td>
                                          <td class="text-navy"> <i class="fa fa-level-up"></i> 66% </td>
                                      </tr>
                                      <tr>
                                          <td><small>Pending...</small> </td>
                                          <td><i class="fa fa-clock-o"></i> 12:08am</td>
                                          <td>Damian</td>
                                          <td class="text-navy"> <i class="fa fa-level-up"></i> 23% </td>
                                      </tr>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                      <div class="col-lg-6">
                          <div class="dashboard-card ">
                              <div class="dashboard-card-header">
                                  <h5>Small todo list</h5>
                                  <div class="dashboard-card-tools">
                                      <a class="collapse-link">
                                          <i class="fa fa-chevron-up"></i>
                                      </a>
                                      <a class="close-link">
                                          <i class="fa fa-times"></i>
                                      </a>
                                  </div>
                              </div>
                              <div class="dashboard-card-body">
                                  <ul class="todo-list m-t small-list">
                                      <li>
                                          <a href="#" class="check-link"><i class="fa fa-check-square"></i> </a>
                                          <span class="m-l-xs todo-completed">Buy a milk</span>

                                      </li>
                                      <li>
                                          <a href="#" class="check-link"><i class="fa fa-square-o"></i> </a>
                                          <span class="m-l-xs">Go to shop and find some products.</span>

                                      </li>
                                      <li>
                                          <a href="#" class="check-link"><i class="fa fa-square-o"></i> </a>
                                          <span class="m-l-xs">Send documents to Mike</span>
                                          <small class="label label-primary"><i class="fa fa-clock-o"></i> 1 mins</small>
                                      </li>
                                      <li>
                                          <a href="#" class="check-link"><i class="fa fa-square-o"></i> </a>
                                          <span class="m-l-xs">Go to the doctor dr Smith</span>
                                      </li>
                                      <li>
                                          <a href="#" class="check-link"><i class="fa fa-check-square"></i> </a>
                                          <span class="m-l-xs todo-completed">Plan vacation</span>
                                      </li>
                                      <li>
                                          <a href="#" class="check-link"><i class="fa fa-square-o"></i> </a>
                                          <span class="m-l-xs">Create new stuff</span>
                                      </li>
                                      <li>
                                          <a href="#" class="check-link"><i class="fa fa-square-o"></i> </a>
                                          <span class="m-l-xs">Call to Anna for dinner</span>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="dashboard-card ">
                              <div class="dashboard-card-header">
                                  <h5>Transactions worldwide</h5>
                                  <div class="dashboard-card-tools">
                                      <a class="collapse-link">
                                          <i class="fa fa-chevron-up"></i>
                                      </a>
                                      <a class="close-link">
                                          <i class="fa fa-times"></i>
                                      </a>
                                  </div>
                              </div>
                              <div class="dashboard-card-body">

                                  <div class="row">
                                      <div class="col-lg-6">
                                          <table class="table table-hover margin bottom">
                                              <thead>
                                              <tr>
                                                  <th style="width: 1%" class="text-center">No.</th>
                                                  <th>Transaction</th>
                                                  <th class="text-center">Date</th>
                                                  <th class="text-center">Amount</th>
                                              </tr>
                                              </thead>
                                              <tbody>
                                              <tr>
                                                  <td class="text-center">1</td>
                                                  <td> Security doors
                                                      </td>
                                                  <td class="text-center small">16 Jun 2014</td>
                                                  <td class="text-center"><span class="label label-primary">$483.00</span></td>

                                              </tr>
                                              <tr>
                                                  <td class="text-center">2</td>
                                                  <td> Wardrobes
                                                  </td>
                                                  <td class="text-center small">10 Jun 2014</td>
                                                  <td class="text-center"><span class="label label-primary">$327.00</span></td>

                                              </tr>
                                              <tr>
                                                  <td class="text-center">3</td>
                                                  <td> Set of tools
                                                  </td>
                                                  <td class="text-center small">12 Jun 2014</td>
                                                  <td class="text-center"><span class="label label-warning">$125.00</span></td>

                                              </tr>
                                              <tr>
                                                  <td class="text-center">4</td>
                                                  <td> Panoramic pictures</td>
                                                  <td class="text-center small">22 Jun 2013</td>
                                                  <td class="text-center"><span class="label label-primary">$344.00</span></td>
                                              </tr>
                                              <tr>
                                                  <td class="text-center">5</td>
                                                  <td>Phones</td>
                                                  <td class="text-center small">24 Jun 2013</td>
                                                  <td class="text-center"><span class="label label-primary">$235.00</span></td>
                                              </tr>
                                              <tr>
                                                  <td class="text-center">6</td>
                                                  <td>Monitors</td>
                                                  <td class="text-center small">26 Jun 2013</td>
                                                  <td class="text-center"><span class="label label-primary">$100.00</span></td>
                                              </tr>
                                              </tbody>
                                          </table>
                                      </div>
                                      <div class="col-lg-6">
                                          <div id="world-map" style="height: 300px;"></div>
                                      </div>
                              </div>
                              </div>
                          </div>
                      </div>
                  </div>

              </div>


          </div>
          </div>


  </div>
  <div id="right-sidebar">
      <div class="sidebar-container">

          <ul class="nav nav-tabs navs-3">
              <li>
                  <a class="nav-link active" data-toggle="tab" href="#tab-1"> Notes </a>
              </li>
              <li>
                  <a class="nav-link" data-toggle="tab" href="#tab-2"> Projects </a>
              </li>
              <li>
                  <a class="nav-link" data-toggle="tab" href="#tab-3"> <i class="fa fa-gear"></i> </a>
              </li>
          </ul>

          <div class="tab-content">


              <div id="tab-1" class="tab-pane active">

                  <div class="sidebar-title">
                      <h3> <i class="fa fa-comments-o"></i> Latest Notes</h3>
                      <small><i class="fa fa-tim"></i> You have 10 new message.</small>
                  </div>

                  <div>

                      <div class="sidebar-message">
                          <a href="#">
                              <div class="float-left text-center">
                                  <img alt="image" class="rounded-circle message-avatar" src="">

                                  <div class="m-t-xs">
                                      <i class="fa fa-star text-warning"></i>
                                      <i class="fa fa-star text-warning"></i>
                                  </div>
                              </div>
                              <div class="media-body">

                                  There are many variations of passages of Lorem Ipsum available.
                                  <br>
                                  <small class="text-muted">Today 4:21 pm</small>
                              </div>
                          </a>
                      </div>
                      <div class="sidebar-message">
                          <a href="#">
                              <div class="float-left text-center">
                                  <img alt="image" class="rounded-circle message-avatar" src="">
                              </div>
                              <div class="media-body">
                                  The point of using Lorem Ipsum is that it has a more-or-less normal.
                                  <br>
                                  <small class="text-muted">Yesterday 2:45 pm</small>
                              </div>
                          </a>
                      </div>
                      <div class="sidebar-message">
                          <a href="#">
                              <div class="float-left text-center">
                                  <img alt="image" class="rounded-circle message-avatar" src="">

                                  <div class="m-t-xs">
                                      <i class="fa fa-star text-warning"></i>
                                      <i class="fa fa-star text-warning"></i>
                                      <i class="fa fa-star text-warning"></i>
                                  </div>
                              </div>
                              <div class="media-body">
                                  Mevolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                  <br>
                                  <small class="text-muted">Yesterday 1:10 pm</small>
                              </div>
                          </a>
                      </div>
                      <div class="sidebar-message">
                          <a href="#">
                              <div class="float-left text-center">
                                  <img alt="image" class="rounded-circle message-avatar" src="">
                              </div>

                              <div class="media-body">
                                  Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the
                                  <br>
                                  <small class="text-muted">Monday 8:37 pm</small>
                              </div>
                          </a>
                      </div>
                      <div class="sidebar-message">
                          <a href="#">
                              <div class="float-left text-center">
                                  <img alt="image" class="rounded-circle message-avatar" src="">
                              </div>
                              <div class="media-body">

                                  All the Lorem Ipsum generators on the Internet tend to repeat.
                                  <br>
                                  <small class="text-muted">Today 4:21 pm</small>
                              </div>
                          </a>
                      </div>
                      <div class="sidebar-message">
                          <a href="#">
                              <div class="float-left text-center">
                                  <img alt="image" class="rounded-circle message-avatar" src="">
                              </div>
                              <div class="media-body">
                                  Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                                  <br>
                                  <small class="text-muted">Yesterday 2:45 pm</small>
                              </div>
                          </a>
                      </div>
                      <div class="sidebar-message">
                          <a href="#">
                              <div class="float-left text-center">
                                  <img alt="image" class="rounded-circle message-avatar" src="">

                                  <div class="m-t-xs">
                                      <i class="fa fa-star text-warning"></i>
                                      <i class="fa fa-star text-warning"></i>
                                      <i class="fa fa-star text-warning"></i>
                                  </div>
                              </div>
                              <div class="media-body">
                                  The standard chunk of Lorem Ipsum used since the 1500s is reproduced below.
                                  <br>
                                  <small class="text-muted">Yesterday 1:10 pm</small>
                              </div>
                          </a>
                      </div>
                      <div class="sidebar-message">
                          <a href="#">
                              <div class="float-left text-center">
                                  <img alt="image" class="rounded-circle message-avatar" src="">
                              </div>
                              <div class="media-body">
                                  Uncover many web sites still in their infancy. Various versions have.
                                  <br>
                                  <small class="text-muted">Monday 8:37 pm</small>
                              </div>
                          </a>
                      </div>
                  </div>

              </div>

              <div id="tab-2" class="tab-pane">

                  <div class="sidebar-title">
                      <h3> <i class="fa fa-cube"></i> Latest projects</h3>
                      <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                  </div>

                  <ul class="sidebar-list">
                      <li>
                          <a href="#">
                              <div class="small float-right m-t-xs">9 hours ago</div>
                              <h4>Business valuation</h4>
                              It is a long established fact that a reader will be distracted.

                              <div class="small">Completion with: 22%</div>
                              <div class="progress progress-mini">
                                  <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                              </div>
                              <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                          </a>
                      </li>
                      <li>
                          <a href="#">
                              <div class="small float-right m-t-xs">9 hours ago</div>
                              <h4>Contract with Company </h4>
                              Many desktop publishing packages and web page editors.

                              <div class="small">Completion with: 48%</div>
                              <div class="progress progress-mini">
                                  <div style="width: 48%;" class="progress-bar"></div>
                              </div>
                          </a>
                      </li>
                      <li>
                          <a href="#">
                              <div class="small float-right m-t-xs">9 hours ago</div>
                              <h4>Meeting</h4>
                              By the readable content of a page when looking at its layout.

                              <div class="small">Completion with: 14%</div>
                              <div class="progress progress-mini">
                                  <div style="width: 14%;" class="progress-bar progress-bar-info"></div>
                              </div>
                          </a>
                      </li>
                      <li>
                          <a href="#">
                              <span class="label label-primary float-right">NEW</span>
                              <h4>The generated</h4>
                              <!--<div class="small float-right m-t-xs">9 hours ago</div>-->
                              There are many variations of passages of Lorem Ipsum available.
                              <div class="small">Completion with: 22%</div>
                              <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                          </a>
                      </li>
                      <li>
                          <a href="#">
                              <div class="small float-right m-t-xs">9 hours ago</div>
                              <h4>Business valuation</h4>
                              It is a long established fact that a reader will be distracted.

                              <div class="small">Completion with: 22%</div>
                              <div class="progress progress-mini">
                                  <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                              </div>
                              <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                          </a>
                      </li>
                      <li>
                          <a href="#">
                              <div class="small float-right m-t-xs">9 hours ago</div>
                              <h4>Contract with Company </h4>
                              Many desktop publishing packages and web page editors.

                              <div class="small">Completion with: 48%</div>
                              <div class="progress progress-mini">
                                  <div style="width: 48%;" class="progress-bar"></div>
                              </div>
                          </a>
                      </li>
                      <li>
                          <a href="#">
                              <div class="small float-right m-t-xs">9 hours ago</div>
                              <h4>Meeting</h4>
                              By the readable content of a page when looking at its layout.

                              <div class="small">Completion with: 14%</div>
                              <div class="progress progress-mini">
                                  <div style="width: 14%;" class="progress-bar progress-bar-info"></div>
                              </div>
                          </a>
                      </li>
                      <li>
                          <a href="#">
                              <span class="label label-primary float-right">NEW</span>
                              <h4>The generated</h4>
                              <!--<div class="small float-right m-t-xs">9 hours ago</div>-->
                              There are many variations of passages of Lorem Ipsum available.
                              <div class="small">Completion with: 22%</div>
                              <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                          </a>
                      </li>

                  </ul>

              </div>

              <div id="tab-3" class="tab-pane">

                  <div class="sidebar-title">
                      <h3><i class="fa fa-gears"></i> Settings</h3>
                      <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                  </div>

                  <div class="setings-item">
              <span>
                  Show notifications
              </span>
                      <div class="switch">
                          <div class="onoffswitch">
                              <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example">
                              <label class="onoffswitch-label" for="example">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                          </div>
                      </div>
                  </div>
                  <div class="setings-item">
              <span>
                  Disable Chat
              </span>
                      <div class="switch">
                          <div class="onoffswitch">
                              <input type="checkbox" name="collapsemenu" checked class="onoffswitch-checkbox" id="example2">
                              <label class="onoffswitch-label" for="example2">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                          </div>
                      </div>
                  </div>
                  <div class="setings-item">
              <span>
                  Enable history
              </span>
                      <div class="switch">
                          <div class="onoffswitch">
                              <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example3">
                              <label class="onoffswitch-label" for="example3">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                          </div>
                      </div>
                  </div>
                  <div class="setings-item">
              <span>
                  Show charts
              </span>
                      <div class="switch">
                          <div class="onoffswitch">
                              <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
                              <label class="onoffswitch-label" for="example4">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                          </div>
                      </div>
                  </div>
                  <div class="setings-item">
              <span>
                  Offline users
              </span>
                      <div class="switch">
                          <div class="onoffswitch">
                              <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example5">
                              <label class="onoffswitch-label" for="example5">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                          </div>
                      </div>
                  </div>
                  <div class="setings-item">
              <span>
                  Global search
              </span>
                      <div class="switch">
                          <div class="onoffswitch">
                              <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example6">
                              <label class="onoffswitch-label" for="example6">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                          </div>
                      </div>
                  </div>
                  <div class="setings-item">
              <span>
                  Update everyday
              </span>
                      <div class="switch">
                          <div class="onoffswitch">
                              <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example7">
                              <label class="onoffswitch-label" for="example7">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                          </div>
                      </div>
                  </div>

                  <div class="sidebar-content">
                      <h4>Settings</h4>
                      <div class="small">
                          I belive that. Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                          And typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                          Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                      </div>
                  </div>

              </div>
          </div>

      </div>



  </div>
</div>