  <div id="nav"> 
    <!--logo start-->
    <div class="profile">
      <div class="logo"><a href=""><img style="width:120px;" src="../asset/images/logo.gif" alt=""></a></div>
    </div><!--logo end--> 
    
    <!--navigation start-->
    <ul class="navigation">
<!--       <li><a class="active" href="index.html"><i class="fa fa-home"></i><span>Vendors</span></a></li>
      <li class="sub"> <a href="#"><i class="fa fa-smile-o"></i><span>UI Elements</span></a>
 -->
<?php
  $currFile = basename($_SERVER['PHP_SELF']); 
 ?>

 <li class="sub"> <a <?php if($currFile == 'dashboard.php' ) echo 'class="active"'; ?> href="dashboard.php"><i class="fa fa-sitemap"></i><span>Dashboard</span></a>
 <li class="sub"> <a <?php if($currFile == 'queries.php' ) echo 'class="active"'; ?> href="queries.php"><i class="fa fa-sitemap"></i><span>Queries</span></a>


<?php /*
<!-- <li class="sub"> <a <?php if($currFile == 'vendors.php' ||  $currFile == 'vendordeals.php' ||  $currFile == 'editvendor.php') echo 'class="active"'; ?> href="vendors.php"><i class="fa fa-briefcase"></i><span>Vendors</span></a>
<li class="sub"> <a <?php if($currFile == 'events.php' || $currFile == 'editevent.php') echo 'class="active"'; ?> href="events.php"><i class="fa fa-calendar-o"></i><span>Events</span></a>
<li class="sub"> <a <?php if($currFile == 'deals.php') echo 'class="active"'; ?> href="deals.php"><i class="fa fa-trophy"></i><span>Deals</span></a>
<li class="sub"> <a <?php if($currFile == 'promovendors.php') echo 'class="active"'; ?> href="promovendors.php"><i class="fa fa-bullhorn"></i><span>Promo Vendors</span></a>
<li class="sub"> <a <?php if($currFile == 'subscribers.php') echo 'class="active"'; ?> href="subscribers.php"><i class="fa fa-bullhorn"></i><span>Subscribers</span></a>
<li class="sub"> <a <?php if($currFile == 'queries.php') echo 'class="active"'; ?> href="queries.php"><i class="fa fa-bullhorn"></i><span>Queries</span></a>
 --> */ ?>
  <!--         <ul class="navigation-sub">
          <li><a href="buttons.html"><i class="fa fa-power-off"></i><span>Button</span></a></li>
          <li><a href="grids.html"><i class="fa fa-columns"></i><span>Grid</span></a></li>
          <li><a href="icons.html"><i class="fa fa-flag"></i><span>Icon</span></a></li>
          <li><a href="tab-accordions.html"><i class="fa fa-plus-square-o"></i><span>Tab / Accordion</span></a></li>
          <li><a href="nestable.html"><i class="fa  fa-arrow-circle-o-down"></i><span>Nestable</span></a></li>
          <li><a href="slider.html"><i class="fa fa-font"></i><span>Slider</span></a></li>
          <li><a href="timeline.html"><i class="fa fa-filter"></i><span>Timeline</span></a></li>
          <li><a href="gallery.html"><i class="fa fa-picture-o"></i><span>Gallery</span></a></li>
        </ul>
 -->      </li>
<!--       <li class="sub"><a href="#"><i class="fa fa-list-alt"></i><span>Forms</span></a>
        <ul class="navigation-sub">
          <li><a href="form-components.html"><i class="fa fa-table"></i><span>Components</span></a></li>
          <li><a href="form-validation.html"><i class="fa fa-leaf"></i><span>Validation</span></a></li>
          <li><a href="form-wizard.html"><i class="fa fa-th"></i><span>Wizard</span></a></li>
          <li><a href="input-mask.html"><i class="fa fa-laptop"></i><span>Input Mask</span></a></li>
          <li><a href="muliti-upload.html"><i class="fa fa-files-o"></i><span>Multi Upload</span></a></li>
        </ul>
      </li> -->
