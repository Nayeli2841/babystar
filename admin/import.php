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
$( document ).ready(function() {
  adminData();
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
      <!--row start-->
        <div class="row">
         <!--col-md-12 start-->
          <div class="col-md-12">
            <div class="page-heading">
              <h1>Profile </h1>
            </div>
          </div><!--col-md-12 end-->
          <div class="col-sm-6 col-md-12">
            <div class="box-info">
              
                <div class="container">
                <div class="row clearfix">
                  <div class="col-md-10 column">
                    <div class="notification-bar" id="msg" style="display: none;"></div>
                    <form class="form-horizontal" role="form" onsubmit="return false;">
                      <div class="form-group">
                         <label for="inputEmail3" class="col-sm-2 control-label">Field</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="field" />
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <div class="container">
              <div class="row clearfix">
                <div class="col-md-10 column">
                  <form class="form-horizontal" role="form" onsubmit="return false;">
                     <div class="form-group">
                               <label for="inputEmail3" class="col-sm-2 control-label">Image</label>
                              <div class="col-sm-5">
                    <!--             <input type="text" class="form-control" id="logo" /> -->

                            <input type="file" id="csv" name="csv" data-url="../api/csv_upload" class="file-pos">

                            <img src="images/spinner.gif" id="csv_spinner" style="display:none;">
                    <!--         <span>Preferred size: 150 X 75</span> -->
                            <input type="hidden" value="" id="path">

                              </div>
                            </div>                  </form>
                </div>
              </div>
            </div>

        <img src="images/spinner.gif" id="spinner" style="position:absolute; right:150px; display:none;">
        <button type="button" onclick="importCSV();" class="btn btn-primary">Submit</button>
        </div>
  </div>


</div>

            </div>
          </div>

        </div><!--row end-->
      </div><!--scrollable wrapper end--> 
    </div><!--margin-container end--> 
  </div><!--main end--> 
</div><!--layout-container end--> 
<script>
    $('#csv').fileupload({
        dataType: 'json',
        done: function (e, data) {
          $('#image_spinner').hide();          
       $('#path').val(data.result.file_name);
        },
        send: function (e, data) {
          $('#image_spinner').show();
        }
    });
</script>

</body>
</html>