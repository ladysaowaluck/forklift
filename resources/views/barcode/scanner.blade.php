<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>📷 Scan Barcode from Image</title>
  <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body style="text-align:center; padding:20px">

  <h2>📷 Upload / Take Photo to Scan Barcode</h2>
  <input type="file" id="file-input" accept="image/*" capture="environment" />
  <p id="result" style="font-size:1.5rem; color:green; font-weight:bold;"></p>
  <div id="reader" style="display:none;"></div>

  <script>
    const fileInput = document.getElementById("file-input");
    const resultEl = document.getElementById("result");

    fileInput.addEventListener("change", async (e) => {
      const file = e.target.files[0];
      if (!file) return;

      const qrScanner = new Html5Qrcode("reader");
      try {
        const result = await qrScanner.scanFile(file, true);
        resultEl.innerText = "✅ " + result;
      } catch (err) {
        resultEl.innerText = "❌ Could not read barcode";
        console.error(err);
      }
    });
  </script>

</body>
</html>