<!--       <li class="sub"><a href="#"><i class="fa fa-table"></i><span>Table</span></a>
        <ul class="navigation-sub">
          <li><a href="basic-tables.html"><i class="fa fa-table"></i><span>Basic Table</span></a></li>
          <li><a href="data-tables.html"><i class="fa fa-columns"></i><span>Data Table</span></a></li>
        </ul>
      </li>
      <li><a href="fullcalendar.html"><i class="fa fa-calendar nav-icon"></i><span>Calendar</span></a></li>
      <li><a href="charts.html"><i class="fa fa-bar-chart-o"></i><span>Charts</span></a></li>
      <li class="sub"><a href="#"><i class="fa fa-folder-open-o"></i><span>Pages</span></a>
        <ul class="navigation-sub">
          <li><a href="404-error.html"><i class="fa fa-warning"></i><span>404 Error</span></a></li>
          <li><a href="500-error.html"><i class="fa fa-warning"></i><span>500 Error</span></a></li>
          <li><a href="balnk-page.html"><i class="fa fa-copy"></i><span>Blank Page</span></a></li>
          <li><a href="profile.html"><i class="fa fa-user"></i><span>Profile</span></a></li>
          <li><a href="login.html"><i class="fa fa-sign-out"></i><span>Login</span></a></li>
          <li><a href="map.html"><i class="fa fa-map-marker"></i><span>Map</span></a></li>
        </ul>
      </li> -->
    </ul><!--navigation end--> 
  </div><!--Left navbar end--> 


  <div id="confirm" class="modal fade" style="z-index:99999!important">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- dialog body -->



      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
      </div>
  
      <div class="modal-body">
          <p>Are you sure?</p>
      </div>
      
      <!-- dialog buttons -->
      <div class="modal-footer">

      <img src="images/spinner.gif" id="del_spinner" style="display:none;">
      <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete" >Yes</button>
      <button type="button" data-dismiss="modal" class="btn">No</button>
 
      </div>
    </div>
  </div>
</div>


<div id="message_user" class="modal fade" style="z-index:99999!important">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- dialog body -->

      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Send Message</h4>
      </div>
  
      <div class="modal-body">
        <form role="form">
                        <div class="form-group">
                            <label class="control-label" for="recipient-name"><font><font>User Email:</font></font></label>
                            <input type="text" id="user_email" class="form-control" readonly="">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="recipient-name"><font><font>Subject:</font></font></label>
                            <input type="text" id="email_subject" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="message-text"><font><font>Message:</font></font></label>
                            <textarea id="email_body" onkeyup="$('#email_body').removeClass('error-class');" style="height:200px;" class="form-control"></textarea>
                        </div>
                    </form>
      </div>
      
      <!-- dialog buttons -->
      <div class="modal-footer">
      <img src="images/spinner.gif" id="del_spinner" style="display:none;">
      <button type="button" data-dismiss="modal" onclick="sendEmail();" class="btn btn-primary" id="delete" >Send</button>
      <button type="button" data-dismiss="modal" class="btn">Close</button> 
      </div>
    </div>
  </div>
</div>



