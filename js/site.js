$(document).ready(function () {
  if ($("div").hasClass("emplist")) {
    $("div.emplist>table>tbody>tr").click(
      function(){
        window.location ='employee?id='+this.id;
      }
    );
  }
});