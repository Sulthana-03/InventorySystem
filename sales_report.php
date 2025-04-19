<?php
$page_title = 'Sale Report';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">

      </div>
      <div class="panel-body">
          <form class="clearfix" method="post" action="sale_report_process.php">
            <div class="form-group">
              <label class="form-label">Date Range</label>
                <div class="input-group">

                  From: <input type="date"  name="start-date" placeholder="From" id="start-date">
                  <script>
                    var today = new Date().toISOString().split('T')[0];
                    document.getElementById("start-date").setAttribute("max" , today);
                  </script>

                  

                  <script>
                    // Wait for the DOM to be ready
                    document.addEventListener('DOMContentLoaded', function () {
                      var today = new Date();
                      var yesterday = new Date(today);
                      yesterday.setDate(today.getDate() - 1); // Get yesterday's date

                      // Format the date as YYYY-MM-DD (ISO format)
                      var formattedYesterday = yesterday.toISOString().split('T')[0];

                      // Set max attribute to yesterday's date, disabling it
                      document.getElementById("start-date").setAttribute("max", formattedYesterday);
                    });
                  </script>



                  To: 
                  <input type="date"  name="end-date" placeholder="To" id="end-date">

                  <script>
                    var today = new Date().toISOString().split('T')[0];
                    document.getElementById("end-date").setAttribute("max" , today);
                  </script>


                </div>
            </div>
            <div class="form-group">
                 <button type="submit" name="submit" class="btn btn-primary">Generate Report</button>
            </div>
          </form>
      </div>

    </div>
  </div>

</div>
<?php include_once('layouts/footer.php'); ?>
