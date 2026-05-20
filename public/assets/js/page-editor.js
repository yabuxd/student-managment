// --- Page Builder Logic ---
let selectedCanvasBlock = null;
let hasUnsavedChanges = false;
let historyStack = [];
let historyIndex = -1;

function togglePropSection(sectionId, headerEl) {
    const section = document.getElementById(sectionId);
    const icon = headerEl.querySelector('.prop-toggle-icon');
    if (section.style.display === 'none') {
        section.style.display = section.dataset.display || 'block';
        icon.textContent = '▼';
    } else {
        section.dataset.display = section.style.display;
        section.style.display = 'none';
        icon.textContent = '▶';
    }
}

function openPageEditor(pageName) {
    document.getElementById('pageEditorModal').style.display = 'block';
    document.getElementById('editorPageTitle').textContent = pageName + ' /edit';

    // Reset editor UI
    selectedCanvasBlock = null;
    document.getElementById('propertiesPanel').style.display = 'none';
    document.getElementById('noPropertiesState').style.display = 'block';

    const subdomain = localStorage.getItem('school_subdomain') || 'testschool';
    const canvasContainer = document.getElementById('liveCanvas');
    canvasContainer.innerHTML = ''; // clear previous
    canvasContainer.style.padding = '0';

    const iframe = document.createElement('iframe');

    // Route to actual template files on the same origin to avoid CORS
    if (pageName === 'Dashboard') {
        iframe.src = `/dashboard.php?preview_subdomain=${subdomain}`;
    } else if (pageName === 'Registration') {
        iframe.src = `/register.php?preview_subdomain=${subdomain}`;
    } else {
        iframe.src = `/?preview_subdomain=${subdomain}`;
    }

    iframe.style.width = '100%';
    iframe.style.height = '100%';
    iframe.style.border = 'none';

    // Inject editability into the iframe only on initial load
    iframe.addEventListener('load', function () {
        historyStack = [];
        historyIndex = -1;
        hasUnsavedChanges = false;
        injectIframeEditorEvents(iframe);
        // Push initial state
        pushToHistory();
    }, { once: true });

    canvasContainer.appendChild(iframe);
}

window.addEventListener('beforeunload', function (e) {
    if (hasUnsavedChanges) {
        e.preventDefault();
        e.returnValue = '';
    }
});

function updateHistoryButtons() {
    document.getElementById('btnUndo').disabled = historyIndex <= 0;
    document.getElementById('btnRedo').disabled = historyIndex >= historyStack.length - 1;
    document.getElementById('btnReset').disabled = historyStack.length <= 1;
}

function pushToHistory() {
    const iframe = document.querySelector('#liveCanvas iframe');
    if (iframe && iframe.contentWindow) {
        const currentHtml = iframe.contentWindow.document.documentElement.outerHTML;

        // If we are pushing a new state while not at the end (e.g. after undo), truncate the future history
        if (historyIndex < historyStack.length - 1) {
            historyStack = historyStack.slice(0, historyIndex + 1);
        }

        // Avoid pushing duplicate consecutive states
        if (historyStack.length > 0 && historyStack[historyIndex] === currentHtml) {
            return;
        }

        historyStack.push(currentHtml);
        historyIndex++;
        hasUnsavedChanges = (historyIndex > 0);
        updateHistoryButtons();
    }
}

function undoChange() {
    if (historyIndex > 0) {
        historyIndex--;
        restoreFromHistory();
    }
}

function redoChange() {
    if (historyIndex < historyStack.length - 1) {
        historyIndex++;
        restoreFromHistory();
    }
}

function resetChanges() {
    if (historyStack.length > 0) {
        historyIndex = 0;
        restoreFromHistory();
    }
}

function restoreFromHistory() {
    const iframe = document.querySelector('#liveCanvas iframe');
    if (iframe && iframe.contentWindow && historyStack[historyIndex]) {
        const iframeDoc = iframe.contentWindow.document;
        iframeDoc.open();
        iframeDoc.write(historyStack[historyIndex]);
        iframeDoc.close();

        // Re-inject events because document was overwritten
        injectIframeEditorEvents(iframe);

        hasUnsavedChanges = (historyIndex > 0);
        updateHistoryButtons();

        selectedCanvasBlock = null;
        document.getElementById('propertiesPanel').style.display = 'none';
        document.getElementById('noPropertiesState').style.display = 'block';
    }
}

