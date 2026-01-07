function confirmAccept(){ return confirm("Accept this booking request?"); }
function confirmReject(){ return confirm("Reject this booking request?"); }

function ajaxSearch(){
  let q = document.getElementById('bookingSearch').value;
  let status = document.getElementById('statusFilter').value;

let xhttp = new XMLHttpRequest();
xhttp.open('POST', '../controller/owner_booking_search.php', true);
xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
xhttp.send('q='+encodeURIComponent(q)+'&status='+encodeURIComponent(status));

xhttp.onreadystatechange = function (){
  if(this.readyState == 4 && this.status == 200) {
   let res = JSON.parse(this.responseText);
   let tbody = document.querySelector('#bookingTable tbody');
      tbody.innerHTML = '';

      if(!res.ok || !res.items || res.items.length == 0)
        {
        tbody.innerHTML = '<tr><td colspan="9">No booking requests found.</td></tr>';
        return;
      }

      for(let i=0; i<res.items.length; i++){
        let b = res.items[i];
        let amount = Number(b.amount);
        if(isNaN(amount)) amount = 0;

        let actionHtml = '-';
        if(b.status == 'Pending'){
          actionHtml =
            '<form method="post" style="display:inline;" onsubmit="return confirmAccept()">'
            + '<input type="hidden" name="booking_id" value="'+b.id+'">'
            + '<input type="hidden" name="action" value="accept">'
            + '<button class="btn btn-small btn-primary" type="submit">Accept</button>'
            + '</form> '
            + '<form method="post" style="display:inline;" onsubmit="return confirmReject()">'
            + '<input type="hidden" name="booking_id" value="'+b.id+'">'
            + '<input type="hidden" name="action" value="reject">'
            + '<button class="btn btn-small btn-danger" type="submit">Reject</button>'
            + '</form>';
        }

        let tr = document.createElement('tr');
        tr.setAttribute('data-status', b.status);
        tr.innerHTML =
          '<td>'+b.id+'</td>'
          + '<td>'+(b.ship_name || '')+'</td>'
          + '<td>'+(b.customer_name || '')+'</td>'
          + '<td>'+(b.destination || '')+'</td>'
          + '<td>'+(b.cargo_type || '')+'</td>'
          + '<td>৳'+amount.toFixed(2)+'</td>'
          + '<td><span class="badge status-pending">'+(b.status || '')+'</span></td>'
          + '<td>'+(b.requested_at || '')+'</td>'
          + '<td>'+actionHtml+'</td>';

        tbody.appendChild(tr);
      }
    }
  }
}
function filterBookings(){
  var q = document.getElementById("bookingSearch").value.toLowerCase();
  var status = document.getElementById("statusFilter").value;
  var rows = document.querySelectorAll("#bookingTable tbody tr");
  for(var i=0;i<rows.length;i++) {
    var rowText = rows[i].innerText.toLowerCase();
    var rowStatus = rows[i].getAttribute("data-status");
    var okText = (q === "" || rowText.indexOf(q) !== -1);
    var okStatus = (status === "" || rowStatus === status);
    rows[i].style.display = (okText && okStatus) ? "" : "none";
  }
}
