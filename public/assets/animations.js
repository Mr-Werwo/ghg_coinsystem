document.addEventListener("DOMContentLoaded", function () {
    // Weiches Fading beim Laden der Seite
    document.body.style.opacity = 0;
    document.body.style.transition = "opacity 1.5s";
    setTimeout(() => {
        document.body.style.opacity = 1;
    }, 100);

    // Hover-Effekt für Buttons
    let buttons = document.querySelectorAll("button, .btn-logout");
    buttons.forEach(button => {
        button.addEventListener("mouseover", function () {
            this.style.transform = "scale(1.1)";
        });
        button.addEventListener("mouseleave", function () {
            this.style.transform = "scale(1)";
        });
    });

    // Navigationseffekt
    let links = document.querySelectorAll("nav ul li a");
    links.forEach(link => {
        link.addEventListener("mouseover", function () {
            this.style.textShadow = "0 0 10px #00eaff";
        });
        link.addEventListener("mouseleave", function () {
            this.style.textShadow = "none";
        });
    });
});

