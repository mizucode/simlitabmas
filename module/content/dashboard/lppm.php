<?php 

ob_start();	

	if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
		header('location:/login');
	}
	else {

	?>

  <link rel="stylesheet" href="plugins/fullcalendar/main.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar-daygrid/main.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar-timegrid/main.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar-bootstrap/main.min.css">

			<div class='row'>
				<div class='col-md-7'>
			            <div class="card">
			              <div class="card-body">
			                <div id="external-events"></div>
					<div id="calendar"></div>
			              </div>
 			            </div>
				</div>
				<div class='col-md-5'>

<?php

					$qpost=mysqli_query($con,"SELECT user.name AS user,post.day,post.time,post.hit,post.text,post.seo_title,post.title,post.id,post.date FROM user,post WHERE user.username=post.owner AND post.type='post' ORDER BY post.id DESC LIMIT 15 ");
					if(mysqli_num_rows($qpost)>0) {
							echo"
										<div class='list-group'>
											<a href='/blog' class='list-group-item active'>
												Tulisan Terkahir</a>
							";
							$no=1;
							while($post=mysqli_fetch_array($qpost)) {
								echo"
												<a class='list-group-item' href='/blog/read/$post[seo_title]/'>$post[title] ($post[user])</a>
								";
								$no++;
							}
							
							echo"
										</div>
							";
					}

?>
				</div>
			</div>
<?php
	$qip = mysqli_query($con,"SELECT nilai.ta,ROUND(SUM(mutu_nilai.angka*kurikulum_mk.sks)/SUM(kurikulum_mk.sks),2) AS ipk FROM mhs,nilai,kurikulum_mk,mutu_nilai WHERE mhs.nim=nilai.nim AND nilai.id_mk=kurikulum_mk.mk AND nilai.mutu=mutu_nilai.huruf AND mhs.paket_studi=kurikulum_mk.kurikulum AND mhs.nim='$_SESSION[ses_user]' AND nilai.ta!='' GROUP BY mhs.nim,nilai.ta");

?>

<!-- fullCalendar 2.2.5 -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/fullcalendar/main.min.js"></script>
<script src="plugins/fullcalendar-daygrid/main.min.js"></script>
<script src="plugins/fullcalendar-timegrid/main.min.js"></script>
<script src="plugins/fullcalendar-interaction/main.min.js"></script>
<script src="plugins/fullcalendar-bootstrap/main.min.js"></script>

<script>
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    ini_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendarInteraction.Draggable;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');

    // initialize the external events
    // -----------------------------------------------------------------

    new Draggable(containerEl, {
      itemSelector: '.external-event',
      eventData: function(eventEl) {
        console.log(eventEl);
        return {
          title: eventEl.innerText,
          backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
        };
      }
    });

    var calendar = new Calendar(calendarEl, {
      plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      'themeSystem': 'bootstrap',

      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function(info) {
        // is the "remove after drop" checkbox checked?
        if (checkbox.checked) {
          // if so, remove the element from the "Draggable Events" list
          info.draggedEl.parentNode.removeChild(info.draggedEl);
        }
      }    
    });

    calendar.render();
    // $('#calendar').fullCalendar()


  })
</script>

		<?php


	}



?>