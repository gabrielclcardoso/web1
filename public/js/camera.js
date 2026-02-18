const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const captureBtn = document.getElementById("capture-btn");
const fileUpload = document.getElementById("file-upload");
const clearUploadBtn = document.getElementById("clear-upload");
const overlayItems = document.querySelectorAll(".overlay-item");

let selectedOverlay = null;
let useUploadedFile = false;

function startWebcam() {
  navigator.mediaDevices
    .getUserMedia({ video: true, audio: false })
    .then((stream) => {
      video.srcObject = stream;
    })
    .catch((err) => console.error("Unable to access webcam: ", err));
}
startWebcam();

// Disable picture without overlay
overlayItems.forEach((item) => {
  item.addEventListener("click", () => {
    overlayItems.forEach((i) => i.classList.remove("selected"));
    item.classList.add("selected");
    selectedOverlay = item.getAttribute("src");
    captureBtn.disabled = false;
  });
});

// Image upload logic
fileUpload.addEventListener("change", (e) => {
  const file = e.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = (event) => {
    const img = new Image();
    img.onload = () => {
      const MAX_WIDTH = 640;
      let width = img.width;
      let height = img.height;

      if (width > MAX_WIDTH) {
        height = height * (MAX_WIDTH / width);
        width = MAX_WIDTH;
      }

      canvas.width = width;
      canvas.height = height;

      const ctx = canvas.getContext("2d");
      ctx.drawImage(img, 0, 0, width, height);

      // Switch from video to canvas
      video.style.display = "none";
      canvas.style.display = "block";
      clearUploadBtn.style.display = "inline-block";
      useUploadedFile = true;
    };
    img.src = event.target.result;
  };
  reader.readAsDataURL(file);
});

clearUploadBtn.addEventListener("click", () => {
  fileUpload.value = "";
  useUploadedFile = false;
  canvas.style.display = "none";
  video.style.display = "block";
  clearUploadBtn.style.display = "none";
});

captureBtn.addEventListener("click", () => {
  let imageData;

  if (useUploadedFile) {
    imageData = canvas.toDataURL("image/jpeg", 0.8);
  } else {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext("2d");
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    imageData = canvas.toDataURL("image/jpeg", 0.8);
  }

  fetch("save_post.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      image: imageData,
      overlay: selectedOverlay,
      is_webcam: !useUploadedFile,
    }),
  })
    .then((response) => {
      if (!response.ok) throw new Error("Error: " + response.statusText);
      return response.json();
    })
    .then((data) => {
      if (data.success) alert("Picture uploaded successfully");
      else alert("Error: " + data.message);
    })
    .catch((error) => {
      alert(error.message);
    });
});
