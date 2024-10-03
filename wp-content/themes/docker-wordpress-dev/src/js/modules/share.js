import $ from "jquery";

const shareClass = ".js--share";

// Share buttons
$("body").on("click", shareClass, function (e) {
  e.preventDefault();

  window
    .open(
      $(this).attr("href"),
      "Share",
      "height=500,width=900,top=150,left=150"
    )
    .focus();
});
