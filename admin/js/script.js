var sortBy = '';
var sortOrder = '';
var server = window.location.hostname;
var webUrl = location.protocol + "//"+server+"/babystar/"
if(server == 'localhost')
{
  var apiUrl = location.protocol + "//"+server+"/babystar/api/";
}
else
  var apiUrl = location.protocol + "//"+server+"/babystar/api/";


function showMsg(id, msg, type)
{
    $(id).html(msg).addClass(type).slideDown('fast').delay(2500).slideUp(1000,function(){$(id).removeClass(type)}); 
}

function exportData()
{
    var startDate = $.trim($('#start_date').val());
    var endDate = $.trim($('#end_date').val());
    var check = true;

    if(startDate == '')
    {
        $('#start_date').addClass('error-class');
        check = false;
    }

    if(endDate == '')
    {
        $('#end_date').addClass('error-class');
        check = false;
    }

    return check;
}

function importCSV()
{
    var field = $.trim($('#field').val());
    var path = $.trim($('#path').val());
    check = true;
    if(field == '')
    {
        $('#field').focus();
        $('#field').addClass('error-class');
        check = false;        
    }

    if(path == '')
    {
        $('#path').focus();
        $('#path').addClass('error-class');
        check = false;        
    }

    if(check)
    {
      $('#csv_spinner').show();
        $.ajax({
          type: 'POST',
          url: apiUrl + 'import_csv',
          dataType : "JSON",
          data: { field: field, path: path },
          beforeSend:function(){

          },
          success:function(data){
            $('#csv_spinner').hide();
            if(data.status == 'success')
            {
                showMsg('#msg', 'Record imported sucessfully', 'green')
                document.reload();
            }
          },
          error:function(jqxhr){
            $('#csv_spinner').hide();            
            showMsg('#msg', 'Some error happened appeared while import', 'red')
          }
        });        
    }
}

function login()
{
    var email = $.trim($('#email').val());
    var password = $.trim($('#password').val());    
    var check = true;

    if(email == '')
    {
        $('#email').focus();
        $('#email').addClass('error-class');
        check = false;
    }

    if(password == '')
    {
        $('#password').focus();
        $('#password').addClass('error-class');
        check = false;
    }

    if(check)
    {
        $('#spinner').show();
        $.ajax({
          type: 'POST',
          url: apiUrl + 'login',
          dataType : "JSON",
          data: { username: email, password: password },
          beforeSend:function(){

          },
          success:function(data){
            $('#spinner').hide();
            if(data.status == 'success')
            {
                showMsg('#msg', 'Successfully Logged In. Redirecting ...', 'green')
                window.location = 'dashboard.php';
            }
          },
          error:function(jqxhr){
            $('#spinner').hide();            
            showMsg('#msg', 'Wrong credentials. Try Again', 'red')
          }
        });

    }
}

