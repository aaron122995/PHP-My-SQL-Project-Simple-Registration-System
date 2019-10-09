var url = window.location;
// grab the url of current page

$("ul.navbar-nav a").filter(function(){return this.href == url;}).parent().addClass('active');
