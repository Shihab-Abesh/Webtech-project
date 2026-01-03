function confirmAccept(){ return confirm("Accept this booking request?"); }
function confirmReject(){ return confirm("Reject this booking request?"); }

function filterBookings(){
  var q = document.getElementById("bookingSearch").value.toLowerCase();
  var status = document.getElementById("statusFilter").value;                    
  var rows = document.querySelectorAll("#bookingTable tbody tr");
  for(var i=0;i<rows.length;i++) {
    var rowText = rows[i].innerText.toLowerCase();
    var rowStatus = rows[i].getAttribute("data-status");                 
    var okText = (q === "" || rowText.indexOf(q) !== -1);
    var okStatus = (status === "all" || rowStatus === status);
    rows[i].style.display = (okText && okStatus) ? "" : "none";
  }
}
