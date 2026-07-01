
        let bgRemovedCanvas = null;
        let bgOriginalImage = null;
        let currentBgColor = "transparent";
        let currentViewMode = "removed";
        let currentFileName = "background-removed";

        const bgInput = document.getElementById("bgRemoverInput");
        const bgHeroUpload = document.getElementById("bgHeroUpload");
        const bgProcessingWrap = document.getElementById("bgProcessingWrap");
        const bgResultContainer = document.getElementById("bgResultContainer");
        const bgDisplayImg = document.getElementById("bgDisplayImg");
        const bgPreviewBox = document.getElementById("bgPreviewBox");
        const bgProgressBar = document.getElementById("bgProgressBar");
        const bgProcessingStatus = document.getElementById("bgProcessingStatus");
        const bgResultMeta = document.getElementById("bgResultMeta");
        const bgDownloadText = document.getElementById("bgDownloadText");

        // View Mode Toggles
        const btnViewRemoved = document.getElementById("btnViewRemoved");
        const btnViewOriginal = document.getElementById("btnViewOriginal");

        function updateViewMode(mode) {
            currentViewMode = mode;
            if (mode === "removed") {
                btnViewRemoved.className = "px-4 py-1.5 text-xs font-bold rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition";
                btnViewOriginal.className = "px-4 py-1.5 text-xs font-bold rounded-lg text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition";
                document.getElementById("bgSwatchBar").style.display = "flex";
                if (bgRemovedCanvas) {
                    bgDisplayImg.src = bgRemovedCanvas.toDataURL("image/png");
                }
                applyBackgroundSwatch(currentBgColor);
            } else {
                btnViewOriginal.className = "px-4 py-1.5 text-xs font-bold rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition";
                btnViewRemoved.className = "px-4 py-1.5 text-xs font-bold rounded-lg text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition";
                document.getElementById("bgSwatchBar").style.display = "none";
                if (bgOriginalImage) {
                    bgDisplayImg.src = bgOriginalImage.src;
                }
                bgPreviewBox.style.background = "transparent";
                bgPreviewBox.style.backgroundImage = "none";
            }
        }

        btnViewRemoved.addEventListener("click", () => updateViewMode("removed"));
        btnViewOriginal.addEventListener("click", () => updateViewMode("original"));

        // Swatches
        function applyBackgroundSwatch(color) {
            currentBgColor = color;
            document.querySelectorAll(".bg-swatch").forEach(btn => {
                if (btn.getAttribute("data-bg") === color) {
                    btn.classList.add("border-2", "border-blue-500", "scale-110");
                    btn.classList.remove("border-gray-300", "dark:border-gray-600");
                } else {
                    btn.classList.remove("border-2", "border-blue-500", "scale-110");
                    btn.classList.add("border-gray-300", "dark:border-gray-600");
                }
            });

            if (color === "transparent") {
                bgPreviewBox.style.backgroundColor = "transparent";
                bgPreviewBox.style.backgroundImage = "linear-gradient(45deg, rgba(0,0,0,0.05) 25%, transparent 25%), linear-gradient(-45deg, rgba(0,0,0,0.05) 25%, transparent 25%), linear-gradient(45deg, transparent 75%, rgba(0,0,0,0.05) 75%), linear-gradient(-45deg, transparent 75%, rgba(0,0,0,0.05) 75%)";
                bgPreviewBox.style.backgroundSize = "20px 20px";
                bgPreviewBox.style.backgroundPosition = "0 0, 0 10px, 10px -10px, -10px 0px";
                bgDownloadText.innerText = "Download Transparent PNG";
            } else {
                bgPreviewBox.style.backgroundImage = "none";
                bgPreviewBox.style.backgroundColor = color;
                bgDownloadText.innerText = "Download Image with Background";
            }
        }

        document.querySelectorAll(".bg-swatch").forEach(btn => {
            btn.addEventListener("click", function() {
                applyBackgroundSwatch(this.getAttribute("data-bg"));
            });
        });

        document.getElementById("bgCustomColor").addEventListener("input", function() {
            applyBackgroundSwatch(this.value);
        });

        // Reset
        document.getElementById("bgResetBtn").addEventListener("click", () => {
            bgInput.value = "";
            bgResultContainer.classList.add("hidden");
            bgProcessingWrap.classList.add("hidden");
            bgHeroUpload.classList.remove("hidden");
            bgRemovedCanvas = null;
            bgOriginalImage = null;
        });

        // Smart Canvas Fallback (High precision edge flood fill without erasing subject)
        function removeBgSmartCanvas(img) {
            const maxSide = 2000;
            const scale = Math.min(1, maxSide / Math.max(img.width, img.height));
            const width = Math.max(1, Math.round(img.width * scale));
            const height = Math.max(1, Math.round(img.height * scale));
            
            const canvas = document.createElement("canvas");
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext("2d", { willReadFrequently: true });
            ctx.drawImage(img, 0, 0, width, height);
            
            const imageData = ctx.getImageData(0, 0, width, height);
            const data = imageData.data;
            const len = width * height;
            
            // Sample perimeter border to compute true background color
            let rSum = 0, gSum = 0, bSum = 0, count = 0;
            const step = Math.max(1, Math.floor(Math.min(width, height) / 40));
            for (let x = 0; x < width; x += step) {
                let i1 = (x) * 4, i2 = ((height - 1) * width + x) * 4;
                rSum += data[i1] + data[i2]; gSum += data[i1+1] + data[i2+1]; bSum += data[i1+2] + data[i2+2]; count += 2;
            }
            for (let y = 0; y < height; y += step) {
                let i1 = (y * width) * 4, i2 = (y * width + width - 1) * 4;
                rSum += data[i1] + data[i2]; gSum += data[i1+1] + data[i2+1]; bSum += data[i1+2] + data[i2+2]; count += 2;
            }
            const bgR = rSum / count, bgG = gSum / count, bgB = bSum / count;
            // Smart Canvas Fallback (High precision edge flood fill with drop-shadow removal)
            function removeBgSmartCanvas(img) {
                const maxSide = 2000;
                const scale = Math.min(1, maxSide / Math.max(img.width, img.height));
                const width = Math.max(1, Math.round(img.width * scale));
                const height = Math.max(1, Math.round(img.height * scale));
                
                const canvas = document.createElement("canvas");
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext("2d", { willReadFrequently: true });
                ctx.drawImage(img, 0, 0, width, height);
                
                const imageData = ctx.getImageData(0, 0, width, height);
                const data = imageData.data;
                const len = width * height;
                
                // Sample perimeter border to compute true background color
                let rSum = 0, gSum = 0, bSum = 0, count = 0;
                const step = Math.max(1, Math.floor(Math.min(width, height) / 40));
                for (let x = 0; x < width; x += step) {
                    let i1 = (x) * 4, i2 = ((height - 1) * width + x) * 4;
                    rSum += data[i1] + data[i2]; gSum += data[i1+1] + data[i2+1]; bSum += data[i1+2] + data[i2+2]; count += 2;
                }
                for (let y = 0; y < height; y += step) {
                    let i1 = (y * width) * 4, i2 = (y * width + width - 1) * 4;
                    rSum += data[i1] + data[i2]; gSum += data[i1+1] + data[i2+1]; bSum += data[i1+2] + data[i2+2]; count += 2;
                }
                const bgR = rSum / count, bgG = gSum / count, bgB = bSum / count;
                
                function colorDist(r1, g1, b1, r2, g2, b2) {
                    // Perceptual luma-weighted color distance
                    const dr = r1 - r2, dg = g1 - g2, db = b1 - b2;
                    let dist = Math.sqrt(0.3 * dr*dr + 0.59 * dg*dg + 0.11 * db*db);
                    // Drop-shadow recognition on light/white backgrounds
                    if (bgR > 180 && bgG > 180 && bgB > 180) {
                        const luma1 = 0.3*r1 + 0.59*g1 + 0.11*b1;
                        const luma2 = 0.3*r2 + 0.59*g2 + 0.11*b2;
                        const chroma1 = Math.max(r1, g1, b1) - Math.min(r1, g1, b1);
                        // If pixel is darker (shadow) but has low chroma (neutral gray), treat as background shadow!
                        if (luma1 < luma2 && chroma1 < 25 && luma1 > 70) {
                            dist *= 0.45; // significantly reduce distance for neutral drop shadows!
                        }
                    }
                    return dist;
                }
                
                const visited = new Uint8Array(len);
                const alphaMap = new Float32Array(len);
                alphaMap.fill(1.0);
                
                const queue = [];
                const tolerance = 30;
                const fadeRange = 16;
                
                function tryPush(x, y) {
                    if (x < 0 || y < 0 || x >= width || y >= height) return;
                    const pos = y * width + x;
                    if (visited[pos]) return;
                    const idx = pos * 4;
                    const dist = colorDist(data[idx], data[idx+1], data[idx+2], bgR, bgG, bgB);
                    if (dist <= tolerance + fadeRange) {
                        visited[pos] = 1;
                        queue.push(pos);
                    }
                }
                
                for (let x = 0; x < width; x++) { tryPush(x, 0); tryPush(x, height - 1); }
                for (let y = 0; y < height; y++) { tryPush(0, y); tryPush(width - 1, y); }
                
                let head = 0;
                while (head < queue.length) {
                    const pos = queue[head++];
                    const x = pos % width;
                    const y = Math.floor(pos / width);
                    const idx = pos * 4;
                    const dist = colorDist(data[idx], data[idx+1], data[idx+2], bgR, bgG, bgB);
                    
                    if (dist <= tolerance) {
                        alphaMap[pos] = 0.0;
                    } else {
                        const ratio = (dist - tolerance) / fadeRange;
                        alphaMap[pos] = Math.min(1.0, Math.max(0.0, ratio));
                    }
                    
                    if (dist <= tolerance + 6) {
                        tryPush(x + 1, y);
                        tryPush(x - 1, y);
                        tryPush(x, y + 1);
                        tryPush(x, y - 1);
                    }
                }
                
                for (let i = 0; i < len; i++) {
                    data[i * 4 + 3] = Math.round(data[i * 4 + 3] * alphaMap[i]);
                }
                ctx.putImageData(imageData, 0, 0);
                return canvas;
            }

            // Post-Processing: Edge Polish & Halo Decontamination (Removes dark fringing/jaggies)
            function polishRemovedEdges(canvas) {
                const ctx = canvas.getContext("2d", { willReadFrequently: true });
                const width = canvas.width, height = canvas.height;
                const imgData = ctx.getImageData(0, 0, width, height);
                const data = imgData.data;
                const len = width * height;

                // Pass 1: Clean up stray noise and semi-transparent shadow dust
                for (let i = 0; i < len; i++) {
                    const alpha = data[i * 4 + 3];
                    if (alpha > 0 && alpha < 40) {
                        data[i * 4 + 3] = 0;
                    } else if (alpha > 220) {
                        data[i * 4 + 3] = 255;
                    }
                }

                // Pass 2: Color Decontamination (Fringe Removal)
                const origData = new Uint8ClampedArray(data);
                for (let y = 1; y < height - 1; y++) {
                    for (let x = 1; x < width - 1; x++) {
                        const pos = y * width + x;
                        const idx = pos * 4;
                        const alpha = origData[idx + 3];
                        if (alpha > 0) {
                            let isEdge = (alpha < 250);
                            if (!isEdge) {
                                if (origData[idx - 4 + 3] < 50 || origData[idx + 4 + 3] < 50 ||
                                    origData[(pos - width) * 4 + 3] < 50 || origData[(pos + width) * 4 + 3] < 50) {
                                    isEdge = true;
                                }
                            }
                            if (isEdge) {
                                let rSum = 0, gSum = 0, bSum = 0, count = 0;
                                for (let dy = -2; dy <= 2; dy++) {
                                    for (let dx = -2; dx <= 2; dx++) {
                                        if (dx === 0 && dy === 0) continue;
                                        const nx = x + dx, ny = y + dy;
                                        if (nx >= 0 && nx < width && ny >= 0 && ny < height) {
                                            const nIdx = (ny * width + nx) * 4;
                                            if (origData[nIdx + 3] > 230) {
                                                rSum += origData[nIdx];
                                                gSum += origData[nIdx + 1];
                                                bSum += origData[nIdx + 2];
                                                count++;
                                            }
                                        }
                                    }
                                }
                                if (count > 0) {
                                    const blend = Math.min(1, Math.max(0.45, (255 - alpha) / 180));
                                    data[idx]     = Math.round(data[idx] * (1 - blend) + (rSum / count) * blend);
                                    data[idx + 1] = Math.round(data[idx + 1] * (1 - blend) + (gSum / count) * blend);
                                    data[idx + 2] = Math.round(data[idx + 2] * (1 - blend) + (bSum / count) * blend);
                                }
                            }
                        }
                    }
                }

                // Pass 3: Smooth Alpha Anti-Aliasing Feather
                const afterDecontam = new Uint8ClampedArray(data);
                for (let y = 1; y < height - 1; y++) {
                    for (let x = 1; x < width - 1; x++) {
                        const pos = y * width + x;
                        const idx = pos * 4;
                        const alpha = afterDecontam[idx + 3];
                        if (alpha > 0 && alpha < 255) {
                            let aSum = (
                                afterDecontam[idx - width * 4 - 4 + 3] * 0.05 +
                                afterDecontam[idx - width * 4 + 3] * 0.1 +
                                afterDecontam[idx - width * 4 + 4 + 3] * 0.05 +
                                afterDecontam[idx - 4 + 3] * 0.1 +
                                alpha * 0.4 +
                                afterDecontam[idx + 4 + 3] * 0.1 +
                                afterDecontam[idx + width * 4 - 4 + 3] * 0.05 +
                                afterDecontam[idx + width * 4 + 3] * 0.1 +
                                afterDecontam[idx + width * 4 + 4 + 3] * 0.05
                            );
                            data[idx + 3] = Math.round(aSum);
                        }
                    }
                }
                ctx.putImageData(imgData, 0, 0);
            }

            // Main Processing
            bgInput.addEventListener("change", async function() {
                const file = this.files[0];
                if (!file) return;

                currentFileName = file.name.replace(/\.[^.]+$/, "") || "background-removed";
                
                bgHeroUpload.classList.add("hidden");
                bgResultContainer.classList.add("hidden");
                bgProcessingWrap.classList.remove("hidden");
                bgProgressBar.style.width = "15%";
                bgProcessingStatus.innerText = "AI neural network is initializing... Isolating foreground.";

                // Load Original Image
                bgOriginalImage = new Image();
                bgOriginalImage.src = URL.createObjectURL(file);
                await new Promise(res => { bgOriginalImage.onload = res; });

                try {
                    // Attempt AI Neural Background Removal with strict timeout & publicPath
                    let aiFunc = null;
                    if (window.imglyRemoveBackground && window.imglyRemoveBackground.removeBackground) {
                        aiFunc = window.imglyRemoveBackground.removeBackground;
                    } else {
                        try {
                            const mod = await import("https://cdn.jsdelivr.net/npm/@imgly/background-removal@1.5.8/+esm");
                            aiFunc = mod.default || mod.removeBackground;
                        } catch (e) {
                            const mod2 = await import("https://esm.sh/@imgly/background-removal@1.5.8");
                            aiFunc = mod2.default || mod2.removeBackground;
                        }
                    }

                    if (!aiFunc) throw new Error("AI engine not available");

                    bgProgressBar.style.width = "30%";
                    bgProcessingStatus.innerText = "Downloading AI model & isolating subject (please wait)...";

                    const aiPromise = aiFunc(file, {
                        publicPath: "https://static.imgly.com/@imgly/background-removal-data/1.5.8/dist/",
                        progress: (key, current, total) => {
                            if (total > 0) {
                                const pct = Math.min(95, Math.max(30, Math.round((current / total) * 95)));
                                bgProgressBar.style.width = pct + "%";
                                if (key && total > 1000000) {
                                    bgProcessingStatus.innerText = `Downloading AI model (${Math.round(current/1048576)}MB / ${Math.round(total/1048576)}MB)...`;
                                }
                            }
                        }
                    });

                    const timeoutPromise = new Promise((_, reject) => {
                        setTimeout(() => reject(new Error("AI engine timeout")), 60000);
                    });

                    const resultBlob = await Promise.race([aiPromise, timeoutPromise]);

                    const aiImg = new Image();
                    aiImg.src = URL.createObjectURL(resultBlob);
                    await new Promise(res => { aiImg.onload = res; });

                    const canvas = document.createElement("canvas");
                    canvas.width = aiImg.width;
                    canvas.height = aiImg.height;
                    const ctx = canvas.getContext("2d");
                    ctx.drawImage(aiImg, 0, 0);
                    bgRemovedCanvas = canvas;

                } catch (err) {
                    console.warn("AI removal timed out or offline, switching to instant smart canvas segmentation:", err);
                    bgProgressBar.style.width = "75%";
                    bgProcessingStatus.innerText = "Polishing subject outline and removing background...";
                    await new Promise(r => setTimeout(r, 50)); // allow UI update
                    bgRemovedCanvas = removeBgSmartCanvas(bgOriginalImage);
                }

                // Polish edges (remove dark halos, shadow chunks & jaggies)
                if (bgRemovedCanvas) {
                    polishRemovedEdges(bgRemovedCanvas);
                }

                bgProgressBar.style.width = "100%";
                setTimeout(() => {
                    bgProcessingWrap.classList.add("hidden");
                    bgResultContainer.classList.remove("hidden");
                    bgResultMeta.innerText = `${bgRemovedCanvas.width} × ${bgRemovedCanvas.height} px • High Definition PNG`;
                    updateViewMode("removed");
                }, 300);
        });

        // Download Handler
        document.getElementById("bgDownloadBtn").addEventListener("click", function() {
            if (!bgRemovedCanvas) return;

            const finalCanvas = document.createElement("canvas");
            finalCanvas.width = bgRemovedCanvas.width;
            finalCanvas.height = bgRemovedCanvas.height;
            const ctx = finalCanvas.getContext("2d");

            if (currentBgColor !== "transparent") {
                ctx.fillStyle = currentBgColor;
                ctx.fillRect(0, 0, finalCanvas.width, finalCanvas.height);
            }

            ctx.drawImage(bgRemovedCanvas, 0, 0);

            finalCanvas.toBlob(function(blob) {
                if (!blob) return;
                const url = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = currentFileName + (currentBgColor === "transparent" ? "-transparent.png" : "-bg.png");
                a.click();
                URL.revokeObjectURL(url);
            }, "image/png");
        });
    