function getReportingData()
{
    $.ajax({
      type: 'GET',
      url: apiUrl + 'reporting',
      dataType : "JSON",
      data: {},
      beforeSend:function(){

      },
      success:function(data){

        // Downloads in current month
        $('#cur_month_str').html(data.data.cur_month.current);
        $('#cur_month_download').html(data.data.cur_month.current_downloads);
        $('#last_month_str').html(data.data.cur_month.last);
        $('#last_month_download').html(data.data.cur_month.last_downloads);
        if(data.data.cur_month.percentage != '-')
        {
          $('#cur_percentage').html(data.data.cur_month.percentage + '%');
          if(data.data.cur_month.percentage < 0)
            var curPercentageStr = 'Downloads decreased by ';
          else
            var curPercentageStr = 'Downloads increased by ';
          $('#cur_percentage_str').html(curPercentageStr);          
        }

        $('#cur_year_str').html(data.data.last_year.current);
        $('#cur_year_download').html(data.data.last_year.current_downloads);
        $('#last_year_str').html(data.data.last_year.last);
        $('#last_year_download').html(data.data.last_year.last_downloads);
        if(data.data.last_year.percentage != '-')
        {
          $('#cur_year_percentage').html(data.data.last_year.percentage + '%');
          if(data.data.last_year.percentage < 0)
            var curPercentageStr = 'Downloads decreased by ';
          else
            var curPercentageStr = 'Downloads increased by ';
          $('#cur_year_percentage_str').html(curPercentageStr);          
        }

        //Other branches
        var otherBranches = '';
        $.each(data.data.other_branches, function( index, value ) {
          otherBranches += value + '<br>';
        });

        $('#readmore').html(otherBranches);

        $('#readmore').readmore({
          speed: 75,
          lessLink: '<a href="#" style="margin-left:245px;">Read less</a>',
          moreLink: '<a href="#" style="margin-left:245px;">More branches</a>'
        });

        //other services
        var otherServices = '';
        $.each(data.data.other_services, function( index, value ) {
          otherServices += value + '<br>';
        });

        $('#readmoreservices').html(otherServices);

        $('#readmoreservices').readmore({
          speed: 75,
          lessLink: '<a href="#" style="margin-left:245px;">Read less</a>',
          moreLink: '<a href="#" style="margin-left:245px;">More Services</a>'
        });        



      $('#monthly_refer_by').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories:data.data.monthly_brnach_referrals.branches,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Percentage (%)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}%</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: data.data.monthly_brnach_referrals.data
    });


      $('#yearly_refer_by').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories:data.data.yearly_brnach_referrals.branches,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Percentage (%)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}%</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: data.data.yearly_brnach_referrals.data
    });




        $.each(['Escandon', 'San Jeronimo', 'San Angel'], function( index, value ) {
          if(value == 'San Jeronimo')
            var id = 'San-Jeronimo';
          else if(value == 'San Angel')
            var id = 'San-Angel';
          else 
            id = 'Escandon';

          $('#' + id).highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        showInLegend: false,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                name: value,
                colorByPoint: true,
                data: data.data.services_comparison[value]
              }]
            });

        });



  // Set up the chart
    var chart = new Highcharts.Chart({
        xAxis: {
            categories: ['Downloads (Last month)', 'Downloads (this month)'],
            id: 'x-axis'
        },      
        chart: {
            renderTo: 'branch_month_container',
            type: 'column',
            margin: 75,
            options3d: {
                enabled: false,
                alpha: 0,
                beta: 0,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: 'Downloads by branch (Last month comparison)'
        },
        subtitle: {
            text: data.data.last_from + ' - ' + data.data.last_to + ' | ' + data.data.cur_from + ' - ' + data.data.cur_to
        },
        plotOptions: {
            column: {
                depth: 0
            }
        },
       tooltip: {
            headerFormat: '',
            pointFormat: ' {point.y} Downloads'
        },        
        series: data.data.cur_month_branch
    });

    var chart = new Highcharts.Chart({
        xAxis: {
            categories: ['Downloads (Last year)', 'Downloads (this year)'],
            id: 'x-axis'
        },      
        chart: {
            renderTo: 'branch_year_container',
            type: 'column',
            margin: 75,
            options3d: {
                enabled: false,
                alpha: 0,
                beta: 0,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: 'Downloads by branch (Last year comparison)'
        },
        subtitle: {
            text: data.data.last_from + ' - ' + data.data.last_to + ' | ' + data.data.cur_from + ' - ' + data.data.cur_to
        },
        plotOptions: {
            column: {
                depth: 0
            }
        },
       tooltip: {
            headerFormat: '',
            pointFormat: ' {point.y} Downloads'
        },        
        series: data.data.cur_year_branch
    });

    var chart = new Highcharts.Chart({
        xAxis: {
            categories: ['Downloads (Last month)', 'Downloads (this month)'],
            id: 'x-axis'
        },      
        chart: {
            renderTo: 'referby_month_container',
            type: 'column',
            margin: 75,
            options3d: {
                enabled: false,
                alpha: 0,
                beta: 0,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: 'Downloads by referral (Last month comparison)'
        },
        subtitle: {
            text: data.data.last_from + ' - ' + data.data.last_to + ' | ' + data.data.cur_from + ' - ' + data.data.cur_to
        },
        plotOptions: {
            column: {
                depth: 0
            }
        },
       tooltip: {
            headerFormat: '',
            pointFormat: ' {point.y} Downloads'
        },        
        series: data.data.cur_month_referby
    });

    var chart = new Highcharts.Chart({
        xAxis: {
            categories: ['Downloads (Last year)', 'Downloads (this year)'],
            id: 'x-axis'
        },      
        chart: {
            renderTo: 'referby_year_container',
            type: 'column',
            margin: 75,
            options3d: {
                enabled: false,
                alpha: 0,
                beta: 0,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: 'Downloads by referral (Last year comparison)'
        },
        subtitle: {
            text: data.data.last_from + ' - ' + data.data.last_to + ' | ' + data.data.cur_from + ' - ' + data.data.cur_to
        },
        plotOptions: {
            column: {
                depth: 0
            }
        },
       tooltip: {
            headerFormat: '',
            pointFormat: ' {point.y} Downloads'
        },        
        series: data.data.cur_year_referby
    });










        $('#container').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Downloads per year'
        },
        subtitle: {
//            text: 'Downloads Per day'
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Date'
            }
        },
        yAxis: {
            title: {
                text: 'Number of download'
            },
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: ' {point.y} Downloads'
        },

        plotOptions: {
            spline: {
                marker: {
                    enabled: true
                }
            }
        },

        // series: [{
        //     name: "Winter 2012-2013",
        //     // Define the data points. All series have a dummy year
        //     // of 1970/71 in order to be compared on the same x axis. Note
        //     // that in JavaScript, months start at 0 for January, 1 for February etc.
        //     data: [
        //         [Date.UTC(1970, 9, 21), 0],
        //         [Date.UTC(1970, 10, 4), 0.28],
        //         [Date.UTC(1970, 10, 9), 0.25],
        //         [Date.UTC(1970, 10, 27), 0.2],
        //         [Date.UTC(1970, 11, 2), 0.28],
        //         [Date.UTC(1970, 11, 26), 0.28],
        //         [Date.UTC(1970, 11, 29), 0.47],
        //         [Date.UTC(1971, 0, 11), 0.79],
        //         [Date.UTC(1971, 0, 26), 0.72],
        //         [Date.UTC(1971, 1, 3), 1.02],
        //         [Date.UTC(1971, 1, 11), 1.12],
        //         [Date.UTC(1971, 1, 25), 1.2],
        //         [Date.UTC(1971, 2, 11), 1.18],
        //         [Date.UTC(1971, 3, 11), 1.19],
        //         [Date.UTC(1971, 4, 1), 1.85],
        //         [Date.UTC(1971, 4, 5), 2.22],
        //         [Date.UTC(1971, 4, 19), 1.15],
        //         [Date.UTC(1971, 5, 3), 0]
        //     ]
        // }, {
        //     name: "Winter 2013-2014",
        //     data: [
        //         [Date.UTC(1970, 9, 29), 0],
        //         [Date.UTC(1970, 10, 9), 0.4],
        //         [Date.UTC(1970, 11, 1), 0.25],
        //         [Date.UTC(1971, 0, 1), 1.66],
        //         [Date.UTC(1971, 0, 10), 1.8],
        //         [Date.UTC(1971, 1, 19), 1.76],
        //         [Date.UTC(1971, 2, 25), 2.62],
        //         [Date.UTC(1971, 3, 19), 2.41],
        //         [Date.UTC(1971, 3, 30), 2.05],
        //         [Date.UTC(1971, 4, 14), 1.7],
        //         [Date.UTC(1971, 4, 24), 1.1],
        //         [Date.UTC(1971, 5, 10), 0]
        //     ]
        // }, {
        //     name: "Winter 2014-2015",
        //     data: [
        //         [Date.UTC(1970, 10, 25), 0],
        //         [Date.UTC(1970, 11, 6), 0.25],
        //         [Date.UTC(1970, 11, 20), 1.41],
        //         [Date.UTC(1970, 11, 25), 1.64],
        //         [Date.UTC(1971, 0, 4), 1.6],
        //         [Date.UTC(1971, 0, 17), 2.55],
        //         [Date.UTC(1971, 0, 24), 2.62],
        //         [Date.UTC(1971, 1, 4), 2.5],
        //         [Date.UTC(1971, 1, 14), 2.42],
        //         [Date.UTC(1971, 2, 6), 2.74],
        //         [Date.UTC(1971, 2, 14), 2.62],
        //         [Date.UTC(1971, 2, 24), 2.6],
        //         [Date.UTC(1971, 3, 2), 2.81],
        //         [Date.UTC(1971, 3, 12), 2.63],
        //         [Date.UTC(1971, 3, 28), 2.77],
        //         [Date.UTC(1971, 4, 5), 2.68],
        //         [Date.UTC(1971, 4, 10), 2.56],
        //         [Date.UTC(1971, 4, 15), 2.39],
        //         [Date.UTC(1971, 4, 20), 2.3],
        //         [Date.UTC(1971, 5, 5), 2],
        //         [Date.UTC(1971, 5, 10), 1.85],
        //         [Date.UTC(1971, 5, 15), 1.49],
        //         [Date.UTC(1971, 5, 23), 1.08]
        //     ]
        // }]
        series: data.data.chart
    });
      },
      error:function(jqxhr){
      }
    });

}