function injectIframeEditorEvents(iframe) {
    try {
        const iframeDoc = iframe.contentWindow.document;
        if (!iframeDoc.getElementById('editor-styles-inject')) {
            const style = iframeDoc.createElement('style');
            style.id = 'editor-styles-inject';
            style.innerHTML = `
                body * { transition: outline 0.2s; }
                body *:hover { outline: 2px dashed #10b981; cursor: pointer; }
            `;
            iframeDoc.head.appendChild(style);
        }

        iframeDoc.body.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            document.getElementById('noPropertiesState').style.display = 'none';
            document.getElementById('propertiesPanel').style.display = 'block';
            document.getElementById('propBlockName').textContent = 'Editing: ' + e.target.tagName;

            let text = '';
            let hasElementChildren = false;
            for (let i = 0; i < e.target.childNodes.length; i++) {
                if (e.target.childNodes[i].nodeType === Node.TEXT_NODE) {
                    text += e.target.childNodes[i].textContent;
                } else if (e.target.childNodes[i].nodeType === Node.ELEMENT_NODE) {
                    hasElementChildren = true;
                }
            }

            let textInput = document.getElementById('propTextContent');
            let warningMsg = document.getElementById('propTextContentWarning');
            if (!warningMsg) {
                warningMsg = document.createElement('div');
                warningMsg.id = 'propTextContentWarning';
                warningMsg.style.color = '#ef4444';
                warningMsg.style.fontSize = '0.75rem';
                warningMsg.style.marginTop = '0.25rem';
                textInput.parentNode.appendChild(warningMsg);
            }

            textInput.value = text.trim();

            if (hasElementChildren && text.trim() === '') {
                textInput.disabled = true;
                textInput.placeholder = 'Select specific text to edit.';
                warningMsg.textContent = 'Contains other elements without direct text. Please select a child element directly to edit its text.';
            } else {
                textInput.disabled = false;
                textInput.placeholder = '';
                warningMsg.textContent = '';
            }

            const computedStyle = window.getComputedStyle(e.target);
            document.getElementById('propBgColor').value = rgbToHex(computedStyle.backgroundColor);
            document.getElementById('propTextColor').value = rgbToHex(computedStyle.color);

            let fontFamily = e.target.style.fontFamily || 'inherit';
            document.getElementById('propFontFamily').value = fontFamily.replace(/['"]/g, '');

            let bgImage = e.target.style.backgroundImage;
            if (bgImage && bgImage !== 'none') {
                document.getElementById('propBgImage').value = bgImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
            } else {
                document.getElementById('propBgImage').value = '';
            }

            document.getElementById('propPadding').value = e.target.style.padding || '';
            document.getElementById('propMargin').value = e.target.style.margin || '';
            document.getElementById('propTextAlign').value = e.target.style.textAlign || '';

            selectedCanvasBlock = e.target;
        });
    } catch (e) {
        console.log('Cross-origin blocked or load error.', e);
    }
}

function closePageEditor() {
    if (hasUnsavedChanges) {
        if (!confirm("You have unsaved changes. Are you sure you want to exit? Your changes will be lost.")) {
            return;
        }
    }
    document.getElementById('pageEditorModal').style.display = 'none';
}

