document.addEventListener("DOMContentLoaded", () => {
  const managementModeButton = document.getElementById("managementModeButton");

  const managementActions = document.querySelectorAll(".management-actions");

  if (!managementModeButton) {
    return;
  }

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
});
