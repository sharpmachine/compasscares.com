/* Author: 

*/// Allows you to use the $ shortcut.  Put all your code  inside this wrapper
jQuery(document).ready(function(e){function s(){e(this).parents("ul:first").find("a").removeClass("selected").end().end().addClass("selected")}function o(t){var n=e("#staff-slider .navigation").find('a[href$="'+t.id+'"]').get(0);s.call(n)}e(".item:first-child").addClass("active");e("#locations li:first-child").addClass("active");e(".tab-pane:first-child").addClass("active");e("h1.section-header .main-title").lettering("words");e(".full-bio span").click(function(){e(this).parent(".full-bio").find("p").slideToggle("fast",function(){e(this).is(":visible")?e(this).siblings("span").text("Read Less"):e(this).siblings("span").text("Read More")})});e("a.jd").click(function(){e("thead.job-description div").slideToggle("fast")});e("#sls span, #ils span").click(function(){e(".service-copy").slideToggle();e("#ils").html("<span class='poop'>Back</span>")});e(".show-comments").click(function(){e("#disqus_thread").slideToggle()});e('input[type="checkbox"]').click(function(){if(e('input[type="checkbox"]:checked').length>0){e("table#wpjb-job-list tr.wpjb-free").hide();e('input[type="checkbox"]:checked').each(function(){e("table#wpjb-job-list tr.wpjb-free[data-county="+this.id+"]").show()})}else e("table#wpjb-job-list tr.wpjb-free").show()});e(".wpjb-element-name-applicant_name").before("<h3>Personal Information</h3>");e(".wpjb-element-name-field_14").after('<h3 class="ha1">Education</h3>');e(".ha1").after("<h4>High School</h4>");e(".wpjb-element-name-field_20").after("<h4>College</h4>");e(".wpjb-element-name-field_25").after("<h4>Graduate School</h4>");e(".wpjb-element-name-field_30").after("<h4>Other</h4>");e(".wpjb-element-name-field_35").after("<h3>U.S. Military Service</h3>");e(".wpjb-element-name-field_38").after("<h3>Special Skills</h3>");e(".wpjb-element-name-field_40").after("<h3>Legal</h3><p>Identity and employment eligibility of all new hires will be verified as required by the immigration reform and control act of 1986.</p>");e(".wpjb-element-name-field_43").after('<h3 class="ha2">Please indicate in the space below times that you be available to work</h3>');e(".ha2").after("<h4>Monday</h4>");e(".wpjb-element-name-field_45").after("<h4>Tuesday</h4>");e(".wpjb-element-name-field_47").after("<h4>Wednesday</h4>");e(".wpjb-element-name-field_49").after("<h4>Thursday</h4>");e(".wpjb-element-name-field_51").after("<h4>Friday</h4>");e(".wpjb-element-name-field_53").after("<h4>Saturday</h4>");e(".wpjb-element-name-field_55").after("<h4>Sunday</h4>");e(".wpjb-element-name-field_57").after('<h3>Employment History</h3><p class="ha3">Please list your last three (3) employers, assignments, volunteer activities, starting with the most recent, including military experience.  Please explain any gaps in employment in the comments section below.</p>');e(".ha3").after("<h4>Recent Employer 1</h4>");e(".wpjb-element-name-field_69").after("<h4>Recent Employer 2</h4>");e(".wpjb-element-name-field_81").after("<h4>Recent Employer 3</h4>");e(".wpjb-element-name-field_94").after('<h3>References</h3><p class="ha4">Give below the names of three persons not related to you whom you have known atleast one year</p>');e(".ha4").after("<h4>Reference 1</h4>");e(".wpjb-element-name-field_99").after("<h4>Reference 2</h4>");e(".wpjb-element-name-field_104").after("<h4>Reference 3</h4>");var t=e("#staff-slider .scrollContainer > div"),n=e("#staff-slider .scrollContainer"),r=!0;if(r){t.css({"float":"left",position:"relative"});n.css("width",t[0].offsetWidth*t.length)}var i=e("#staff-slider .scroll").css("overflow","hidden");i.before('<span class="left">&lsaquo;</span>').after('<span class="right">&rsaquo;</span>');e("#staff-slider .navigation").find("a").click(s);window.location.hash?o({id:window.location.hash.substr(1)}):e("ul.navigation a:first").click();var u=parseInt((r?n.css("paddingTop"):n.css("paddingLeft"))||0)*-1,a={target:i,items:t,navigation:".navigation a",prev:"span.left",next:"span.right",axis:"xy",onAfter:o,offset:u,duration:500,easing:"swing"};e("#staff-slider").serialScroll(a);e.localScroll(a);a.duration=1;e.localScroll.hash(a);Filters;e("#filters :checkbox").click(function(){var t=new RegExp(e("#filters :checkbox:checked").map(function(){return this.value}).get().join("|"));e("div").each(function(){var n=e(this);n[t.source!=""&&t.test(n.attr("class"))?"show":"hide"]()})})});