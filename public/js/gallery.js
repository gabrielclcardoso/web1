document.addEventListener("click", function (e) {
  if (
    e.target.classList.contains("like-btn") ||
    e.target.closest(".like-btn")
  ) {
    const btn = e.target.classList.contains("like-btn")
      ? e.target
      : e.target.closest(".like-btn");
    const imageId = btn.getAttribute("data-id");
    const countSpan = btn.querySelector(".like-count");

    fetch("toggle_like.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ imageId: imageId }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          countSpan.innerText = data.newCount;

          if (data.action === "liked") {
            btn.style.color = "red";
          } else {
            btn.style.color = "#555";
          }
        } else if (data.message) {
          alert(data.message);
        }
      });
  }
});
