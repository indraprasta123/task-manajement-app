const statusToggles = document.querySelectorAll(".status-toggle");

statusToggles.forEach((toggle) => {
  toggle.addEventListener("change", () => {
    const form = toggle.closest(".status-form");

    if (form) {
      form.submit();
    }
  });
});

// Simple client-side table sorting for the tasks table
document.addEventListener("DOMContentLoaded", () => {
  const table = document.getElementById("tasksTable");
  if (!table) return;

  const headers = Array.from(table.querySelectorAll("th.sortable"));

  headers.forEach((th, colIndex) => {
    th.style.cursor = "pointer";
    th.addEventListener("click", () => {
      const sortType = th.dataset.sort || "string";
      const tbody = table.tBodies[0];
      if (!tbody) return;

      const rows = Array.from(tbody.querySelectorAll("tr"));

      // Toggle ascending/descending
      const asc = th.dataset.asc !== "true";
      headers.forEach((h) => (h.dataset.asc = ""));
      th.dataset.asc = asc ? "true" : "false";

      rows.sort((a, b) => {
        const aCell = a.children[colIndex];
        const bCell = b.children[colIndex];
        const aText = aCell ? aCell.textContent.trim() : "";
        const bText = bCell ? bCell.textContent.trim() : "";
        return compareValues(aText, bText, sortType, asc);
      });

      // Re-append in new order
      rows.forEach((r) => tbody.appendChild(r));

      // Update indicators
      headers.forEach((h) => {
        const ind = h.querySelector(".sort-indicator");
        if (!ind) return;
        if (h === th) ind.textContent = asc ? "▲" : "▼";
        else ind.textContent = "↕";
      });
    });
  });

  function compareValues(a, b, type, asc) {
    const dir = asc ? 1 : -1;

    if (type === "date") {
      const da = a === "-" || a === "" ? new Date(0) : new Date(a);
      const db = b === "-" || b === "" ? new Date(0) : new Date(b);
      return (da - db) * dir;
    }

    if (type === "priority") {
      const map = { high: 3, hard: 3, medium: 2, low: 1 };
      const va = map[a.toLowerCase()] || 0;
      const vb = map[b.toLowerCase()] || 0;
      return (va - vb) * dir;
    }

    if (type === "status") {
      const map = { done: 3, completed: 3, pending: 2, overdue: 1 };
      const va = map[a.toLowerCase()] || 0;
      const vb = map[b.toLowerCase()] || 0;
      return (va - vb) * dir;
    }

    return (
      a.localeCompare(b, undefined, { numeric: true, sensitivity: "base" }) *
      dir
    );
  }
});
