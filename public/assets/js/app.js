document.addEventListener("DOMContentLoaded", () => {
  // Toggle management controls; authorization remains server-side

  const managementModeButton = document.getElementById("managementModeButton");

  const managementActions = document.querySelectorAll(".management-actions");

  if (managementModeButton) {
    let managementMode = false;

    managementModeButton.addEventListener("click", () => {
      managementMode = !managementMode;

      managementActions.forEach((actions) => {
        actions.classList.toggle("d-none", !managementMode);
      });

      if (managementMode) {
        managementModeButton.classList.remove("btn-outline-secondary");

        managementModeButton.classList.add("btn-success");

        managementModeButton.textContent = "Mode gestion activé";
      } else {
        managementModeButton.classList.remove("btn-success");

        managementModeButton.classList.add("btn-outline-secondary");

        managementModeButton.textContent = "Mode gestion";
      }
    });
  }

  // Load fresh statistics when the panel opens

  const statisticsOffcanvas = document.getElementById("statisticsOffcanvas");

  const statisticsOffcanvasBody = document.getElementById(
    "statisticsOffcanvasBody",
  );

  if (statisticsOffcanvas && statisticsOffcanvasBody) {
    statisticsOffcanvas.addEventListener("show.bs.offcanvas", async () => {
      statisticsOffcanvasBody.innerHTML =
        '<div class="text-muted">Chargement...</div>';

      const appScript = document.querySelector("script[data-base-url]");

      const baseUrl = appScript?.dataset.baseUrl ?? "";

      try {
        const response = await fetch(`${baseUrl}/statistics/offcanvas`);

        if (!response.ok) {
          throw new Error("Erreur lors du chargement");
        }

        statisticsOffcanvasBody.innerHTML = await response.text();
      } catch (error) {
        statisticsOffcanvasBody.innerHTML = `
            <div class="alert alert-danger">
              Impossible de charger les statistiques.
            </div>
          `;
      }
    });
  }
});
