const statusToggles = document.querySelectorAll(".status-toggle");

statusToggles.forEach((toggle) => {
  toggle.addEventListener("change", () => {
    const form = toggle.closest(".status-form");

    if (form) {
      form.submit();
    }
  });
});
