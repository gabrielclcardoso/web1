const video = document.getElementById("video");
const captureBtn = document.getElementById("capture-btn");
const overlayItems = document.querySelectorAll(".overlay-item");
let selectedOverlay = null;

navigator.mediaDevices
  .getUserMedia({ video: true, audio: false })
  .then((stream) => {
    video.srcObject = stream;
  })
  .catch((err) => console.error("Unable to access webcam: ", err));

overlayItems.forEach((item) => {
  item.addEventListener("click", () => {
    overlayItems.forEach((i) => i.classList.remove("selected"));
    item.classList.add("selected");
    selectedOverlay = item.getAttribute("src");
    captureBtn.disabled = false;
  });
});

captureBtn.addEventListener("click", () => {
  const canvas = document.getElementById("canvas");
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;

  const ctx = canvas.getContext("2d");
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

  const imageData = canvas.toDataURL("image/png");

  fetch("save_post.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      image: imageData,
      overlay: selectedOverlay,
    }),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Server error: " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) alert("Picture uploaded successfully");
      else alert("Error: " + data.message);
    })
    .catch((error) => {
      console.error("Fetch error:", error);
      alert("Unexpected error, check the console");
    });
});