async function savePageEdits() {
    const iframe = document.querySelector('#liveCanvas iframe');
    if (!iframe) return;

    try {
        const iframeDoc = iframe.contentWindow.document;

        // Clean up the injected editor styles before saving
        const editorStyles = iframeDoc.querySelectorAll('style');
        editorStyles.forEach(s => {
            if (s.innerHTML.includes('outline: 2px dashed #10b981')) {
                s.remove();
            }
        });

        const modifiedHtml = iframeDoc.documentElement.outerHTML;
        const subdomain = localStorage.getItem('school_subdomain');

        let path = '/';
        const src = iframe.src;
        if (src.includes('dashboard.php')) path = '/dashboard.php';
        if (src.includes('register.php')) path = '/register.php';
        if (src.includes('login.php')) path = '/login.php';

        const saveBtn = document.querySelector('#pageEditorModal button.btn-primary');
        const originalText = saveBtn.textContent;
        saveBtn.textContent = 'Saving...';
        saveBtn.disabled = true;

        const response = await fetch(`${API_BASE}/schools/save-page`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                subdomain: subdomain,
                path: path,
                html: modifiedHtml
            })
        });

        const data = await response.json();
        if (data.success) {
            hasUnsavedChanges = false;
            alert('Page layout saved successfully! The live site has been updated.');
        } else {
            alert('Error saving page: ' + data.message);
        }

        saveBtn.textContent = originalText;
        saveBtn.disabled = false;

        // Re-inject the editor styles so they can continue editing
        const style = iframeDoc.createElement('style');
        style.innerHTML = `
            body * { transition: outline 0.2s; }
            body *:hover { outline: 2px dashed #10b981; cursor: pointer; }
        `;
        iframeDoc.head.appendChild(style);

    } catch (e) {
        console.error("Error saving layout:", e);
        alert("Failed to save layout. Ensure you are on the same origin.");
    }
}

function addComponent(type) {
    alert('Adding new components dynamically requires the backend JSON schema. For now, try clicking the text in the actual template preview to edit it live!');
}

function selectBlock(blockElement, type) {
    // Deprecated by iframe logic
}

function rgbToHex(rgb) {
    if (!rgb || rgb === 'rgba(0, 0, 0, 0)' || rgb === 'transparent') return '#ffffff';
    let match = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)/);
    if (!match) return '#ffffff';
    return "#" + (1 << 24 | match[1] << 16 | match[2] << 8 | match[3]).toString(16).slice(1);
}

function updateSelectedBlock() {
    if (!selectedCanvasBlock) return;

    const textInput = document.getElementById('propTextContent');
    if (!textInput.disabled) {
        const newText = textInput.value;
        let updated = false;
        for (let i = 0; i < selectedCanvasBlock.childNodes.length; i++) {
            if (selectedCanvasBlock.childNodes[i].nodeType === Node.TEXT_NODE && selectedCanvasBlock.childNodes[i].textContent.trim().length > 0) {
                selectedCanvasBlock.childNodes[i].textContent = newText;
                updated = true;
                break;
            }
        }
        if (!updated) {
            let hasElementChildren = Array.from(selectedCanvasBlock.childNodes).some(n => n.nodeType === Node.ELEMENT_NODE);
            if (!hasElementChildren) {
                selectedCanvasBlock.textContent = newText;
            } else if (newText.trim() !== '') {
                selectedCanvasBlock.appendChild(document.createTextNode(newText));
            }
        }
    }

    const bgColor = document.getElementById('propBgColor').value;
    const textColor = document.getElementById('propTextColor').value;
    const fontFamily = document.getElementById('propFontFamily').value;
    const bgImage = document.getElementById('propBgImage').value;
    const padding = document.getElementById('propPadding').value;
    const margin = document.getElementById('propMargin').value;
    const textAlign = document.getElementById('propTextAlign').value;

    if (bgColor !== '#ffffff') selectedCanvasBlock.style.backgroundColor = bgColor;
    if (textColor !== '#000000') selectedCanvasBlock.style.color = textColor;
    if (fontFamily !== 'inherit') selectedCanvasBlock.style.fontFamily = fontFamily;

    if (bgImage && bgImage.trim() !== '') {
        selectedCanvasBlock.style.backgroundImage = `url('${bgImage}')`;
        selectedCanvasBlock.style.backgroundSize = 'cover';
        selectedCanvasBlock.style.backgroundPosition = 'center';
    } else {
        selectedCanvasBlock.style.backgroundImage = '';
    }

    selectedCanvasBlock.style.padding = padding;
    selectedCanvasBlock.style.margin = margin;
    selectedCanvasBlock.style.textAlign = textAlign;
}

function removeSelectedBlock() {
    if (selectedCanvasBlock) {
        selectedCanvasBlock.remove();
        selectedCanvasBlock = null;
        document.getElementById('propertiesPanel').style.display = 'none';
        document.getElementById('noPropertiesState').style.display = 'block';
        pushToHistory();
    }
}

function clearBlockOverlays() {
    document.querySelectorAll('.block-overlay').forEach(el => el.style.display = 'none');
}