<div id="edit_query" class="modal fade" style="z-index:99999!important">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- dialog body -->
      <input type="hidden" id="query_id" value="">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Edit</h4>
      </div>
  
      <div class="modal-body">
        
      <form class="form-horizontal" role="form">
       

      



        <div class="form-group">
           
          <label for="name" class="col-sm-5 control-label">
            Name
          </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="parent_name" onkeyup="$(this).removeClass('has-error');">
          </div>
        </div>

        <div class="form-group">
           
          <label for="childName" class="col-sm-5 control-label">
            Name your child
          </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="child_name" onkeyup="$(this).removeClass('has-error');">
          </div>
        </div>

          <div class="form-group" id="age_div">
           
          <label for="age" class="col-sm-5 control-label">
            Date of birth
          </label>
          <div class="col-sm-2">
          <input type="text" id="dob" class="form-control" style="width:100px;">
          <!-- <select class="selectpicker" data-width="100%" id="years" onchange="$('#age_div').removeClass('has-error');">
               <option value="">Years</option>
               <option value="0">0</option>
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>
            </select>
            </div>
            <div class="col-sm-2">
          <select class="selectpicker" id="months"  data-width="100%" onchange="$('#age_div').removeClass('has-error');">
               <option value="">Months</option>
               <option value="0">0</option>
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>
               <option value="4">4</option>
               <option value="5">5</option>
               <option value="6">6</option>
               <option value="7">7</option>
               <option value="8">8</option>
               <option value="9">9</option>
               <option value="10">10</option>
               <option value="11">eleven</option>
            </select>
            </div>
            <div class="col-sm-2">
            <label for="age" class="col-sm-2 control-label">
            Days
          </label>
            <select class="selectpicker" id="days" data-width="100%" onchange="$('#age_div').removeClass('has-error');">
               <option value="">Days</option>
               <option value="0">0</option>
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>
               <option value="4">4</option>
               <option value="5">5</option>
               <option value="6">6</option>
               <option value="7">7</option>
               <option value="8">8</option>
               <option value="9">9</option>
               <option value="10">10</option>
               <option value="11">eleven</option>
               <option value="12">12</option>
               <option value="13">13</option>
               <option value="14">14</option>
               <option value="15">fifteen</option>
               <option value="16">16</option>
               <option value="17">17</option>
               <option value="18">18</option>
               <option value="19">19</option>
               <option value="20">20</option>
               <option value="21">21</option>
               <option value="22">22</option>
               <option value="23">23</option>
               <option value="24">24</option>
               <option value="25">25</option>
               <option value="26">26</option>
               <option value="27">27</option>
               <option value="28">28</option>
               <option value="29">29</option>
               <option value="30">30</option>
            </select> -->
            </div>
        </div>
        
        <div class="form-group">
           
          <label for="branch_office" class="col-sm-5 control-label">
            Branch Office
          </label>
          <div class="col-sm-6">
            <select class="selectpicker" id="branch_office" onchange="changebranch(this.value);">
               <option value="Escandon">Escandon</option>
               <option value="San Jeronimo">San Jeronimo</option>
               <option value="San Angel">San Angel</option>
               <option value="other">Testify in area - specify</option>
            </select>
            <br><br>
            <input type="text" class="form-control" id="branch_office_other" placeholder="Other" style="display:none;" onkeyup="$(this).removeClass('has-error');">
          </div>
        </div>        

          <div class="form-group">
           
          <label for="hours" class="col-sm-5 control-label">
            Required Hours
          </label>
          <div class="col-sm-5">
          <label for="start_time" class="col-sm-6 control-label" style="text-align:left; width:60px;">
            From
          </label>
          <select class="selectpicker" id="start_time" data-width="25%">
               <option value="07:00">07:00 hrs</option>
               <option value="08:00">08:00 hrs</option>
               <option value="09:00">09:00 hrs</option>
               <option value="10:00">10:00 hrs</option>
               <option value="11:00">11:00 hrs</option>
               <option value="12:00">12:00 hrs</option>
               <option value="13:00">13:00 hrs</option>
               <option value="14:00">14:00 hrs</option>
               <option value="15:00">15:00 hrs</option>
               <option value="16:00">16:00 hrs</option>
               <option value="17:00">17:00 hrs</option>
               <option value="18:00">18:00 hrs</option>
            </select>
            <b>To</b>
               
               <select class="selectpicker" id="end_time" data-width="25%">
               <option value="08:00">08:00 hrs</option>
               <option value="09:00">09:00 hrs</option>
               <option value="10:00">10:00 hrs</option>
               <option value="11:00">11:00 hrs</option>
               <option value="12:00">12:00 hrs</option>
               <option value="13:00">13:00 hrs</option>
               <option value="14:00">14:00 hrs</option>
               <option value="15:00">15:00 hrs</option>
               <option value="16:00">16:00 hrs</option>
               <option value="17:00">17:00 hrs</option>
               <option value="18:00">18:00 hrs</option>
               <option value="19:00">19:00 hrs</option>
            </select>
            </div>

        </div>

        <div class="form-group">
           
          <label for="inputEmail3" class="col-sm-5 control-label">
            Email
          </label>
          <div class="col-sm-6">
            <input type="email" class="form-control" id="email" onkeyup="$(this).removeClass('has-error');">
          </div>
        </div>
        <div class="form-group">
           
          <label for="phone" class="col-sm-5 control-label">
            Phone
          </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="phone" onkeyup="$(this).removeClass('has-error');">
          </div>
        </div>

        <div class="form-group">
           
          <label for="services" class="col-sm-5 control-label">
            What services interest you 
          </label>
          <div class="col-sm-6">
          <div class="checkbox checkbox-primary">
              <input id="checkbox1" class="services" type="checkbox" value="Web Cameras">
              <label for="checkbox1">
                  Web Cameras
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox2" class="services" type="checkbox" value="Early Stimulation">
              <label for="checkbox2">
                  Early Stimulation
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox3" class="services" type="checkbox" value="English">
              <label for="checkbox3">
                  English
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox4" class="services" type="checkbox" value="Kindergarten">
              <label for="checkbox4">
                  Kindergarten
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox5" class="services" type="checkbox" value="Nursery Express">
              <label for="checkbox5">
                  Nursery Express
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox6" class="services" type="checkbox" value="Infants">
              <label for="checkbox6">
                  Infants
              </label>
          </div>
          <div class="checkbox checkbox-primary">
              <input id="checkbox7" class="services" type="checkbox" value="Maternal">
              <label for="checkbox7">
                  Maternal
              </label>
          </div>


            <input type="text" class="form-control" id="other_service" placeholder="other" onkeyup="$(this).removeClass('has-error');">
          </div>
        </div>

        <div class="form-group">
           
          <label for="branch_office" class="col-sm-5 control-label">
            How did he find out about us
          </label>
          <div class="col-sm-6">
            <select class="selectpicker" id="refer_to" onChange="changeReferBy(this.value);">
               <option value="recommendation">Recommendation</option>
               <option value="google">Google</option>
               <option value="bing">Bing</option>
               <option value="youtube">Youtube</option>
               <option value="facebook">Facebook</option>
               <option value="external advertising">External advertising</option>
               <option value="other">Other - specify</option>
            </select>
            <br><br>
            <input type="text" style="display:none; margin-top:15px;" class="form-control" id="refer_to_other" placeholder="other" onkeyup="$(this).removeClass('has-error');">
          </div>
        </div>


      </form>


      </div>
      
      <!-- dialog buttons -->
      <div class="modal-footer">
      <img src="images/spinner.gif" id="del_spinner" style="display:none;">
      <button type="button" data-dismiss="modal" onclick="validation();" class="btn btn-primary" id="delete" >Send</button>
      <button type="button" data-dismiss="modal" class="btn">Close</button> 
      </div>
    </div>
  </div>
