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

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-send-comment")) {
    const btn = e.target;
    const imageId = btn.getAttribute("data-id");
    const inputField = document.getElementById("comment-input-" + imageId);
    const commentText = inputField.value.trim();

    if (commentText === "") return;

    btn.disabled = true;

    fetch("add_comment.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ imageId: imageId, comment: commentText }),
    })
      .then((res) => res.json())
      .then((data) => {
        btn.disabled = false;

        if (data.success) {
          inputField.value = "";

          const imageCard = btn.closest(".image-card");
          const commentsArea = imageCard.querySelector(".image-comments");

          const newComment = document.createElement("div");
          newComment.className = "comment-item";
          newComment.innerHTML = `<strong>${data.username}:</strong> ${data.content}`;

          const addCommentDiv = imageCard.querySelector(".add-comment");
          commentsArea.insertBefore(newComment, addCommentDiv);
        } else {
          alert("Erro: " + data.message);
        }
      })
      .catch((err) => {
        console.error(err);
        btn.disabled = false;
      });
  }
});
