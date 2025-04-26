const select_options = document.querySelectorAll(".status_list");

select_options.forEach((option) => {
  option.addEventListener("change", function () {
    const selected_option = this.value;

    const order_id =
      this.parentElement.parentElement.querySelector(".order_id").innerText;

    const xhr = new XMLHttpRequest();
    xhr.open(
      "POST",
      "https://paws.graceye.ca/pawsAdmin/table_order_view.php",
      true
    );
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      // Handle the response if needed
      console.log(xhr.responseText);
    };
    xhr.send(
      "order-id=" +
        encodeURIComponent(order_id) +
        "&order-action=" +
        encodeURIComponent(selected_option)
    );
  });
});