</div>


<div id="query_detail" class="modal fade" style="z-index:99999!important">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- dialog body -->



      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel"> Query Details</h4>
      </div>
  
      <div class="modal-body" id="detail_query_body">
<!--         <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Parent Name</th>
                    <td id="parent_name"></td>
                  </tr>
                  <tr>
                    <th>Child Name</th>
                    <td id="child_name"></td>
                  </tr>
                  <tr>
                    <th>Date Of Birth</th>
                    <td id="dob"></td>
                  </tr>
                  <tr>
                    <th>Age</th>
                    <td id="age"></td>
                  </tr>
                  <tr id="filename">
                    <th>File Name</th>
                    <td id="file_name"></td>
                  </tr>
                  <tr>
                    <th>Branch Office</th>
                    <td id="branch_office"></td>
                  </tr>
                  <tr>
                    <th>Strat Time</th>
                    <td id="start_time"></td>
                  </tr>
                  <tr>
                    <th>End Time</th>
                    <td id="end_time"></td>
                  </tr>
                  <tr>
                    <th>Email</th>
                    <td id="email"></td>
                  </tr>
                  <tr>
                    <th>Phone</th>
                    <td id="phone"></td>
                  </tr>
                  <tr>
                    <th>Refer By</th>
                    <td id="refer_by"></td>
                  </tr>
                  <tr>
                    <th>Date Created</th>
                    <td id="date_created"></td>
                  </tr>
                  <tr>
                    <th style="vertical-align: top;">Services</th>
                    <td id="services"></td>
                  </tr>

                </thead>
                <tbody id="detail">

                </tbody>
              </table> -->

      </div>
      
      <!-- dialog buttons -->
      <div class="modal-footer">

      <img src="images/spinner.gif" id="del_spinner" style="display:none;">
      <button type="button" data-dismiss="modal" class="btn">Close</button>
 
      </div>
    </div>
  </div>
</div>
