$(document).ready(function() {
  $("#menu-icons").click(function() {
    $("#main-menu").css("left", "0px");

    function showMenu() {
      $("#main-menu").css("-webkit-clip-path", "polygon(0 0,100% 0,100% 100%,0% 100%)");
      $("#menu-icons").animate({
        right: '-100'
      }, 300);
    }
    setTimeout(showMenu, 100);
  });

  $("#close").click(function() {
    $("#main-menu").css("-webkit-clip-path", "polygon(0 0,0% 0,100% 100%,0% 100%)");

    function hideMenu() {
      $("#main-menu").css("left", "-300px");
      $("#menu-icons").animate({
        right: '50'
      }, 300);
    }
    setTimeout(hideMenu, 300);

    function originalLayout() {
      $("#main-menu").css("-webkit-clip-path", "polygon(0 0,100% 0,0% 100%,0% 100%)");
    }
    setTimeout(originalLayout, 600);
  });
});