function logout()
{
    $.ajax({
      type: 'GET',
      url: apiUrl + 'logout',
      dataType : "JSON",
      data: {},
      beforeSend:function(){

      },
      success:function(data){
        window.location = 'login.php';
      },
      error:function(jqxhr){
      }
    });
}


function sortbyFunc(sort, sortbystr, module, status)
{
  sortBy = sortbystr;
  obj = '.'+sortbystr;
  if(sort == 'all')
  {
    sortOrder = 'asc';
    $('.fa-sort').show();
    $('.fa-sort-asc').hide();
    $('.fa-sort-desc').hide();
    $(obj+'all').hide();
    $(obj+'desc').hide();
    $(obj+'asc').show();
  }
  else if(sort == 'asc')
  {
    sortOrder = 'desc';
    $('.fa-sort').show();
    $(obj+'all').hide();
    $(obj+'asc').hide();
    $(obj+'desc').show();
  }
  else if(sort == 'desc')
  {
    sortOrder = 'asc';
    $('.fa-sort').show();
    $(obj+'all').hide();
    $(obj+'desc').hide();
    $(obj+'asc').show();
  }

  if(module == 'vendors')
    getAllVendors(status)
  else if(module == 'events')
    getAllEvents(status)
  else if(module == 'deals')
    getDeals(status, 1);
  else if(module == 'promo')
    getPromoVendors(status , 1);
  else if(module == 'subscriber')
    getSubscribers(status);  
}

