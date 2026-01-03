function filterHistory(){
  var q = document.getElementById("historySearch").value.toLowerCase();
  var status = document.getElementById("historyStatus").value; 
  var rows = document.querySelectorAll("#historyTable tbody tr");
  for(var i=0;i<rows.length;i++){
    var rowText = rows[i].innerText.toLowerCase();
    var rowStatus = rows[i].getAttribute("data-status");
    var okText = (q === "" || rowText.indexOf(q) !== -1);
    var okStatus = (status === "all" || rowStatus === status);
    rows[i].style.display = (okText && okStatus) ? "" : "none";
  }
}
function printHistory(){
  window.print();
}
