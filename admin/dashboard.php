<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>:: Babystar :: </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include('inc/js.php') ;?>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

<script>
$(function () {
  getReportingData()
});
</script>
</head>
<body>
<!--layout-container start-->
<div id="layout-container"> 
  <!--Left navbar start-->
  
<?php
  include('inc/left.php');
?>
  <!--main start-->

  <div id="main">




  <?php
    include('inc/nav.php');
  ?>
    <!--margin-container start-->
    <div class="margin-container">
    <!--scrollable wrapper start-->
      <div class="scrollable wrapper">

        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>


        <div id="branch_month_container" style="margin-top:10px;"></div>
        <div id="branch_year_container" style="margin-top:10px;"></div>

        <div id="referby_month_container" style="margin-top:10px;"></div>
        <div id="referby_year_container" style="margin-top:10px;"></div>





          <div class="row" style="margin-top:10px;">
            <div class="col-lg-12">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Escandon</h3>
                </div>
                <div class="panel-body">
                  <div id="Escandon"></div>
                </div>
              </div>
            </div>
            </div>

          <div class="row" style="margin-top:10px;">
            <div class="col-lg-12">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">San Jeronimo</h3>
                </div>
                <div class="panel-body">
                  <div id="San-Jeronimo"></div>
                </div>
              </div>
            </div>
            </div>

          <div class="row" style="margin-top:10px;">
            <div class="col-lg-12">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">San Angel</h3>
                </div>
                <div class="panel-body">
                  <div id="San-Angel"></div>
                </div>
              </div>
            </div>
            </div>

           <div class="row" style="margin-top:10px;">
            <div class="col-lg-12">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Monthly Branch Channels comparison</h3>
                </div>
                <div class="panel-body">
                  <div id="monthly_refer_by"></div>
                </div>
              </div>
            </div>
            </div>


           <div class="row" style="margin-top:10px;">
            <div class="col-lg-12">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Yearly Branch Channels comparison</h3>
                </div>
                <div class="panel-body">
                  <div id="yearly_refer_by"></div>
                </div>
              </div>
            </div>
            </div>

          <div class="row" style="margin-top:10px;">
            <div class="col-lg-4">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Downloads in this month</h3>
                </div>
                <div class="panel-body">
                  <table class="table">
                    <thead>
                    <tr><td id="last_month_str"></td><td id="last_month_download"></td></tr>
                    <tr><td id="cur_month_str"></td><td id="cur_month_download"></td></tr>
                    <tr><td id="cur_percentage_str"></td><td id="cur_percentage"></td></tr>                    
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Downloads in this year</h3>
                </div>
                <div class="panel-body">
                  <table class="table">
                    <thead>                  
                    <tr><td id="last_year_str"></td><td id="last_year_download"></td></tr>
                    <tr><td id="cur_year_str"></td><td id="cur_year_download"></td></tr>
                    <tr><td id="cur_year_percentage_str"></td><td id="cur_year_percentage"></td></tr>                    
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-4">

              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Other branches</h3>
                </div>
                <div class="panel-body" id="readmore">
                </div>
              </div>


            </div>
          </div>

      <div class="row" style="margin-top:10px;">
            
            <div class="col-lg-4">

              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Other Services</h3>
                </div>
                <div class="panel-body" id="readmoreservices">
                </div>
              </div>


            </div>
          </div>



      </div><!--scrollable wrapper end--> 

    </div><!--margin-container end--> 
  </div><!--main end--> 
</div><!--layout-container end--> 
<script>
</script>

</body>
</html>