function removeElm(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

function adminData()
{
  $.ajax({
           type: "GET",
           url: apiUrl + 'admindata',
           dataType : "JSON",
           beforeSend:function(){


           },
           success:function(data){
           $('#spinner').hide();      
             if(data.status == 'success')
             {

                $('#name').val(data.data.name);
                $('#email').val(data.data.username);
             }
           },
           error:function(jqxhr){
             $('#spinner').hide();      
             showMsg('#msg', 'error.', 'red');
           }
         });
}

function updateAdminData()
{
  var name = $('#name').val();
  var email = $('#email').val();

  var check = true;

   if(name == '')
     {
         $('#name').focus();
         $('#name').addClass('error-class');
         check = false;
     }
     else if(email == '')
     {
         $('#email').focus();
         $('#email').addClass('error-class');
         check = false;
     }

     if(check)
     {
        $.ajax({
           type: "POST",
           url: apiUrl + 'editadmindata',
           dataType : "JSON",
           data:{name:name,email:email},
           beforeSend:function(){


           },
           success:function(data){
           $('#spinner').hide();      
             if(data.status == 'success')
             {
                showMsg('#msg', 'Profile updated successfully.', 'green');
                adminData();
             }
           },
           error:function(jqxhr){
             $('#spinner').hide();      
             showMsg('#msg', 'error.', 'red');
           }
         });
     }
}

function confirmPassword()
{
  var password = $('#password').val();
  var confirm_password = $('#confirm_password').val();
  var check = true;

  if(password == '')
     {
         $('#password').focus();
         $('#password').addClass('error-class');
         check = false;
     }
     else if(confirm_password == '')
     {
         $('#confirm_password').focus();
         $('#confirm_password').addClass('error-class');
         check = false;
     }

     if(password == confirm_password)
     {
        check = true;
        $.ajax({
           type: "POST",
           url: apiUrl + 'editadminpassword',
           dataType : "JSON",
           data:{password:password},
           beforeSend:function(){

           },
           success:function(data){
           $('#spinner').hide();      
             if(data.status == 'success')
             {
              showMsg('#msg', 'Password updated successfully.', 'green');
             }
           },
           error:function(jqxhr){
             $('#spinner').hide();      
             showMsg('#msg', 'error.', 'red');
           }
         });
     }
     else
     {
         $('#password').focus();
         $('#password').addClass('error-class');
         $('#confirm_password').focus();
         $('#confirm_password').addClass('error-class');

         check = false;
     }


}

function forgotPassword()
{
    var email = $('#email').val();

    if(email == '')
    {
      $('#email').focus();
      $('#email').addClass('error-class');
      check = false;
    }

    $.ajax({
           type: "GET",
           url: apiUrl + 'forgotpassword',
           dataType : "JSON",
           data:{email:email},
           beforeSend:function(){

           },
           success:function(data){
           $('#spinner').hide();      
             if(data.status == 'success')
             {
              showMsg('#msg', 'Email have been sent.', 'green');
             }
           },
           error:function(jqxhr){
             $('#spinner').hide();      
             showMsg('#msg', 'error.', 'red');
           }
         });

}

function resetPassword()
{
  var password = $('#password').val();
  var confirmPassword = $('#confirmpassword').val();
  var email = $('#email').val();
  var code = $('#code').val();
  var check = true;

  if(password == '')
  {
    $('#password').focus();
    $('#password').addClass('error-class');
    check = false;
  }
  if(confirmPassword == '')
  {
    $('#confirmpassword').focus();
    $('#confirmpassword').addClass('error-class');
    check = false;
  }
  else if(password != confirmPassword)
  {
    $('#password').focus();
    $('#password').addClass('error-class');
    $('#confirmpassword').focus();
    $('#confirmpassword').addClass('error-class');
    check = false;
  }
  else
  {
    $.ajax({
           type: "GET",
           url: apiUrl + 'resetpassword',
           dataType : "JSON",
           data:{email:email,code:code,password:password},
           beforeSend:function(){

           },
           success:function(data){
           $('#spinner').hide();      
             if(data.status == 'success')
             {
              showMsg('#msg', 'Password updated successfully.', 'green');
             }
           },
           error:function(jqxhr){
             $('#spinner').hide();      
             showMsg('#msg', 'error.', 'red');
           }
         });
  }
}


function deleteQuery(id)
{
    $.ajax({
      type: 'POST',
      url: apiUrl + 'deletequery',
      dataType : "JSON",
      data: {id:id},
      beforeSend:function(){

      },
      success:function(data){
        showMsg('#jobmsg', 'Query deleted successfully.', 'green');
        getQueries();
      },
      error:function(jqxhr){
      }
    });
}

function showDelPopup(id)
{
  var query_id = id;
  $('#delete').attr('onclick', 'deleteQuery('+query_id+')');
}

function showQueryPopup(id)
{
  queryDetail(id);
}

function sendEmail()
{
  var email = $.trim($('#user_email').val());
  var emailSubject = $.trim($('#email_subject').val());
  var emailBody = $.trim($('#email_body').val());

  if(emailBody != '')
  {
    $.ajax({
          type: 'POST',
          url: apiUrl + 'mail',
          dataType : "JSON",
          data: { email: email, subject: emailSubject, message:emailBody },
          beforeSend:function(){

          },
          success:function(data){
            $('#email_body, #email_subject, #user_email').val('');
          },
          error:function(jqxhr){

          }
        });    
  }
  else
  {
    $('#email_body').addClass('error-class');
  }

}

function resetMessage(email)
{
  $('#user_email').val(email);
  $('#email_subject, #email_body').val('');
}


      function validateEmail(email) 
      {
          var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
          return re.test(email);
      }

      function validation()
      {
        var name      = $.trim($('#parent_name').val());
        var query_id      = $.trim($('#query_id').val());
        var child_name    = $.trim($('#child_name').val());
        var dob       = $.trim($('#dob').val());
        var branch_office   = $.trim($('#branch_office').val());
        var branch_office_other  = $.trim($('#branch_office_other').val());
        var start_time    = $.trim($('#start_time').val());
        var end_time    = $.trim($('#end_time').val());
        var email       = $.trim($('#email').val());
        var phone       = $.trim($('#phone').val());
        var refer_to    = $.trim($('#refer_to').val());
        var refer_to_other = $.trim($('#refer_to_other').val());
        var other_service = $.trim($('#other_service').val());

        // populate services
        var services = [];
        $("input:checkbox[class=services]:checked").each(function()
        {
            var service = $(this).val();
            services.push(service);
        });

        if(other_service != '')
          services.push(other_service);
  
        var check = true;

        if(name == '')
        {
          $('#parent_name').focus();
          $('#parent_name').addClass('has-error');
          check = false;
        }

        if(child_name == '')
        {
          $('#child_name').addClass('has-error');
          if(check)
            $('#child_name').focus();
          check = false;
        }

        if(dob == '')
        {
          $('#dob').addClass('has-error');
          if(check)
            $('#dob').focus();
          check = false;
        }

        if(branch_office == '')
        {
          $('#branch_office').addClass('has-error');
          if(check)
            $('#branch_office').focus();
          check = false;
        }
        else if(branch_office == 'other' && branch_office_other == '')
        {
          $('#branch_office_other').focus();
          $('#branch_office_other').addClass('has-error');
          if(check)
            $('#branch_office_other').focus();
          check = false;
        }

        if(start_time == '')
        {
          $('#start_time').addClass('has-error');
          if(check)
            $('#start_time').focus();
          check = false;
        }

        if(end_time == '')
        {
          $('#end_time').addClass('has-error');
          if(check)
            $('#end_time').focus();
          check = false;
        }

        if(email == '')
        {
          $('#email').addClass('has-error');
          if(check)
            $('#email').focus();
          check = false;
        }
        else
        {
          if(!validateEmail(email))
          {
            $('#email').addClass('has-error');
            if(check)
              $('#email').focus();
            check = false;            
          }
        }

        if(phone == '')
        {
          $('#phone').addClass('has-error');
          if(check)  
            $('#phone').focus();
          check = false;
        }

        if(refer_to == '')
        {
          $('#refer_to').addClass('has-error');
          if(check)  
            $('#refer_to').focus();
          check = false;
        }
        else if(refer_to == 'other' && refer_to_other == '')
        {
          $('#refer_to_other').addClass('has-error');
          if(check)  
            $('#refer_to_other').focus();
          check = false;
        }




        if(check)
        {
          $('#query_spinner').show();
          $.ajax({
              type: 'post',
              url: '../api/query',
              dataType : "JSON",
              data: {id:query_id, parent_name:name, child_name:child_name, dob:dob, branch_office:branch_office, branch_office_other:branch_office_other, start_time:start_time, end_time:end_time, phone:phone, email:email, refer_by:refer_to, refery_by_other: refer_to_other, services:services},
              beforeSend:function(){

              },
              success:function(data){
                if(data.status == 'success')
                {
                  getQueries();
                }
              },
              error:function(jqxhr){
                $('#query_spinner').hide();                
              }
            });
        }

      }  

function getQueryData(id)
{
  // reset fields
  $('#parent_name, #child_name, #dob, #branch_office_other, #email, #phone, #other_service, #refer_to_other').val('');
  $('#query_id').val(id);
  $('#branch_office option:first-child, #start_time option:first-child, #start_time option:first-child, #refer_to option:first-child').attr("selected", "selected");
  $('#refer_to_other, #branch_office_other').hide();
  $('.services').prop('checked', false);


  $.ajax({
      type: 'GET',
      url: apiUrl + 'get_query_data',
      dataType:"JSON",
      data: {id:id},
      beforeSend:function(){

      },
        success:function(data){

          $('#parent_name').val(data.data.parent_name);
          $('#child_name').val(data.data.child_name);
          $('#dob').val(data.data.dob);
          if(data.data.branch_office == 'Escandon' || data.data.branch_office == 'San Angel' || data.data.branch_office == 'San Jeronimo')
            $('#branch_office').val(data.data.branch_office);
          else
          {
            $('#branch_office').val('other');
            $('#branch_office_other').val(data.data.branch_office);
            $('#branch_office_other').show();
          }


          $('#start_time').val(data.data.start_time);
          $('#end_time').val(data.data.end_time);
          $('#email').val(data.data.email);
          $('#phone').val(data.data.phone);
          $('#refer_to').val(data.data.refer_by);
          console.log(data.services);
          $.each(data.services, function( index, value ) {
            if($("input:checkbox[value='" + value.service + "']").length > 0)
              $("input:checkbox[value='" + value.service + "']").attr("checked", true);
            else
              $('#other_service').val(value.service);
          });

          if(data.data.refer_by == 'recommendation' || data.data.refer_by == 'google' ||
           data.data.refer_by == 'bing' || data.data.refer_by == 'youtube' || 
           data.data.refer_by == 'facebook' || data.data.refer_by == 'external advertising')
          {
            $('#refer_to').val(data.data.refer_by);
          }
          else
          {
            $('#refer_to').val('other');
            $('#refer_to_other').val(data.data.refer_by);
            $('#refer_to_other').show();
          }


      },
      error:function(jqxhr){
      }
    });
}

function changeReferBy(curValue)
      {
        if(curValue == 'other')
        {
          $('#refer_to_other').fadeIn();
        }
        else
        {
          $('#refer_to_other').fadeOut();          
        }
      }

      function changebranch(curValue)
      {
        if(curValue == 'other')
        {
          $('#branch_office_other').fadeIn();
        }
        else
        {
          $('#branch_office_other').fadeOut();          
        }
      }

var globalNum = 1;
function getQueries(page)
{
    curpage = page;
    // if(page > 0)
    // page -= 1;

    $.ajax({
      type: 'GET',
      url: apiUrl + 'queries',
      dataType : "JSON",
      data: {page:page},
      //async:sync,
      beforeSend:function(){

      },
      success:function(data){
        var html = '';
        var options = '';
        if(data.data.length > 0)
        {       

            $.each(data.data, function( index, value ) {
             
                html += '<tr>\
                            <td>'+value.parent_name+'</td>\
                            <td>'+value.child_name+'</td>\
                            <td>'+value.dob_formatted+'</td>\
                            <td>'+value.date_created_formatted+'</td>\
                            <td><a href="javascript:void(0);" data-toggle="modal" onclick="getQueryData('+value.id+');" data-target="#edit_query"> <i class="fa fa-pencil"></i> </a> | <a href="javascript:void(0);" data-toggle="modal" onclick="resetMessage(\''+value.email+'\');" data-target="#message_user"> <i class="fa fa-envelope"></i> </a> | <a href="javascript:void(0);" data-toggle="modal" data-target="#query_detail" onclick="showQueryPopup('+value.id+')"> <i class="fa fa-tasks"></i> </a> </a> | <a href="javascript:void(0);" data-toggle="modal" data-target="#confirm" onclick="showDelPopup('+value.id+')"><i class="fa fa-trash-o"></i></a></td>\
                         </tr>';

            });            
        }
        else
        { 
            html += '<tr>\
                        <td colspan="5" align="center">Queries not found</td>\
                     </tr>';            
        }



        $('#queriesbody').html(html);
       // $('#cat_id').append(options);


         $('#pagination').bootpag({
              total: data.total_pages,          // total pages
              page: page,            // default page
              maxVisible: 5,     // visible pagination
              leaps: true         // next/prev leaps through maxVisible
          }).on("page", function(event, num){
             if(num != globalNum)
             {
              getQueries(num);
              globalNum = num;                      
             }
             });




      },
      error:function(jqxhr){
      }
    });
}

function queryDetail(id)
{
  $.ajax({
      type: 'GET',
      url: apiUrl + 'querydetail',
      data: {id:id},
      beforeSend:function(){

      },
        success:function(data){
            
          $('#detail_query_body').html(data);
          // $('#parent_name').html(data.data.data.parent_name);
          // $('#child_name').html(data.data.data.child_name);
          // var dob = data.data.data.dob;
          // //dob= dob.format("dd-mm-yy");
          // //$('#dob').html(dob);
          // if(data.data.data.import == '1')
          // {
          //   $('#file_name').html(data.data.data.filename);
          // }
          // else
          // {
          //   $('#filename').hide();
          // }
          // //$('#file_name').html(data.data.data.filename);
          // $('#branch_office').html(data.data.data.branch_office);
          // $('#start_time').html(data.data.data.start_time);
          // $('#end_time').html(data.data.data.end_time);
          // $('#email').html(data.data.data.email);
          // $('#phone').html(data.data.data.phone);
          // $('#refer_by').html(data.data.data.refer_by);
          // $('#date_created').html(data.data.data.date_created);
          // var html = '';
          // $.each(data.data.services, function( index, value ) {
          //     html += value.service+'<br>';

          //   });   

          // $('#services').html(html);


      },
      error:function(jqxhr){
      }
    });
}