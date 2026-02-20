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

function loadGallery() {
  fetch("get_user_images.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const gallery = document.getElementById("gallery-preview");

        gallery.innerHTML =
          "<h3>My Recent Pictures</h3><div class='thumbnail-container'></div>";
        const container = gallery.querySelector(".thumbnail-container");

        if (data.images.length === 0) {
          container.innerHTML = "<p style='color: #777;'>No pictures yet</p>";
          return;
        }

        data.images.forEach((image) => {
          const wrapper = document.createElement("div");
          wrapper.className = "thumbnail-wrapper";

          const img = document.createElement("img");
          img.src = image.path;
          img.className = "gallery-thumbnail";

          const delBtn = document.createElement("button");
          delBtn.innerHTML = "&times;";
          delBtn.className = "delete-btn";

          delBtn.onclick = () => {
            if (confirm("Delete this picture?")) {
              deleteImage(image.id, wrapper);
            }
          };

          wrapper.appendChild(img);
          wrapper.appendChild(delBtn);
          container.appendChild(wrapper);
        });
      }
    })
    .catch((err) => console.log("Unable to load gallery.", err));
}
loadGallery();

function deleteImage(imageId, wrapperElement) {
  fetch("delete_image.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ imageId: imageId }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        wrapperElement.remove();
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch((err) => alert("Error:", err));
}

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
      if (data.success) {
        alert("Picture uploaded successfully");
        loadGallery();
        if (useUploadedFile) clearUploadBtn.click();
      } else alert("Error: " + data.message);
    })
    .catch((error) => {
      alert(error.message);
    });
});
