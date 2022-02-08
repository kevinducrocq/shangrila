var exampleModalSuppr = document.getElementById("hide_item");
exampleModalSuppr.addEventListener("show.bs.modal", function (event) {
  var button = event.relatedTarget;
  var link = button.getAttribute("data-bs-link");
  var modalLinkValue = exampleModalSuppr.querySelector(".btn-suppr");
  modalLinkValue.href = link;
});

var exampleModalDeleteProduct = document.getElementById("delete_item");
exampleModalDeleteProduct.addEventListener("show.bs.modal", function (event) {
  var button = event.relatedTarget;
  var link = button.getAttribute("data-bs-link");
  var modalLinkValue = exampleModalDeleteProduct.querySelector(".btn-delete");
  modalLinkValue.href = link;
});
