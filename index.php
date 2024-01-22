<div class="display_images" id="display_images"></div>

<script>
	
	jQuery.ajax({
		url: 'https://collagefest.teaserstaging.com/wp-admin/admin-ajax.php',//ajaxurl, // WordPress AJAX URL
		type: 'POST',
		data: {
			action: 'get_finalists_profile_action', // Action hook
			test : 'test'
		},
		success: function(data) {
			window.localStorage.setItem("carouselItems",data);
			const $ = str => document.querySelector(str);
			const $$ = str => document.querySelectorAll(str);

			  (function () {
			  if (!window.app) {
				window.app = {};
			  }
				 
				  //const jsonData = JSON.parse(window.localStorage.getItem("carouselItems"));
				  const jsonData = JSON.parse(data);
					 console.log("jsonData",jsonData);
			  renderCarouselItems(jsonData);
			  app.carousel = {
				removeClass: function (el, classname = '') {
				  if (el) {
					if (classname === '') {
					  el.className = '';
					} else {
					  el.classList.remove(classname);
					}
					return el;
				  }
				  return;
				},
				reorder: function () {
				  let childcnt = $("#carousel").children.length;
				  let childs = $("#carousel").children;

				  for (let j = 0; j < childcnt; j++) {
					childs[j].dataset.pos = j;
				  }
				},
				move: function (el) {
					let selected = el;

					if (typeof el === "string") {
					  console.log(`got string: ${el}`);
					  selected =
						el == "next"
						  ? $(".selected").nextElementSibling
						  : $(".selected").previousElementSibling;
					  console.dir(selected);
					}

					let curpos = parseInt(app.selected.dataset.pos);
					let tgtpos = parseInt(selected.dataset.pos);

					let cnt = curpos - tgtpos;
					let dir = cnt < 0 ? -1 : 1;
					let shift = Math.abs(cnt);

					for (let i = 0; i < shift; i++) {
					  let el =
						dir == -1
						  ? $("#carousel").firstElementChild
						  : $("#carousel").lastElementChild;

					  if (dir == -1) {
						el.dataset.pos = $("#carousel").children.length;
						$("#carousel").append(el);
					  } else {
						el.dataset.pos = 0;
						$("#carousel").prepend(el);
					  }

					  app.carousel.reorder();
					}

					app.selected = selected;
					let next = selected.nextElementSibling; // ? selected.nextElementSibling : selected.parentElement.firstElementChild;
					var prev = selected.previousElementSibling; // ? selected.previousElementSibling : selected.parentElement.lastElementChild;
					var prevSecond = prev
					  ? prev.previousElementSibling
					  : selected.parentElement.lastElementChild;
					var nextSecond = next
					  ? next.nextElementSibling
					  : selected.parentElement.firstElementChild;

					selected.className = "";
					selected.classList.add("selected");

					app.carousel.removeClass(prev).classList.add("prev");
					app.carousel.removeClass(next).classList.add("next");

					app.carousel
					  .removeClass(nextSecond)
					  .classList.add("nextRightSecond");
					app.carousel
					  .removeClass(prevSecond)
					  .classList.add("prevLeftSecond");

					app.carousel.nextAll(nextSecond).forEach((item) => {
					  item.className = "";
					  item.classList.add("hideRight");
					});
					app.carousel.prevAll(prevSecond).forEach((item) => {
					  item.className = "";
					  item.classList.add("hideLeft");
					});
				  },
				  nextAll: function (el) {
				  let els = [];

				  if (el) {
					while (el = el.nextElementSibling) { els.push(el); }
				  }

				  return els;

				},
				prevAll: function (el) {
				  let els = [];

				  if (el) {
					while (el = el.previousElementSibling) { els.push(el); }
				  }


				  return els;
				},
				keypress: function (e) {
				  switch (e.which) {
					case 37: // left
					  app.carousel.move('prev');
					  break;

					case 39: // right
					  app.carousel.move('next');
					  break;

					default:
					  return;
				  }
				  e.preventDefault();
				  return false;
				},
				select: function (e) {
				  console.log(`select: ${e}`);
				  let tgt = e.target;
				  while (!tgt.parentElement.classList.contains('carousel')) {
					tgt = tgt.parentElement;
				  }
				  app.carousel.move(tgt);
				 
				},
				previous: function (e) {
				  app.carousel.move('prev');
				},
				next: function (e) {
				  app.carousel.move('next');
				},
				doDown: function (e) {
				  console.log(`down: ${e.x}`);
				  app.carousel.state.downX = e.x;
				},
				doUp: function (e) {
				  console.log(`up: ${e.x}`);
				  let direction = 0,
					velocity = 0;
				
				  if (app.carousel.state.downX) {
					direction = (app.carousel.state.downX > e.x) ? -1 : 1;
					velocity = app.carousel.state.downX - e.x;

					if (Math.abs(app.carousel.state.downX - e.x) < 10) {
					  app.carousel.select(e);
					  return false;
					}
					if (direction === -1) {
					  app.carousel.move('next');
					} else {
					  app.carousel.move('prev');
					}
					app.carousel.state.downX = 0;
				  }
				},

				init: function () {
				  document.addEventListener("keydown", app.carousel.keypress);
				  $("#carousel").addEventListener("mousedown", app.carousel.doDown);
				  $("#carousel").addEventListener("touchstart", app.carousel.doDown);
				  $("#carousel").addEventListener("mouseup", app.carousel.doUp);
				  $("#carousel").addEventListener("touchend", app.carousel.doUp);
				  $("#prev").addEventListener("click", app.carousel.previous);
				  $("#next").addEventListener("click", app.carousel.next);
					
				  app.carousel.reorder();
				  app.selected = $(".selected");

				  // Remove the next/prev buttons
// 				  $('#prev').style.display = 'none';
// 				  $('#next').style.display = 'none';

				  // Automatically slide every 3 seconds (adjust the interval as needed)
				  setInterval(function () {
					app.carousel.move('next');
				  }, 105000);
				},
				state: {}

			  }
			  app.carousel.init();
			 })();
		},
		
		error: function(errorThrown) {
			console.log(errorThrown);
		}
	});
	
   
	function VoteButtonFunction(u_id, vote_status,login_status,user_login){
		 
		 if(login_status===1 && vote_status===0){
			 jQuery.ajax({
				url: 'https://collagefest.teaserstaging.com/wp-admin/admin-ajax.php',//ajaxurl, // WordPress AJAX URL
				type: 'POST',
				data: {
					action: 'voting_profile_action', // Action hook
					user_id : u_id
				},
				success: function(data) {
					if(data!='Error'){
						
						dataString = data.trim();
				        var dataArray = dataString.split(',');
						
						var instaurl= dataArray[0];
						var mobile_number= dataArray[1];
						var twitterhandle= dataArray[2];
						
						//var slideData = document.getElementById('VotesSectDiv_' + u_id);
						var voteButton = document.getElementById("VoteButton_"+u_id);
						//var slideData = voteButton.closest(".slide-data");
						var slideData = document.getElementById("OuterSectDiv_"+u_id);
						var slideDataDiv = slideData.querySelector('.slide-data');
						
                      const slideDatas1 = document.createElement("div");
					  slideDatas1.classList.add("slide-datas");

					  const votedBtn = document.createElement("a");
					  //votedBtn.href = "user/";
					  votedBtn.classList.add("voted-btn");
					  votedBtn.textContent = "VOTED";

					  const voteBtns = document.createElement("div");
					  voteBtns.classList.add("vote-btns");

					  const ul = document.createElement("ul");
					  ul.innerHTML = '<li><a href="'+instaurl+'"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 551.034 551.034" width="20" height="20" style="enable-background:new 0 0 551.034 551.034;" xml:space="preserve"><g><linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="275.517" y1="4.57" x2="275.517" y2="549.72" gradientTransform="matrix(1 0 0 -1 0 554)"><stop  offset="0" style="stop-color:#E09B3D"/><stop  offset="0.3" style="stop-color:#C74C4D"/><stop  offset="0.6" style="stop-color:#C21975"/><stop  offset="1" style="stop-color:#7024C4"/></linearGradient><path style="fill:url(#SVGID_1_);" d="M386.878,0H164.156C73.64,0,0,73.64,0,164.156v222.722c0,90.516,73.64,164.156,164.156,164.156h222.722c90.516,0,164.156-73.64,164.156-164.156V164.156C551.033,73.64,477.393,0,386.878,0z M495.6,386.878c0,60.045-48.677,108.722-108.722,108.722H164.156c-60.045,0-108.722-48.677-108.722-108.722V164.156c0-60.046,48.677-108.722,108.722-108.722h222.722c60.045,0,108.722,48.676,108.722,108.722L495.6,386.878L495.6,386.878z"/><linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="275.517" y1="4.57" x2="275.517" y2="549.72" gradientTransform="matrix(1 0 0 -1 0 554)"><stop  offset="0" style="stop-color:#E09B3D"/><stop  offset="0.3" style="stop-color:#C74C4D"/><stop  offset="0.6" style="stop-color:#C21975"/><stop  offset="1" style="stop-color:#7024C4"/></linearGradient><path style="fill:url(#SVGID_2_);" d="M275.517,133C196.933,133,133,196.933,133,275.516s63.933,142.517,142.517,142.517S418.034,354.1,418.034,275.516S354.101,133,275.517,133z M275.517,362.6c-48.095,0-87.083-38.988-87.083-87.083s38.989-87.083,87.083-87.083c48.095,0,87.083,38.988,87.083,87.083C362.6,323.611,323.611,362.6,275.517,362.6z"/><linearGradient id="SVGID_3_" gradientUnits="userSpaceOnUse" x1="418.31" y1="4.57" x2="418.31" y2="549.72" gradientTransform="matrix(1 0 0 -1 0 554)"><stop  offset="0" style="stop-color:#E09B3D"/><stop  offset="0.3" style="stop-color:#C74C4D"/><stop  offset="0.6" style="stop-color:#C21975"/><stop  offset="1" style="stop-color:#7024C4"/></linearGradient><circle style="fill:url(#SVGID_3_);" cx="418.31" cy="134.07" r="34.15"/></g></svg></a></li><li><a href="https://wa.me/91'+mobile_number+'"><svg version="1.1" width="20" height="20" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 418.135 418.135" style="enable-background:new 0 0 418.135 418.135;" xml:space="preserve"><g><path style="fill:#7AD06D;" d="M198.929,0.242C88.5,5.5,1.356,97.466,1.691,208.02c0.102,33.672,8.231,65.454,22.571,93.536L2.245,408.429c-1.191,5.781,4.023,10.843,9.766,9.483l104.723-24.811c26.905,13.402,57.125,21.143,89.108,21.631c112.869,1.724,206.982-87.897,210.5-200.724C420.113,93.065,320.295-5.538,198.929,0.242z M323.886,322.197c-30.669,30.669-71.446,47.559-114.818,47.559c-25.396,0-49.71-5.698-72.269-16.935l-14.584-7.265l-64.206,15.212l13.515-65.607l-7.185-14.07c-11.711-22.935-17.649-47.736-17.649-73.713c0-43.373,16.89-84.149,47.559-114.819c30.395-30.395,71.837-47.56,114.822-47.56C252.443,45,293.218,61.89,323.887,92.558c30.669,30.669,47.559,71.445,47.56,114.817C371.446,250.361,354.281,291.803,323.886,322.197z"/><path style="fill:#7AD06D;" d="M309.712,252.351l-40.169-11.534c-5.281-1.516-10.968-0.018-14.816,3.903l-9.823,10.008c-4.142,4.22-10.427,5.576-15.909,3.358c-19.002-7.69-58.974-43.23-69.182-61.007c-2.945-5.128-2.458-11.539,1.158-16.218l8.576-11.095c3.36-4.347,4.069-10.185,1.847-15.21l-16.9-38.223c-4.048-9.155-15.747-11.82-23.39-5.356c-11.211,9.482-24.513,23.891-26.13,39.854c-2.851,28.144,9.219,63.622,54.862,106.222c52.73,49.215,94.956,55.717,122.449,49.057c15.594-3.777,28.056-18.919,35.921-31.317C323.568,266.34,319.334,255.114,309.712,252.351z"/></g></svg></a></li><li><a href="'+twitterhandle+'"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 1226.37 1226.37" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><path d="M727.348 519.284 1174.075 0h-105.86L680.322 450.887 370.513 0H13.185l468.492 681.821L13.185 1226.37h105.866l409.625-476.152 327.181 476.152h357.328L727.322 519.284zM582.35 687.828l-47.468-67.894-377.686-540.24H319.8l304.797 435.991 47.468 67.894 396.2 566.721H905.661L582.35 687.854z" fill="#ffffff" opacity="1" data-original="#000000"></path></g></svg></a></li>';
					  voteBtns.appendChild(ul);
					  slideDatas1.appendChild(votedBtn);
					  slideDatas1.appendChild(voteBtns);
					  //slideData.innerHTML = '';
					  slideData.removeChild(slideDataDiv);
					  slideData.appendChild(slideDatas1);

// 						if (VoteButton) {
// 							VoteButton.href = "user/" + user_login;
// 							VoteButton.classList.add("voted-btn");
// 							VoteButton.textContent = "Voted";
// 						}
						
						//window.localStorage.setItem("carouselItems",data);
					}
					
				},

				error: function(errorThrown) {
					console.log(errorThrown);
				}
			});
		 }else{
			 window.location.href = "/login/";
		 }
	}
	
	var selectedImage = document.querySelector('.selected img');
	if (selectedImage) {
		selectedImage.addEventListener('click', opendiv);
	}
	
	function opendiv(){
		alert('test');
	}
</script>