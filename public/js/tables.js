// Profil utilisateur : historique de commandes

$(document).ready(function () {
  $("#userOrderTable").DataTable({
    order: [[1, "DESC"]],
    pageLength: 5,
    lengthMenu: [
      [5, 10, 15, -1],
      [5, 10, 15, "All"],
    ],
    language: {
      lengthMenu: "Montrer _MENU_",
      zeroRecords: "Aucun résultat - désolé",
      info: "Page _PAGE_ sur _PAGES_",
      infoEmpty: "Aucun résultat",
      infoFiltered: "(filtered from _MAX_ total records)",
      paginate: {
        first: "Premier",
        last: "Dernier",
        next: "&raquo;",
        previous: "&laquo;",
      },
      search: "Rechercher:",
    },
  });
});

// Food

$(document).ready(function () {
  $("#foodTable").DataTable({
    order: [[1, "DESC"]],
    pageLength: 10,
    lengthMenu: [
      [5, 10, 15, -1],
      [5, 10, 15, "All"],
    ],
    language: {
      lengthMenu: "Montrer _MENU_",
      zeroRecords: "Aucun résultat - désolé",
      info: "Page _PAGE_ sur _PAGES_",
      infoEmpty: "Aucun résultat",
      infoFiltered: "(filtered from _MAX_ total records)",
      paginate: {
        first: "Premier",
        last: "Dernier",
        next: "&raquo;",
        previous: "&laquo;",
      },
      search: "Rechercher:",
    },
  });
});

// Comments

$(document).ready(function () {
  $("#commentTable").DataTable({
    order: [[5, "desc"]],
    pageLength: 10,
    lengthMenu: [
      [5, 10, 15, -1],
      [5, 10, 15, "All"],
    ],
    language: {
      lengthMenu: "Montrer _MENU_",
      zeroRecords: "Aucun résultat - désolé",
      info: "Page _PAGE_ sur _PAGES_",
      infoEmpty: "Aucun résultat",
      infoFiltered: "(filtered from _MAX_ total records)",
      paginate: {
        first: "Premier",
        last: "Dernier",
        next: "&raquo;",
        previous: "&laquo;",
      },
      search: "Rechercher:",
    },
  });
});

// Orders

$(document).ready(function () {
  $("#orderTable").dataTable({
    order: [[1, "DESC"]],
    pageLength: 10,
    lengthMenu: [
      [5, 10, 15, -1],
      [5, 10, 15, "All"],
    ],
    language: {
      lengthMenu: "Montrer _MENU_",
      zeroRecords: "Aucun résultat - désolé",
      info: "Page _PAGE_ sur _PAGES_",
      infoEmpty: "Aucun résultat",
      infoFiltered: "(filtered from _MAX_ total records)",
      paginate: {
        first: "Premier",
        last: "Dernier",
        next: "&raquo;",
        previous: "&laquo;",
      },
      search: "Rechercher:",
    },
  });
});

// Category

$(document).ready(function () {
  $("#categoryTable").DataTable({
    pageLength: 10,
    lengthMenu: [
      [5, 10, 15, -1],
      [5, 10, 15, "All"],
    ],
    language: {
      lengthMenu: "Montrer _MENU_",
      zeroRecords: "Aucun résultat - désolé",
      info: "Page _PAGE_ sur _PAGES_",
      infoEmpty: "Aucun résultat",
      infoFiltered: "(filtered from _MAX_ total records)",
      paginate: {
        first: "Premier",
        last: "Dernier",
        next: "&raquo;",
        previous: "&laquo;",
      },
      search: "Rechercher:",
    },
  });
});

// Users

$(document).ready(function () {
  $("#userTable").DataTable({
    pageLength: 5,
    lengthMenu: [
      [5, 10, 15, -1],
      [5, 10, 15, "All"],
    ],
    language: {
      lengthMenu: "Montrer _MENU_",
      zeroRecords: "Aucun résultat - désolé",
      info: "Page _PAGE_ sur _PAGES_",
      infoEmpty: "Aucun résultat",
      infoFiltered: "(filtered from _MAX_ total records)",
      paginate: {
        first: "Premier",
        last: "Dernier",
        next: "&raquo;",
        previous: "&laquo;",
      },
      search: "Rechercher:",
    },
  });
});

// Contact

$(document).ready(function () {
  $("#contactTable").DataTable({
    order: [
      [3, "desc"],
      [1, "asc"],
    ],
    pageLength: 5,
    lengthMenu: [
      [5, 10, 15, -1],
      [5, 10, 15, "All"],
    ],
    language: {
      lengthMenu: "Montrer _MENU_",
      zeroRecords: "Aucun résultat - désolé",
      info: "Page _PAGE_ sur _PAGES_",
      infoEmpty: "Aucun résultat",
      infoFiltered: "(filtered from _MAX_ total records)",
      paginate: {
        first: "Premier",
        last: "Dernier",
        next: "&raquo;",
        previous: "&laquo;",
      },
      search: "Rechercher:",
    },
  });
});
