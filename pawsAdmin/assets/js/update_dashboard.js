const today_price = document.getElementById("today_price");
const yesterday_price = document.getElementById("yesterday_price");
const month_price = document.getElementById("month_price");
const year_price = document.getElementById("year_price");

updateData();
setInterval(updateData, 5000);

function updateData() {
  const xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      const data = JSON.parse(xmlHttp.responseText);
      today_price.innerText = data.today_price;
      yesterday_price.innerText = data.yesterday_price;
      month_price.innerText = data.month_price;
      year_price.innerText = data.year_price;

      console.log(data);
    }
  };

  xmlHttp.open("GET", "https://paws.graceye.ca/api/update_dashboard.php", true);
  xmlHttp.send();
}
