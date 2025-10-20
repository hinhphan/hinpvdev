<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Công cụ thiết kế PDF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .paper-canvas {
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
            background: white;
            position: relative;
            margin: 20px auto;
            overflow: hidden;
        }
        
        .table-cell {
            position: relative;
            min-height: 40px;
            box-sizing: border-box;
        }
        
        .table-cell:hover {
            background: #f0f9ff;
        }
        
        .table-cell.selected {
            background: #dbeafe;
            outline: 2px solid #3b82f6;
            outline-offset: -2px;
        }
        
        .resize-handle {
            position: absolute;
            background: rgba(59, 130, 246, 0.3);
            z-index: 100;
            transition: background 0.2s;
        }
        
        .resize-handle-row {
            height: 8px;
            width: 100%;
            bottom: -4px;
            left: 0;
            cursor: row-resize;
        }
        
        .resize-handle-col {
            width: 8px;
            height: 100%;
            right: -4px;
            top: 0;
            cursor: col-resize;
        }
        
        .resize-handle:hover {
            background: rgba(59, 130, 246, 0.8);
        }
        
        .drop-zone {
            border: 2px dashed #94a3b8;
            background: #f8fafc;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
        }
        
        .drop-zone.drag-over {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
            height: 100%;
        }
        
        td, .table-cell {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: top;
        }
        
        .context-menu {
            position: absolute;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
        }
        
        .context-menu.show {
            display: block;
        }
        
        .context-menu-item {
            padding: 8px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .context-menu-item:hover {
            background: #f3f4f6;
        }
        
        .context-menu-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Công cụ thiết kế PDF</h1>
            
            <!-- Controls -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Paper Size -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kích thước giấy</label>
                    <select id="paperSize" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="a4">A4 (210 × 297 mm)</option>
                        <option value="a3">A3 (297 × 420 mm)</option>
                        <option value="b4">B4 (250 × 353 mm)</option>
                        <option value="b3">B3 (353 × 500 mm)</option>
                        <option value="letter">Letter (216 × 279 mm)</option>
                        <option value="legal">Legal (216 × 356 mm)</option>
                    </select>
                </div>
                
                <!-- Orientation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hướng giấy</label>
                    <select id="orientation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="portrait">Dọc (Portrait)</option>
                        <option value="landscape">Ngang (Landscape)</option>
                    </select>
                </div>
                
                <!-- Table Rows -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số hàng</label>
                    <input type="number" id="tableRows" value="3" min="1" max="20" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Table Columns -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số cột</label>
                    <input type="number" id="tableCols" value="3" min="1" max="20" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-4 flex flex-wrap gap-2">
                <button id="addTableBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    + Thêm bảng
                </button>
                <button id="clearBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Xóa tất cả
                </button>
                <button id="exportBtn" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Xuất HTML
                </button>
            </div>
        </div>
        
        <!-- Canvas Area -->
        <div class="bg-gray-200 rounded-lg p-8 overflow-auto">
            <div id="paperCanvas" class="paper-canvas">
                <div class="drop-zone" id="dropZone">
                    <p class="text-center">
                        <span class="block text-lg font-semibold mb-2">Kéo và thả để tạo bảng</span>
                        <span class="block text-sm">hoặc nhấn nút "Thêm bảng" ở trên</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Context Menu -->
    <div id="contextMenu" class="context-menu">
        <div class="context-menu-item" data-action="merge-right">Gộp ô sang phải</div>
        <div class="context-menu-item" data-action="merge-down">Gộp ô xuống dưới</div>
        <div class="context-menu-item" data-action="split">Tách ô</div>
        <div class="context-menu-item" data-action="delete-row">Xóa hàng</div>
        <div class="context-menu-item" data-action="delete-col">Xóa cột</div>
        <div class="context-menu-item" data-action="add-row-above">Thêm hàng ở trên</div>
        <div class="context-menu-item" data-action="add-row-below">Thêm hàng ở dưới</div>
        <div class="context-menu-item" data-action="add-col-left">Thêm cột bên trái</div>
        <div class="context-menu-item" data-action="add-col-right">Thêm cột bên phải</div>
    </div>

    <script>
        // Paper sizes in mm (converted to px at 96 DPI: 1mm = 3.7795px)
        const PAPER_SIZES = {
            a4: { width: 210, height: 297 },
            a3: { width: 297, height: 420 },
            b4: { width: 250, height: 353 },
            b3: { width: 353, height: 500 },
            letter: { width: 216, height: 279 },
            legal: { width: 216, height: 356 }
        };
        
        const MM_TO_PX = 3.7795;
        
        let currentTable = null;
        let selectedCell = null;
        let draggedTable = null;
        let resizing = null;
        
        // Get DOM elements
        const paperCanvas = document.getElementById('paperCanvas');
        let dropZone = document.getElementById('dropZone');
        const paperSizeSelect = document.getElementById('paperSize');
        const orientationSelect = document.getElementById('orientation');
        const tableRowsInput = document.getElementById('tableRows');
        const tableColsInput = document.getElementById('tableCols');
        const addTableBtn = document.getElementById('addTableBtn');
        const clearBtn = document.getElementById('clearBtn');
        const exportBtn = document.getElementById('exportBtn');
        const contextMenu = document.getElementById('contextMenu');
        
        // Initialize
        function init() {
            updatePaperSize();
            setupEventListeners();
        }
        
        // Update paper size
        function updatePaperSize() {
            const size = paperSizeSelect.value;
            const orientation = orientationSelect.value;
            const dimensions = PAPER_SIZES[size];
            
            let width, height;
            if (orientation === 'portrait') {
                width = dimensions.width * MM_TO_PX;
                height = dimensions.height * MM_TO_PX;
            } else {
                width = dimensions.height * MM_TO_PX;
                height = dimensions.width * MM_TO_PX;
            }
            
            paperCanvas.style.width = width + 'px';
            paperCanvas.style.height = height + 'px';
        }
        
        // Setup event listeners
        function setupEventListeners() {
            paperSizeSelect.addEventListener('change', updatePaperSize);
            orientationSelect.addEventListener('change', updatePaperSize);
            addTableBtn.addEventListener('click', addTable);
            clearBtn.addEventListener('click', clearCanvas);
            exportBtn.addEventListener('click', exportHTML);
            
            // Drop zone events
            setupDropZoneEvents();
            
            // Context menu
            document.addEventListener('click', () => {
                contextMenu.classList.remove('show');
            });
            
            contextMenu.addEventListener('click', handleContextMenuAction);
            
            // Click outside to deselect
            document.addEventListener('click', (e) => {
                if (!e.target.closest('td')) {
                    if (selectedCell) {
                        selectedCell.classList.remove('selected');
                        selectedCell = null;
                    }
                }
            });
        }
        
        // Setup drop zone events
        function setupDropZoneEvents() {
            if (dropZone) {
                dropZone.addEventListener('dragover', handleDragOver);
                dropZone.addEventListener('drop', handleDrop);
                dropZone.addEventListener('dragleave', handleDragLeave);
            }
        }
        
        // Add table
        function addTable() {
            const rows = parseInt(tableRowsInput.value);
            const cols = parseInt(tableColsInput.value);
            
            if (rows < 1 || cols < 1) {
                alert('Số hàng và số cột phải lớn hơn 0');
                return;
            }
            
            const table = createTable(rows, cols);
            
            // Remove drop zone if exists
            if (dropZone) {
                dropZone.remove();
            }
            
            paperCanvas.appendChild(table);
        }
        
        // Setup cell events
        function setupCellEvents(cell) {
            // Remove old events if any
            const newCell = cell.cloneNode(true);
            if (cell.parentNode) {
                cell.parentNode.replaceChild(newCell, cell);
            }
            
            newCell.addEventListener('click', (e) => {
                e.stopPropagation();
                selectCell(newCell);
            });
            
            newCell.addEventListener('contextmenu', (e) => {
                e.preventDefault();
                selectCell(newCell);
                showContextMenu(e.pageX, e.pageY);
            });
            
            return newCell;
        }
        
        // Add resize handles to cell
        function addResizeHandles(cell) {
            // Remove existing handles first
            cell.querySelectorAll('.resize-handle').forEach(h => h.remove());
            
            const table = cell.closest('table');
            if (!table) return;
            
            const row = cell.parentElement;
            const rowIndex = Array.from(table.rows).indexOf(row);
            const cellIndex = Array.from(row.cells).indexOf(cell);
            
            // Add column resize handle for all cells
            const colHandle = document.createElement('div');
            colHandle.className = 'resize-handle resize-handle-col';
            colHandle.addEventListener('mousedown', (e) => startResize(e, 'col', cell));
            cell.appendChild(colHandle);
            
            // Add row resize handle for all rows (including the last row)
            const rowHandle = document.createElement('div');
            rowHandle.className = 'resize-handle resize-handle-row';
            rowHandle.addEventListener('mousedown', (e) => startResize(e, 'row', cell));
            cell.appendChild(rowHandle);
        }
        
        // Update all resize handles in table
        function updateAllResizeHandles(table) {
            const rows = table.rows;
            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].cells;
                for (let j = 0; j < cells.length; j++) {
                    addResizeHandles(cells[j]);
                }
            }
        }
        
        // Create new cell with events
        function createNewCell(content = 'Ô mới', width = null) {
            const td = document.createElement('td');
            td.contentEditable = true;
            td.className = 'table-cell';
            // Ensure consistent border style
            td.style.border = '1px solid #cbd5e1';
            td.style.padding = '8px';
            td.style.verticalAlign = 'top';
            
            // Set explicit width if provided
            if (width) {
                td.style.width = width + 'px';
            }
            
            td.innerHTML = `<div class="min-h-[40px]">${content}</div>`;
            
            td.addEventListener('click', (e) => {
                e.stopPropagation();
                selectCell(td);
            });
            
            td.addEventListener('contextmenu', (e) => {
                e.preventDefault();
                selectCell(td);
                showContextMenu(e.pageX, e.pageY);
            });
            
            return td;
        }
        
        // Create table
        function createTable(rows, cols) {
            const tableWrapper = document.createElement('div');
            tableWrapper.className = 'relative m-4';
            tableWrapper.draggable = true;
            
            const table = document.createElement('table');
            table.className = 'border-collapse w-full';
            
            for (let i = 0; i < rows; i++) {
                const tr = document.createElement('tr');
                for (let j = 0; j < cols; j++) {
                    const td = createNewCell(`Ô ${i + 1}-${j + 1}`);
                    td.dataset.row = i;
                    td.dataset.col = j;
                    
                    // Add column resize handle for all columns
                    const colHandle = document.createElement('div');
                    colHandle.className = 'resize-handle resize-handle-col';
                    colHandle.addEventListener('mousedown', (e) => startResize(e, 'col', td));
                    td.appendChild(colHandle);
                    
                    // Add row resize handle for all rows (including the last row)
                    const rowHandle = document.createElement('div');
                    rowHandle.className = 'resize-handle resize-handle-row';
                    rowHandle.addEventListener('mousedown', (e) => startResize(e, 'row', td));
                    td.appendChild(rowHandle);
                    
                    tr.appendChild(td);
                }
                table.appendChild(tr);
            }
            
            tableWrapper.appendChild(table);
            
            // Dragging events
            tableWrapper.addEventListener('dragstart', (e) => {
                draggedTable = tableWrapper;
                e.dataTransfer.effectAllowed = 'move';
            });
            
            tableWrapper.addEventListener('dragend', () => {
                draggedTable = null;
            });
            
            return tableWrapper;
        }
        
        // Select cell
        function selectCell(cell) {
            if (selectedCell) {
                selectedCell.classList.remove('selected');
            }
            selectedCell = cell;
            cell.classList.add('selected');
        }
        
        // Show context menu
        function showContextMenu(x, y) {
            contextMenu.style.left = x + 'px';
            contextMenu.style.top = y + 'px';
            contextMenu.classList.add('show');
        }
        
        // Handle context menu actions
        function handleContextMenuAction(e) {
            e.stopPropagation();
            const action = e.target.dataset.action;
            if (!action || !selectedCell) return;
            
            const table = selectedCell.closest('table');
            const row = selectedCell.parentElement;
            const rowIndex = Array.from(table.rows).indexOf(row);
            const colIndex = Array.from(row.cells).indexOf(selectedCell);
            
            switch (action) {
                case 'merge-right':
                    mergeRight(selectedCell);
                    break;
                case 'merge-down':
                    mergeDown(selectedCell);
                    break;
                case 'split':
                    splitCell(selectedCell);
                    break;
                case 'delete-row':
                    deleteRow(table, rowIndex);
                    break;
                case 'delete-col':
                    deleteColumn(table, colIndex);
                    break;
                case 'add-row-above':
                    addRowAbove(table, rowIndex);
                    break;
                case 'add-row-below':
                    addRowBelow(table, rowIndex);
                    break;
                case 'add-col-left':
                    addColumnLeft(table, colIndex);
                    break;
                case 'add-col-right':
                    addColumnRight(table, colIndex);
                    break;
            }
            
            contextMenu.classList.remove('show');
        }
        
        // Merge cells
        function mergeRight(cell) {
            const row = cell.parentElement;
            const nextCell = cell.nextElementSibling;
            if (!nextCell) {
                alert('Không có ô bên phải để gộp');
                return;
            }
            
            // Merge content
            const currentContent = cell.textContent.trim();
            const nextContent = nextCell.textContent.trim();
            if (currentContent && nextContent && currentContent !== nextContent) {
                cell.innerHTML = `<div class="min-h-[40px]">${currentContent} ${nextContent}</div>`;
            }
            
            const currentColspan = parseInt(cell.getAttribute('colspan') || 1);
            const nextColspan = parseInt(nextCell.getAttribute('colspan') || 1);
            cell.setAttribute('colspan', currentColspan + nextColspan);
            nextCell.remove();
            
            // Update resize handles after merge
            const table = cell.closest('table');
            updateAllResizeHandles(table);
        }
        
        function mergeDown(cell) {
            const table = cell.closest('table');
            const row = cell.parentElement;
            const rowIndex = Array.from(table.rows).indexOf(row);
            const cellIndex = Array.from(row.cells).indexOf(cell);
            
            const nextRow = table.rows[rowIndex + 1];
            if (!nextRow) {
                alert('Không có hàng bên dưới để gộp');
                return;
            }
            
            const nextCell = nextRow.cells[cellIndex];
            if (!nextCell) {
                alert('Không tìm thấy ô tương ứng ở hàng dưới');
                return;
            }
            
            // Merge content
            const currentContent = cell.textContent.trim();
            const nextContent = nextCell.textContent.trim();
            if (currentContent && nextContent && currentContent !== nextContent) {
                cell.innerHTML = `<div class="min-h-[40px]">${currentContent}<br>${nextContent}</div>`;
            }
            
            const currentRowspan = parseInt(cell.getAttribute('rowspan') || 1);
            const nextRowspan = parseInt(nextCell.getAttribute('rowspan') || 1);
            cell.setAttribute('rowspan', currentRowspan + nextRowspan);
            nextCell.remove();
            
            // Update resize handles after merge
            updateAllResizeHandles(table);
        }
        
        function splitCell(cell) {
            const colspan = parseInt(cell.getAttribute('colspan') || 1);
            const rowspan = parseInt(cell.getAttribute('rowspan') || 1);
            
            if (colspan === 1 && rowspan === 1) {
                alert('Ô này không thể tách');
                return;
            }
            
            if (colspan > 1) {
                cell.setAttribute('colspan', 1);
                const row = cell.parentElement;
                for (let i = 1; i < colspan; i++) {
                    const newCell = createNewCell();
                    row.insertBefore(newCell, cell.nextSibling);
                }
            }
            
            if (rowspan > 1) {
                cell.setAttribute('rowspan', 1);
                // Add cells to rows below
                const table = cell.closest('table');
                const row = cell.parentElement;
                const rowIndex = Array.from(table.rows).indexOf(row);
                const cellIndex = Array.from(row.cells).indexOf(cell);
                
                for (let i = 1; i < rowspan; i++) {
                    const targetRow = table.rows[rowIndex + i];
                    const newCell = createNewCell();
                    targetRow.insertBefore(newCell, targetRow.cells[cellIndex]);
                }
            }
            
            // Update resize handles after split
            const table = cell.closest('table');
            updateAllResizeHandles(table);
        }
        
        // Get total column count of a row (including colspan)
        function getTotalColumnCount(row) {
            let total = 0;
            for (let i = 0; i < row.cells.length; i++) {
                const cell = row.cells[i];
                total += parseInt(cell.getAttribute('colspan') || 1);
            }
            return total;
        }
        
        // Get maximum column count in table
        function getMaxColumnCount(table) {
            let maxCols = 0;
            for (let i = 0; i < table.rows.length; i++) {
                const colCount = getTotalColumnCount(table.rows[i]);
                if (colCount > maxCols) {
                    maxCols = colCount;
                }
            }
            return maxCols;
        }
        
        // Delete row
        function deleteRow(table, rowIndex) {
            if (table.rows.length <= 1) {
                alert('Không thể xóa hàng cuối cùng');
                return;
            }
            table.deleteRow(rowIndex);
            updateAllResizeHandles(table);
        }
        
        // Delete column
        function deleteColumn(table, colIndex) {
            for (let i = 0; i < table.rows.length; i++) {
                const row = table.rows[i];
                if (row.cells.length <= 1) {
                    alert('Không thể xóa cột cuối cùng');
                    return;
                }
                if (row.cells[colIndex]) {
                    row.deleteCell(colIndex);
                }
            }
            updateAllResizeHandles(table);
        }
        
        // Add row above
        function addRowAbove(table, rowIndex) {
            const newRow = table.insertRow(rowIndex);
            const colCount = getMaxColumnCount(table);
            
            // Create cells matching the column count, calculating width properly
            const referenceRow = table.rows[rowIndex + 1] || table.rows[0];
            
            // Build an array of column widths from all rows
            const columnWidths = calculateColumnWidths(table);
            
            for (let i = 0; i < colCount; i++) {
                const cellWidth = columnWidths[i] || null;
                const newCell = createNewCell('Ô mới', cellWidth);
                newRow.appendChild(newCell);
            }
            updateAllResizeHandles(table);
        }
        
        // Add row below
        function addRowBelow(table, rowIndex) {
            const newRow = table.insertRow(rowIndex + 1);
            const colCount = getMaxColumnCount(table);
            
            // Calculate column widths from the entire table
            const columnWidths = calculateColumnWidths(table);
            
            for (let i = 0; i < colCount; i++) {
                const cellWidth = columnWidths[i] || null;
                const newCell = createNewCell('Ô mới', cellWidth);
                newRow.appendChild(newCell);
            }
            updateAllResizeHandles(table);
        }
        
        // Calculate column widths from table
        function calculateColumnWidths(table) {
            const maxCols = getMaxColumnCount(table);
            const widths = new Array(maxCols).fill(null);
            
            // Try to find a row without merged cells to get accurate widths
            for (let i = 0; i < table.rows.length; i++) {
                const row = table.rows[i];
                let allSingleCols = true;
                
                // Check if this row has all single columns (no colspan)
                for (let j = 0; j < row.cells.length; j++) {
                    const cell = row.cells[j];
                    const colspan = parseInt(cell.getAttribute('colspan') || 1);
                    if (colspan > 1) {
                        allSingleCols = false;
                        break;
                    }
                }
                
                // If found a row with all single columns, use it
                if (allSingleCols && row.cells.length === maxCols) {
                    for (let j = 0; j < row.cells.length; j++) {
                        // Use the explicit width if set, otherwise use offsetWidth
                        const cell = row.cells[j];
                        widths[j] = cell.style.width ? parseFloat(cell.style.width) : cell.offsetWidth;
                    }
                    return widths;
                }
            }
            
            // If no perfect row found, calculate from cells with explicit widths
            // by mapping each cell to its column positions
            for (let i = 0; i < table.rows.length; i++) {
                const row = table.rows[i];
                let colPos = 0;
                
                for (let j = 0; j < row.cells.length; j++) {
                    const cell = row.cells[j];
                    const colspan = parseInt(cell.getAttribute('colspan') || 1);
                    const cellWidth = cell.style.width ? parseFloat(cell.style.width) : cell.offsetWidth;
                    
                    if (colspan === 1) {
                        // Single column cell - directly assign width
                        if (widths[colPos] === null) {
                            widths[colPos] = cellWidth;
                        }
                    } else {
                        // Merged cell - distribute width equally across columns it spans
                        const widthPerCol = cellWidth / colspan;
                        for (let k = 0; k < colspan; k++) {
                            if (widths[colPos + k] === null) {
                                widths[colPos + k] = widthPerCol;
                            }
                        }
                    }
                    
                    colPos += colspan;
                }
            }
            
            // Fill any remaining null widths with average
            const totalWidth = table.offsetWidth;
            const avgWidth = totalWidth / maxCols;
            for (let i = 0; i < maxCols; i++) {
                if (widths[i] === null) {
                    widths[i] = avgWidth;
                }
            }
            
            return widths;
        }
        
        // Add column left
        function addColumnLeft(table, colIndex) {
            for (let i = 0; i < table.rows.length; i++) {
                const row = table.rows[i];
                
                // Get width from the cell we're inserting before
                let cellWidth = null;
                if (row.cells[colIndex]) {
                    cellWidth = row.cells[colIndex].offsetWidth;
                }
                
                const newCell = createNewCell('Ô mới', cellWidth);
                row.insertBefore(newCell, row.cells[colIndex]);
            }
            updateAllResizeHandles(table);
        }
        
        // Add column right
        function addColumnRight(table, colIndex) {
            for (let i = 0; i < table.rows.length; i++) {
                const row = table.rows[i];
                
                // Get width from the cell at colIndex
                let cellWidth = null;
                if (row.cells[colIndex]) {
                    cellWidth = row.cells[colIndex].offsetWidth;
                }
                
                const newCell = createNewCell('Ô mới', cellWidth);
                if (colIndex + 1 < row.cells.length) {
                    row.insertBefore(newCell, row.cells[colIndex + 1]);
                } else {
                    row.appendChild(newCell);
                }
            }
            updateAllResizeHandles(table);
        }
        
        // Get column position accounting for colspan
        function getColumnPosition(table, targetRow, targetCell) {
            const rowIndex = Array.from(table.rows).indexOf(targetRow);
            const cellIndex = Array.from(targetRow.cells).indexOf(targetCell);
            
            let colPos = 0;
            for (let i = 0; i < cellIndex; i++) {
                const cell = targetRow.cells[i];
                colPos += parseInt(cell.getAttribute('colspan') || 1);
            }
            return colPos;
        }
        
        // Get all cells at a specific column position
        function getCellsAtColumnPosition(table, colPos) {
            const cells = [];
            for (let i = 0; i < table.rows.length; i++) {
                const row = table.rows[i];
                let currentPos = 0;
                
                for (let j = 0; j < row.cells.length; j++) {
                    const cell = row.cells[j];
                    const colspan = parseInt(cell.getAttribute('colspan') || 1);
                    
                    // Check if this cell occupies the target column position
                    if (currentPos === colPos) {
                        cells.push(cell);
                        break;
                    } else if (currentPos < colPos && currentPos + colspan > colPos) {
                        // This cell spans across the target position
                        cells.push(cell);
                        break;
                    }
                    
                    currentPos += colspan;
                }
            }
            return cells;
        }
        
        // Get next column position cell
        function getNextColumnCell(table, row, cell) {
            const colPos = getColumnPosition(table, row, cell);
            const colspan = parseInt(cell.getAttribute('colspan') || 1);
            const nextColPos = colPos + colspan;
            
            // Find cell at next column position in the same row
            let currentPos = 0;
            for (let i = 0; i < row.cells.length; i++) {
                const c = row.cells[i];
                if (currentPos === nextColPos) {
                    return c;
                }
                currentPos += parseInt(c.getAttribute('colspan') || 1);
            }
            return null;
        }
        
        // Start resize
        function startResize(e, type, cell) {
            e.preventDefault();
            e.stopPropagation();
            
            const row = cell.parentElement;
            
            resizing = {
                type: type,
                cell: cell,
                row: row,
                startX: e.pageX,
                startY: e.pageY,
                startWidth: cell.offsetWidth,
                startHeight: cell.offsetHeight
            };
            
            if (type === 'col') {
                // Simply get the next sibling cell (immediate neighbor)
                resizing.nextCell = cell.nextElementSibling;
                
                if (resizing.nextCell) {
                    resizing.nextCellStartWidth = resizing.nextCell.offsetWidth;
                }
            }
            
            document.addEventListener('mousemove', handleResize);
            document.addEventListener('mouseup', stopResize);
        }
        
        function handleResize(e) {
            if (!resizing) return;
            
            if (resizing.type === 'col') {
                const diff = e.pageX - resizing.startX;
                
                if (!resizing.nextCell) {
                    // Last column - resize only this cell
                    let newWidth = resizing.startWidth + diff;
                    resizing.cell.style.width = newWidth + 'px';
                } else {
                    // Middle column - resize only these two adjacent cells
                    let newCurrentWidth = resizing.startWidth + diff;
                    let newNextWidth = resizing.nextCellStartWidth - diff;
                    
                    // Apply widths to only these two specific cells
                    resizing.cell.style.width = newCurrentWidth + 'px';
                    resizing.nextCell.style.width = newNextWidth + 'px';
                }
                
            } else if (resizing.type === 'row') {
                const diff = e.pageY - resizing.startY;
                let newHeight = resizing.startHeight + diff;
                
                // Apply height to all cells in the same row
                Array.from(resizing.row.cells).forEach(cell => {
                    cell.style.height = newHeight + 'px';
                });
            }
        }
        
        function stopResize() {
            resizing = null;
            document.removeEventListener('mousemove', handleResize);
            document.removeEventListener('mouseup', stopResize);
        }
        
        // Drag and drop handlers
        function handleDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            dropZone.classList.add('drag-over');
        }
        
        function handleDrop(e) {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            addTable();
        }
        
        function handleDragLeave() {
            dropZone.classList.remove('drag-over');
        }
        
        // Clear canvas
        function clearCanvas() {
            if (confirm('Bạn có chắc muốn xóa tất cả nội dung?')) {
                paperCanvas.innerHTML = `
                    <div class="drop-zone" id="dropZone">
                        <p class="text-center">
                            <span class="block text-lg font-semibold mb-2">Kéo và thả để tạo bảng</span>
                            <span class="block text-sm">hoặc nhấn nút "Thêm bảng" ở trên</span>
                        </p>
                    </div>
                `;
                // Re-assign drop zone reference and attach events
                dropZone = document.getElementById('dropZone');
                setupDropZoneEvents();
            }
        }
        
        // Export HTML
        function exportHTML() {
            const clone = paperCanvas.cloneNode(true);
            
            // Remove resize handles and contenteditable
            clone.querySelectorAll('.resize-handle').forEach(el => el.remove());
            clone.querySelectorAll('[contenteditable]').forEach(el => {
                el.removeAttribute('contenteditable');
            });
            
            // Remove drop zone
            const dropZone = clone.querySelector('.drop-zone');
            if (dropZone) dropZone.remove();
            
            const html = clone.innerHTML;
            
            // Create a new window to show the HTML
            const newWindow = window.open('', '_blank');
            newWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Exported PDF Design</title>
                    <style>
                        body { margin: 0; padding: 20px; background: #f0f0f0; }
                        table { border-collapse: collapse; width: 100%; }
                        td { border: 1px solid #cbd5e1; padding: 8px; }
                        .paper-canvas {
                            box-shadow: 0 0 20px rgba(0,0,0,0.15);
                            background: white;
                            margin: 20px auto;
                            width: ${paperCanvas.style.width};
                            height: ${paperCanvas.style.height};
                        }
                    </style>
                </head>
                <body>
                    <div class="paper-canvas">
                        ${html}
                    </div>
                    <div style="text-align: center; margin-top: 20px;">
                        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            In PDF
                        </button>
                    </div>
                </body>
                </html>
            `);
            newWindow.document.close();
        }
        
        // Initialize on load
        init();
    </script>
</body>
</html>
