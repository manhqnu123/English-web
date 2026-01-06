document.addEventListener("DOMContentLoaded", function () {
  // Chọn tất cả các liên kết có thể bật/tắt submenu
  const menuToggles = document.querySelectorAll(".has-submenu > a.menu-toggle");

  menuToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      const parentLi = this.closest(".has-submenu");
      const submenu = parentLi.querySelector(".submenu");
      const icon = parentLi.querySelector(".submenu-icon");

      if (parentLi.classList.contains("open")) {
        // Đóng submenu
        parentLi.classList.remove("open");
        submenu.style.display = "none";
        icon.classList.remove("fa-chevron-up");
        icon.classList.add("fa-chevron-down");
      } else {
        // Mở submenu
        parentLi.classList.add("open");
        submenu.style.display = "block";
        icon.classList.remove("fa-chevron-down");
        icon.classList.add("fa-chevron-up");
      }
    });
  });
});

