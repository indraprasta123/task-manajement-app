document.addEventListener("DOMContentLoaded", () => {
  const flash = window.__APP_FLASH || {};

  if (flash.success) {
    Swal.fire({
      icon: "success",
      title: "Success",
      text: flash.success,
      confirmButtonText: "OK",
    });
  }

  if (flash.error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: flash.error,
      confirmButtonText: "OK",
    });
  }

  const validateForms = document.querySelectorAll(
    'form[data-swal-validate="true"]',
  );

  validateForms.forEach((form) => {
    form.addEventListener("submit", (event) => {
      const fields = form.querySelectorAll("[required]");
      const emptyField = Array.from(fields).find((field) => {
        const value = typeof field.value === "string" ? field.value.trim() : "";
        return value === "";
      });

      if (emptyField) {
        event.preventDefault();
        const context = form.dataset.swalContext || "form";
        const labels = {
          login: {
            email: "Email is required.",
            password: "Password is required.",
          },
          register: {
            name: "Name is required.",
            email: "Email is required.",
            password: "Password is required.",
          },
          add: {
            title: "Task is required.",
            description: "Description is required.",
            priority: "Priority is required.",
            due_date: "Due date is required.",
          },
          edit: {
            title: "Task is required.",
            description: "Description is required.",
            priority: "Priority is required.",
            due_date: "Due date is required.",
          },
        };
        const fieldName = emptyField.getAttribute("name") || "";
        const message =
          labels[context]?.[fieldName] || "This field is required.";

        Swal.fire({
          icon: "warning",
          title: "Required field",
          text: message,
          confirmButtonText: "OK",
        });
      }
    });
  });

  document.querySelectorAll('[data-swal-delete="true"]').forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();

      const form = button.closest("form");
      if (!form) {
        return;
      }

      Swal.fire({
        icon: "warning",
        title: "Delete task?",
        text: "Deleted tasks cannot be restored.",
        showCancelButton: true,
        confirmButtonText: "Yes, delete",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#ef4444",
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});
