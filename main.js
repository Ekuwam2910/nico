jQuery("document").ready(function ($) {
  // make recaptcha required...
  setTimeout(function () {
    var $recaptcha = document.querySelector("#g-recaptcha-response");

    if ($recaptcha) {
      $recaptcha.setAttribute("required", "required");
    }
  }, 750);

  var nav = $(".menu-bar");

  $(".menu-list li a").on("click", function (e) {
    var t = $(this.hash);
    var t = (t.length && t) || $("[name=" + this.hash.slice(1) + "]");
    if (t.length) {
      var tOffset = t.offset().top;
      $("html,body").animate({ scrollTop: tOffset - 500 }, "slow");
      e.preventDefault();
      return false;
    }
  });

  $(window).scroll(function () {
    if ($(this).scrollTop() > 200) {
      nav.addClass("f-nav");
    } else {
      nav.removeClass("f-nav");
    }
  });

  $("#my-contact-form").submit(function (event) {
    $(".form-field").removeClass("has-error");
    $(".error-message").remove();

    $.ajax({
      type: "POST",
      url: "contact.php",
      data: $(this).serialize(),
      dataType: "json",
      encode: true
    }).done(function (data) {
      //   console.log(data);
      if (!data.success) {
        for (var fieldError of Object.keys(data.errors)) {
          $(".form-field." + fieldError).addClass("has-error");
          $(".form-field." + fieldError).append('<div class="error-message">' + data.errors[fieldError] + "</div>");
        }
      } else {
        $("#my-contact-form").addClass("hidden");
        $("#success-message").removeClass("hidden");
      }
    });

    event.preventDefault();
  });
});

var modalInfo = {
  1: {
    title: "Project 1",
    info: "...",
    link: "#",
    github: "#"
  },
  2: {
    title: "Project 2",
    info: "...",
    link: "#",
    github: "#"
  },
  3: {
    title: "Project 3",
    info: "...",
    link: "#",
    github: "#"
  },
  4: {
    title: "Project 4",
    info: "....",
    link: "#",
    github: "#"
  },
  5: {
    title: "Project 5",
    info: "...",
    link: "#",
    github: "#"
  },
  6: {
    title: "Project 6",
    info: "...",
    link: "#",
    github: "#"
  }
};

// Get the modal
var modal = document.getElementById('preview');

// button that opens the modal
var btn = document.getElementsByClassName("button");

// <span> that closes the modal
var span = document.getElementsByClassName("close")[0];

// open modal 
for(let i = 0; i < btn.length; i++){
  btn[i].addEventListener("click", function() {
    var project = btn[i].parentElement;
    openModal(project);
  })
};

function openModal(project){
  var id = project.id;
  var img = project.getElementsByTagName("img")[0].src;
  fillOut(id, img);
  modal.style.display = "block";
  document.getElementsByClassName("modal-content")[0].classList.add("scale");
}

function fillOut(id, img){
  document.getElementById("title").innerHTML = modalInfo[id].title;
  document.getElementById("info").innerHTML = modalInfo[id].info;
  document.getElementById("img").src = img;
  document.getElementById("live").onclick = function(){
    window.open(modalInfo[id].link,'_blank');
  }
  document.getElementById("github").onclick = function(){
    window.open(modalInfo[id].github,'_blank');
  }
}

// close the modal
span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}