$(function () {
  "use strict";

  $(".preloader").fadeOut();

  $(".left-sidebar").hover(
    function () {
      $(".navbar-header").addClass("expand-logo");
    },
    function () {
      $(".navbar-header").removeClass("expand-logo");
    }
  );

  $(".nav-toggler").on("click", function () {
    $("#main-wrapper").toggleClass("show-sidebar");
    $(".nav-toggler i").toggleClass("ti-menu");
  });

  $(".nav-lock").on("click", function () {
    $("body").toggleClass("lock-nav");
    $(".nav-lock i").toggleClass("mdi-toggle-switch-off");
    $("body, .page-wrapper").trigger("resize");
  });

  $(".search-box a, .search-box .app-search .srh-btn").on("click", function () {
    $(".app-search").toggle(200);
    $(".app-search input").focus();
  });

  $(".service-panel-toggle").on("click", function () {
    $(".customizer").toggleClass("show-service-panel");
  });

  $(".page-wrapper").on("click", function () {
    $(".customizer").removeClass("show-service-panel");
  });

  $(".floating-labels .form-control")
    .on("focus blur", function (e) {
      $(this)
        .parents(".form-group")
        .toggleClass("focused", e.type === "focus" || this.value.length > 0);
    })
    .trigger("blur");

  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover();

  $(".message-center, .customizer-body, .scrollable").perfectScrollbar({
    wheelPropagation: !0,
  });

  $("body, .page-wrapper").trigger("resize");
  $(".page-wrapper").delay(20).show();

  $(".list-task li label").click(function () {
    $(this).toggleClass("task-done");
  });

  var setsidebartype = function () {
    var width = window.innerWidth > 0 ? window.innerWidth : this.screen.width;
    if (width < 1170) {
      $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
    } else {
      $("#main-wrapper").attr("data-sidebartype", "full");
    }
  };

  $(window).ready(setsidebartype);
  $(window).on("resize", setsidebartype);

  $(".sidebartoggler").on("click", function () {
    $("#main-wrapper").toggleClass("mini-sidebar");
    if ($("#main-wrapper").hasClass("mini-sidebar")) {
      $(".sidebartoggler").prop("checked", !0);
      $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
    } else {
      $(".sidebartoggler").prop("checked", !1);
      $("#main-wrapper").attr("data-sidebartype", "full");
    }
  });

});



/* =====================================================
   AVAILABLE QUIZ PAGE SEARCH + CATEGORY FILTER
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

  if (document.querySelector(".quiz-item")) {

    document.querySelectorAll(".category-chip").forEach(button => {

      button.addEventListener("click", function () {

        document.querySelectorAll(".category-chip")
          .forEach(btn => btn.classList.remove("active"));

        this.classList.add("active");

        let category = this.dataset.category;

        document.querySelectorAll(".quiz-item").forEach(quiz => {

          if (category === "all") {
            quiz.style.display = "block";
          }

          else if (quiz.dataset.category === category) {
            quiz.style.display = "block";
          }

          else {
            quiz.style.display = "none";
          }

        });

      });

    });

  }

});


/* =====================================================
   MANAGE QUIZ PAGE
   SEARCH + FILTER + PAGINATION
===================================================== */

document.addEventListener("DOMContentLoaded", function () {

  const table = document.querySelector("#zero_config");

  if (!table) return;

  let rowsPerPage = 5;
  let rows = table.querySelectorAll("tbody tr");
  let rowsCount = rows.length;

  let pageCount = Math.ceil(rowsCount / rowsPerPage);
  let pagination = document.createElement("ul");

  pagination.classList.add("pagination", "justify-content-center");

  for (let i = 1; i <= pageCount; i++) {

    let li = document.createElement("li");
    li.classList.add("page-item");

    let a = document.createElement("a");
    a.classList.add("page-link");
    a.innerText = i;

    li.appendChild(a);
    pagination.appendChild(li);

  }

  table.after(pagination);


  function showPage(page) {

    rows.forEach((row, i) => {

      row.style.display =
        (i >= rowsPerPage * (page - 1) && i < rowsPerPage * page)
          ? ""
          : "none";

    });

  }

  showPage(1);

  pagination.querySelectorAll("a").forEach((btn, index) => {

    btn.addEventListener("click", function (e) {

      e.preventDefault();
      showPage(index + 1);

    });

  });


  const searchInput = document.getElementById("quizSearch");

  if (searchInput) {

    searchInput.addEventListener("keyup", function () {

      let search = this.value.toLowerCase();

      rows.forEach(row => {

        let text = row.innerText.toLowerCase();

        row.style.display =
          text.includes(search) ? "" : "none";

      });

    });

  }

  $(document).ready(function () {

    if ($("#quizTable").length) {

      var table = $("#quizTable").DataTable({

        pageLength: 10,

        lengthMenu: [10, 25, 50, 100],

        ordering: true,

        info: true

      });


      /* SEARCH */

      $("#quizSearch").on("keyup", function () {

        table.search(this.value).draw();

      });


      /* CATEGORY FILTER */

      $("#categoryFilter").on("change", function () {

        table.column(1).search(this.value).draw();

      });

    }

  });


});



function showMessage(type, msg) {
  $("#loginMsg")
    .removeClass("d-none alert-success alert-danger")
    .addClass("alert-" + type)
    .text(msg);
}

