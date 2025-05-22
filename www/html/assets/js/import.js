document.addEventListener("DOMContentLoaded", () => {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('xmlFile');
    const fileInfo = document.getElementById('fileInfo');
    const previewContainer = document.getElementById('xmlPreviewContainer');
    const hiddenTextarea = document.getElementById('importData');
    const importWrap = document.getElementById('importButtonWrap');

    dropzone.addEventListener('click', () => fileInput.click());

    dropzone.addEventListener('dragover', e => {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
    });

    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file && file.name.endsWith(".xml")) {
            handleXML(file);
        } else {
            alert("Prosím vlož platný XML soubor.");
        }
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file) handleXML(file);
    });

    function handleXML(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const xmlText = e.target.result;
            try {
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(xmlText, "application/xml");

                const parsererror = xmlDoc.getElementsByTagName("parsererror");
                if (parsererror.length > 0) throw new Error("Chyba ve formátu XML");

                const cars = xmlDoc.getElementsByTagName("car");
                if (cars.length === 0) throw new Error("Nebyly nalezeny žádné záznamy <car>");

                fileInfo.style.display = "block";
                fileInfo.textContent = `Vybrán soubor: ${file.name} (${cars.length} vozidel)`;

                let table = `<table><thead><tr><th>Značka</th><th>Model</th><th>Rok</th></tr></thead><tbody>`;
                for (let car of cars) {
                    const brand = car.getElementsByTagName("brand")[0]?.textContent ?? "";
                    const model = car.getElementsByTagName("model")[0]?.textContent ?? "";
                    const year  = car.getElementsByTagName("year")[0]?.textContent ?? "";
                    table += `<tr><td>${brand}</td><td>${model}</td><td>${year}</td></tr>`;
                }
                table += `</tbody></table>`;

                previewContainer.innerHTML = table;
                hiddenTextarea.value = xmlText;
                importWrap.style.display = "block";
            } catch (err) {
                fileInfo.style.display = "block";
                fileInfo.className = "message error";
                fileInfo.textContent = `❌ ${err.message}`;
                previewContainer.innerHTML = "";
                hiddenTextarea.value = "";
                importWrap.style.display = "none";
            }
        };
        reader.readAsText(file);
    }
});
