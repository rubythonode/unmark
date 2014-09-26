/*! Unmark Internal - http://unmark.it - 2014-09-26 - http://plainmade.com */ 
if(void 0===unmark)var unmark={};if(unmark.template=unmark.template||{},unmark.template.sidebar='<div class="sidebar-action"><a class="action" data-action="sidebar_expand" href="#"><i class="icon-heading_expand"></i></a><a class="action" data-action="sidebar_collapse" href="#"><i class="icon-heading_close"></i></a></div><div class="sidebar-label label-{{label_id}}"><span id="label-chosen"></span><a class="action" data-action="marks_addLabel" href="#" data-id="{{mark_id}}">{{label_name}}</a><ul class="sidebar-label-list" data-id="{{mark_id}}"></ul></div><div class="sidebar-info-panel">{{#embed}}<h4 class="prev-coll">Preview <i class="icon-up"></i></h4><section class="sidebar-info-preview">{{{embed}}}</section>{{/embed}}<h4 class="action" data-action="marks_editNotes">Notes <i class="icon-edit"></i></h4><section id="notes-{{mark_id}}" data-id="{{mark_id}}" class="sidebar-info-notes hideoutline">{{{notes}}}</section></div>{{#archived_on}}<button data-id="{{mark_id}}" data-view="sidebar" data-action="delete_mark">Delete Link</button>{{/archived_on}}',unmark.template.marks='<div id="mark-{{mark_id}}" class="mark label-{{label_id}}"><h2><a target="_blank" href="{{url}}">{{title}}</a></h2><div class="mark-meta"><span class="mark-date">{{nice_time}}</span><span class="mark-sep">•</span><span class="mark-link"><a target="_blank" href="{{url}}">{{prettyurl}}</a></span></div><div class="mark-actions" style="display: none;"><a class="action mark-archive tabletonly" href="#" data-nofade="true" data-action="show_mark_info" data-mark="mark-data-{{mark_id}}"><i class="icon-ellipsis"></i></a><a target="_blank" class="mark-info" href="{{url}}" data-mark="mark-data-{{mark_id}}"><i class="icon-goto_link"></i></a>{{#archived_on}}<a title="Unarchive Mark" class="action mark-archive" data-action="mark_restore" href="#" data-id="{{mark_id}}"><i class="icon-label"></i></a>{{/archived_on}}{{^archived_on}}<a title="Archive Mark" class="action mark-archive" data-action="mark_archive" href="#" data-id="{{mark_id}}"><i class="icon-check"></i></a>{{/archived_on}}</div><div class="note-placeholder"></div><script id="mark-data-{{mark_id}}" type="application/json">{"mark_id":"{{mark_id}}","label_id":"{{label_id}}","label_name":"{{label_name}}","notes":"{{notes}}",{{#embed}}"preview":{{embed}},{{/embed}}"archived":{{active}}}</script></div>',void 0===unmark)var unmark={};(function(e){unmark.ajax=function(a,t,n,r,i,o){var s=unmark.urlEncode(unmark.vars.csrf_token),i=void 0!==i?i:"json",o=void 0!==o?o:!0,l="csrf_token="+s+"&content_type="+i;n=unmark.empty(n)?l:n+"&"+l,e.ajax({dataType:i,cache:!1,url:a,type:t.toUpperCase(),data:n,async:o,success:function(a){e.isFunction(r)&&r(a)},error:function(a,t,n){var i={error:n,status:t,request:a};e.isFunction(r)&&r(i)}})},unmark.readQuery=function(e){for(var a=window.location.search.substring(1),t=a.split("&"),n=0;t.length>n;n++){var r=t[n].split("=");if(r[0]==e)return r[1]}return!1},unmark.swapClass=function(a,t,n){var r=a;if(-1===t.indexOf("*"))return r.removeClass(t),n?r.addClass(n):r;var i=RegExp("\\s"+t.replace(/\*/g,"[A-Za-z0-9-_]+").split(" ").join("\\s|\\s")+"\\s","g");return r.each(function(a,t){for(var n=" "+t.className+" ";i.test(n);)n=n.replace(i," ");t.className=e.trim(n)}),n?r.addClass(n):r},unmark.replaceSpecial=function(e){if(void 0!==e&&null!==e){var a=null;for(var t in unmark.special_chars)a=RegExp(t,"gi"),e=e.replace(a,unmark.special_chars[t])}return e},unmark.urlEncode=function(e){return e=unmark.replaceSpecial(e),encodeURIComponent(e)},unmark.empty=function(e){var a=void 0!==e&&null!==e?e.length:0;return e===!1||""===e||null===e||0===e||void 0===e||1>a},unmark.createCookie=function(e,a,t){if(t){var n=new Date;n.setTime(n.getTime()+1e3*60*60*24*t);var r="; expires="+n.toGMTString()}else var r="";document.cookie=e+"="+a+r+"; path=/"},unmark.readCookie=function(e){for(var a=e+"=",t=document.cookie.split(";"),n=0;t.length>n;n++){for(var r=t[n];" "==r.charAt(0);)r=r.substring(1,r.length);if(0==r.indexOf(a))return r.substring(a.length,r.length)}return null},unmark.prettyLink=function(e){return e=e.replace(/https?:\/\/(www.)?/,""),"/"===e.substr(-1)&&(e=e.substr(0,e.length-1)),e},unmark.read_query_str=function(e){e=e.replace(/[\[]/,"\\[").replace(/[\]]/,"\\]");var a=RegExp("[\\?&]"+e+"=([^&#]*)"),t=a.exec(location.search);return null==t?"":decodeURIComponent(t[1].replace(/\+/g," "))},unmark.extendFunction=function(e,a){this[e]=function(e,a,t){return function(){var n=a.apply(e,arguments),r=t.apply(e,arguments);return null!==r?r:n}}(this,this[e],a)}})(window.jQuery),function(e){unmark.updateDom=function(){var a=e("div.marks").data("label-class"),t=e("body");t.removeClass().addClass(a),unmark.page_setup(e("body").height())},unmark.sidebar_collapse=function(){Modernizr.mq("only screen and (max-width: 480px)")&&(e(".mark-actions").hide(),e(".sidebar-content").animate({right:"-85%"},600,function(){e(this).hide()})),e(".mark").removeClass("view-inactive").removeClass("view-active"),unmark.sidebar_expand(!0),unmark.sidebar_mark_info.fadeOut(400,function(){unmark.sidebar_default.fadeIn(400)})},unmark.sidebar_expand=function(e){var a=unmark.sidebar_content.find('a[data-action="sidebar_expand"] i');return e===!0?unmark.sidebar_content.animate({width:"42.17749%"},800,function(){a.removeClass("icon-heading_collapse").addClass("icon-heading_expand"),unmark.sidebar_content.removeClass("wide")}):(a.hasClass("icon-heading_collapse")?unmark.sidebar_content.animate({width:"42.17749%"},800,function(){a.removeClass("icon-heading_collapse").addClass("icon-heading_expand"),unmark.sidebar_content.removeClass("wide")}):unmark.sidebar_content.animate({width:"75%"},800,function(){a.removeClass("icon-heading_expand").addClass("icon-heading_collapse"),unmark.sidebar_content.addClass("wide")}),void 0)},unmark.hideNavigation=function(){Modernizr.mq("only screen and (min-width: 480px)")&&(e(".mark-actions").hide(),e(".branding").fadeOut()),unmark.nav_panel.stop().animate({left:-285},400),unmark.main_panel.stop().animate({left:65},200,function(){e(".nav-panel").hide(),e(".menu-item").removeClass("active-menu"),e(".navigation-pane-links").show(),e(".menu-activator i").removeClass("icon-menu_close").addClass("icon-menu_open")})},unmark.interact_nav=function(a,t){var n=t.attr("href"),r=n.replace(/^#/,""),i=parseInt(t.attr("rel")),o=i+65,s=t.parent(),l=parseInt(unmark.nav_panel.css("left"));return unmark.sidebar_collapse(),n.match(/\//)?(unmark.hideNavigation(),!0):(a.preventDefault(),e(".mark-actions").hide(),s.hasClass("active-menu")?(e(".menu-item").removeClass("active-menu"),unmark.hideNavigation()):(e(".menu-item").removeClass("active-menu"),e(".navigation-content").find("[data-menu='"+r+"']").addClass("active-menu"),"#panel-menu"===n&&l>0?unmark.hideNavigation():(e(".menu-activator i").removeClass("icon-menu_open").addClass("icon-menu_close"),unmark.nav_panel.animate({left:65},{duration:200,queue:!1}),unmark.main_panel.animate({left:o},{duration:200,queue:!1}),unmark.nav_panel.animate({width:i},200),unmark.nav_panel.find(".nav-panel").animate({width:i},200),e(".branding").fadeIn(),"#panel-menu"===n?(e(".navigation-pane-links").show(),e(".nav-panel").hide()):(e(".navigation-pane-links").hide(),e(".nav-panel").not(n).hide(),e(n).show()),void 0)))},unmark.scrollPaginate=function(e){var a,t,n,r,i,o="",r=window.unmark_current_page+1,s=window.unmark_total_pages;e.scrollTop()+e.innerHeight()>=e[0].scrollHeight&&s>=r&&(n=Hogan.compile(unmark.template.marks),a=window.location.pathname,unmark.ajax(a+"/"+r,"post","",function(e){if(e.marks){for(i=Object.keys(e.marks).length,t=1;i>t;t++)e.marks[t].prettyurl=unmark.prettyLink(e.marks[t].url),o+=n.render(e.marks[t]);unmark.main_content.find(".marks_list").append(o),window.unmark_current_page=r}}))},unmark.updateCounts=function(){unmark.getData("stats",function(a){var t=a.stats.archived,n=(a.stats.saved,a.stats.marks);e(".na-today").text(t.today),e(".ns-year").text(n["ages ago"])})},unmark.getData=function(e,a){unmark.ajax("/marks/get/"+e,"post","",a)},unmark.close_window=function(a){if(a)return window.close();var t=e(".mark-added-note").find("textarea").val(),n=e(".mark-added-note").find("textarea").data("id");unmark.saveNotes(n,t),window.close()},unmark.dismiss_this=function(e){e.parent().parent().fadeOut()},unmark.page_setup=function(a){unmark.main_content.height(a),unmark.sidebar_content.height(a),e(".nav-panel").height(a),e("body").height(a)},unmark.overlay=function(a){if(a===!0){unmark.mainpanels.addClass("blurme");var t=e('<div id="unmark-overlay"><a href="#" id="unmarkModalClose"><i class="icon-big_close"></i></a></div>');t.appendTo(document.body)}else e(".hiddenform").hide().css("top","-300px"),unmark.mainpanels.removeClass("blurme"),e("#unmark-overlay").remove(),e("#helperforms input").val("")}}(window.jQuery),function(e){var a=0;unmark.show_mark_info=function(t){function n(){a=arguments[0]||a,isNaN(a)?e("ul.sidebar-label-list").prepend(unmark.label_list(a)):unmark.getData("labels",n)}var r,i,o=t.data("mark"),s=e("#"+o).html(),l=jQuery.parseJSON(s),c=o.replace("mark-data-",""),u=e("#mark-"+c).find(".note-placeholder").text();mark_nofade=t.data("nofade"),mark_nofade||(e(".mark").removeClass("view-inactive").removeClass("view-active"),e(".mark").not("#mark-"+c).addClass("view-inactive"),e("#mark-"+c).addClass("view-active")),""!==u&&(l.notes=u),r=Hogan.compile(unmark.template.sidebar),i=r.render(l),Modernizr.mq("only screen and (max-width: 480px)")&&e("#mobile-sidebar-show").trigger("click"),unmark.sidebar_mark_info.fadeOut(400,function(){unmark.sidebar_default.is(":visible")?unmark.sidebar_default.fadeOut(400,function(){unmark.sidebar_mark_info.html(i).fadeIn(400,function(){unmark.tagify_notes(e("#notes-"+c)),n(),e("section.sidebar-info-preview").fitVids()})}):(unmark.sidebar_mark_info.html(i),unmark.tagify_notes(e("#notes-"+c)),n(),unmark.sidebar_mark_info.fadeIn(400,function(){e("section.sidebar-info-preview").fitVids()}))})},unmark.update_label_count=function(){function a(e){var a,n,r=e.labels;for(a in r)n=r[a].total_active_marks,"1"===n?n+=" mark":"0"===n?n="no marks":n+=" marks",t.find(".label-"+r[a].label_id+" span").text(n)}var t=e("ul.label-list");unmark.getData("labels",a),unmark.updateCounts()},unmark.mark_archive=function(a){var t=a.data("id");unmark.ajax("/mark/archive/"+t,"post","",function(a){null!==a.mark.archived_on?(e("#mark-"+t).fadeOut(),unmark.sidebar_collapse(),unmark.update_label_count()):alert("Sorry, We could not archive this mark at this time.")})},unmark.mark_restore=function(a){var t=a.data("id");unmark.ajax("/mark/restore/"+t,"post","",function(a){null===a.mark.archived_on?(e("#mark-"+t).fadeOut(),unmark.sidebar_collapse(),unmark.update_label_count()):alert("Sorry, We could not restore this mark at this time.")})},unmark.archive_all=function(){unmark.ajax("/marks/archive/old","post","",function(e){e.archived===!0?window.location="/marks":alert("Sorry, We could not archive the links at this time. Please try again.")})},unmark.marks_editNotes=function(a){function t(a,t){i="notes="+unmark.urlEncode(a),unmark.ajax("/mark/edit/"+t,"post",i,function(){n(1),e("#mark-"+t).find(".note-placeholder").text(a)})}function n(e){switch(e){case 1:heading='Notes <i class="icon-edit"></i>';break;case 2:heading='EDITING NOTES <i class="icon-heading_close"></i>';break;case 3:heading='ADD A NOTE <i class="icon-edit"></i>'}a.html(heading)}var r,i,o=a.next();o.unbind(),n(2),a.removeClass("action"),o.attr("contenteditable",!0).addClass("editable"),o.find("a").contents().unwrap(),o.focus(),o.on("blur keydown",function(i){(13===i.which||"blur"===i.type)&&(i.preventDefault(),o.attr("contenteditable",!1).removeClass("editable"),r=e(this).text(),id=e(this).data("id"),""===r?n(3):t(r,id),o.unbind(),unmark.tagify_notes(o),setTimeout(function(){a.addClass("action")},500))})},unmark.marks_addNotes=function(e){var a=e.next();e.hide(),a.fadeIn(),a.focus()},unmark.saveNotes=function(e,a){var t="notes="+unmark.urlEncode(a);unmark.ajax("/mark/edit/"+e,"post",t)},unmark.marks_addLabel=function(a){var t,n,r,i,o,s,l=a.next(),c=a.parent();return l.is(":visible")?l.fadeOut():(l.find("a").unbind(),l.fadeIn(),l.find("a").on("click",function(u){u.preventDefault(),t=l.data("id"),n=e(this).attr("rel"),i=e(this).text(),o=e("body").attr("class"),s=RegExp("label"),r="label_id="+n,unmark.ajax("/mark/edit/"+t,"post",r,function(r){l.fadeOut(),a.text(i),unmark.swapClass(a,"label-*","label-"+n),l.find("a").unbind(),unmark.update_label_count(),c.hasClass("sidebar-label")&&(unmark.swapClass(c,"label-*","label-"+n),unmark.swapClass(e("#mark-"+t),"label-*","label-"+n),unmark.update_mark_info(r,t),s.test(o)&&o!=="label-"+n&&(e("#mark-"+t).fadeOut(),unmark.sidebar_collapse()))})}),void 0)},unmark.update_mark_info=function(a,t){var n=a.mark;n=JSON.stringify(n),e("#mark-data-"+t).html(n)},unmark.label_list=function(e){var a,t,n=e.labels,r="";for(a in n)t=n[a],r+='<li class="label-'+t.label_id+'"><a href="#" rel="'+t.label_id+'"><span>'+t.name+"</span></a></li>";return r},unmark.tagify_notes=function(e){var a=e.text();""!==a?(a=a.replace(/(https?:\/\/[^\]\s]+)(?: ([^\]]*))?/g,"<a target='_blank' href='$1'>$1</a>"),a=a.replace(/#(\S*)/g,'<a href="/marks/tag/$1">#$1</a>')):e.prev().html('Click To Add A Note <i class="icon-edit"></i>'),e.html(a)},unmark.delete_mark=function(a){var t=a.data("id"),n=a.data("view");unmark.ajax("/mark/delete/"+t,"post","",function(a){"0"===a.mark.active?"bookmarklet"===n?unmark.close_window(!0):(unmark.sidebar_collapse(),e("#mark-"+t).fadeOut()):alert("This mark could not be deleted, please try again laster.")})}}(window.jQuery),function(e){function a(e,a,t){var n=a?"error":"";e.parent().find(".response-message").removeClass("error").addClass(n).text(t).fadeIn()}function t(e,a){var t=e.find(".login-submit i");a?t.removeClass("icon-go").addClass("icon-spinner"):t.removeClass("icon-spinner").addClass("icon-go")}unmark.logout=function(){window.location="/logout"},unmark.change_password=function(){unmark.overlay(!0),e("#resetPasswordForm").show().animate({top:0},1e3)},unmark.change_email=function(){unmark.overlay(!0),e("#changePasswordForm").show().animate({top:0},1e3)},unmark.import_export=function(){unmark.overlay(!0),e("#importExportForm").show().animate({top:0},1e3)},unmark.send_password_change=function(n){var r,i=e("#pass1, #pass2"),o=e("#oldpass"),s=e("#oldpass").val(),l=e("#pass1").val(),c=e("#pass2").val();return t(n,!0),l!==c?(i.val(""),t(n,!1),a(n,!0,"New Passwords do not match")):(r="password="+unmark.urlEncode(l)+"&current_password="+unmark.urlEncode(s),unmark.ajax("/user/update/password","post",r,function(e){e.success?a(n,!1,"Your password has been changed."):a(n,!0,e.message),t(n,!1),i.val(""),o.val("")}),void 0)},unmark.send_email_change=function(n){var r,i=e("#emailupdate"),o=i.val();return t(n,!0),""===o?(i.val(""),t(n,!1),a(n,!0,"Please enter something!")):(r="email="+unmark.urlEncode(o),unmark.ajax("/user/update/email","post",r,function(r){r.success?(a(n,!1,"Your email has been changed."),e("#user-email").empty().text("[ "+o+" ]")):a(n,!0,r.message),t(n,!1),i.val("")}),void 0)},unmark.export_data=function(){return window.location.href="/export"},unmark.import_data=function(){return e(".importer").trigger("click")},unmark.import_data_html=function(){return e(".importerHTML").trigger("click")}}(window.jQuery),function($){unmark.init=function(){this.nav_panel=$(".navigation-pane"),this.main_panel=$(".main-wrapper"),this.main_content=$(".main-content"),this.sidebar_content=$(".sidebar-content"),this.main_panel_width=unmark.main_panel.width(),this.sidebar_default=$(".sidebar-default"),this.sidebar_mark_info=$(".sidebar-mark-info"),this.body_height=$(window).outerHeight(!0),this.special_chars={"\\+":"&#43;"},this.mainpanels=$("#unmark-wrapper");var load=unmark.readQuery("load");load!==!1&&(unmark.overlay(!0),$("#"+load).show().animate({top:0},1e3)),window.unmark_current_page=1,Modernizr.mq("only screen and (min-width: 480px)")&&$("body").animate({opacity:1},1e3),$(".navigation-content a, .navigation-pane-links a").on("click",function(e){unmark.interact_nav(e,$(this))}),$(document).on("mouseenter",".mark",function(){$(this).addClass("hide-dot"),$(this).find(".mark-actions").show()}),$(document).on("mouseleave",".mark",function(){$(this).removeClass("hide-dot"),$(this).find(".mark-actions").hide()}),$(document).on("click","button[data-action], .action",function(e){e.preventDefault(),e.stopPropagation();var action=$(this).data("action"),funct;funct=eval("unmark."+action),funct($(this))}),$(document).on("click",".sidebar-info-panel h4.prev-coll",function(e){e.preventDefault();var a=$(this).next("section"),t=$(this).find("i");a.is(":visible")?(t.removeClass("icon-up"),t.addClass("icon-down"),a.slideUp()):(t.removeClass("icon-down"),t.addClass("icon-up"),a.slideDown())}),$(document).on("click",".mark",function(e){var a=e.target.className,t=$(this).find("a.mark-info");"icon-check"!==a&&"action mark-archive"!==a&&unmark.show_mark_info(t),unmark.hideNavigation()}),$("#unmark").length>0&&($(document).pjax("a[href*='/']",unmark.main_content),$(document).on("submit","#search-form",function(e){$.pjax.submit(e,unmark.main_content)}),$(document).on("pjax:complete",function(){Modernizr.mq("only screen and (max-width: 480px)")&&unmark.mobile_nav(!0),window.unmark_current_page=1,unmark.main_content.scrollTop(0),unmark.main_content.find(".marks").hide().fadeIn(),unmark.updateDom()})),$("form.ajaxsbmt").on("submit",function(e){e.preventDefault();var form=$(this),formid=form.attr("id");funct=eval("unmark."+formid),funct(form,e)}),$("#helperforms input.field-input").on("keydown change",function(){$(this).parent().parent().find(".response-message").hide()}),$(document).on("click","#unmarkModalClose",function(e){return e.preventDefault(),unmark.overlay(!1)}),$(document).on("mouseenter",".label-choices li, .sidebar-label-list li",function(){var e=$(this),a=e.find("span").text(),t=e.attr("class");$("#label-chosen").show().text(a).removeClass().addClass(t)}),$(document).on("mouseleave",".label-choices li, .sidebar-label-list li",function(){$("#label-chosen").show().hide()}),unmark.main_content.on("scroll",function(){unmark.scrollPaginate($(this))}),$(".importer").change(function(){return $("#importForm").submit()}),$(".importerHTML").change(function(){return $("#importFormHTML").submit()})},$(document).ready(function(){unmark.init()})}(window.jQuery),function(e){e(document).ready(function(){unmark.mobile_nav=function(a){a?(Modernizr.mq("only screen and (max-width: 480px)")&&(e(".main-wrapper").animate({left:0},400),e(".navigation-content").animate({left:"-64"},400),e(".navigation-content .menu-activator").animate({left:62},400)),e(".menu-item").removeClass("active-menu")):(e(".mark-actions").hide(),e(".main-wrapper").animate({left:65},400),e(".navigation-content").animate({left:0},400),e(".navigation-content .menu-activator").animate({left:0},400),unmark.mobile_sidebar(!0))},unmark.mobile_sidebar=function(a){a?e(".sidebar-content").show().animate({right:"-85%"},600,function(){e(this).hide(),e("a#mobile-sidebar-show i").removeClass("icon-heading_close").addClass("icon-ellipsis")}):(e(".sidebar-content").show().css("width","85%").animate({right:0},600),e("a#mobile-sidebar-show i").removeClass(".icon-ellipsis").addClass("icon-heading_close"),unmark.mobile_nav(!0))},Modernizr.mq("only screen and (max-width: 480px)")&&(e(".menu-activator a").off().on("click",function(a){a.preventDefault();var t=e(".main-wrapper").css("left");"65px"===t?unmark.mobile_nav(!0):unmark.mobile_nav()}),e("#mobile-sidebar-show").on("click",function(a){a.preventDefault();var t=e(".sidebar-content").css("right");"0px"===t?unmark.mobile_sidebar(!0):unmark.mobile_sidebar(),e(".mark-actions").hide()}),e(".menu-upgrade a, .menu-settings a, .menu-search a").attr("rel","250")),Modernizr.mq("only screen and (max-width: 1024px)")&&(e(document).off("mouseenter mouseleave click",".mark"),e(document).on("click",".mark",function(){e(".mark-actions").hide(),e(this).find(".mark-actions").show(),unmark.mobile_nav(!0)})),Modernizr.mq("only screen and (max-width: 767px)")&&e(".nav-panel a").on("click",function(){unmark.hideNavigation()})})}(window.jQuery);