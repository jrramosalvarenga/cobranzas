@props(['tableId', 'filename' => 'datos'])

<div
    x-data="{
        copied: false,
        getTableData() {
            const table = document.getElementById('{{ $tableId }}');
            if (!table) return { headers: [], rows: [] };

            const headers = [];
            const headerCells = table.querySelectorAll('thead th');
            headerCells.forEach(th => {
                const text = th.textContent.trim();
                if (text) headers.push(text);
            });

            const rows = [];
            table.querySelectorAll('tbody tr').forEach(tr => {
                const cells = [];
                const tds = tr.querySelectorAll('td');
                tds.forEach((td, i) => {
                    if (i < headers.length) {
                        cells.push(td.textContent.trim().replace(/\s+/g, ' '));
                    }
                });
                if (cells.length > 0 && cells.some(c => c !== '')) rows.push(cells);
            });

            return { headers, rows };
        },
        copyTable() {
            const { headers, rows } = this.getTableData();
            if (rows.length === 0) return;

            let text = headers.join('\t') + '\n';
            rows.forEach(row => { text += row.join('\t') + '\n'; });

            navigator.clipboard.writeText(text).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            });
        },
        exportExcel() {
            const { headers, rows } = this.getTableData();
            if (rows.length === 0) return;

            const data = [headers, ...rows];
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);

            const colWidths = headers.map((h, i) => {
                let max = h.length;
                rows.forEach(r => { if (r[i] && r[i].length > max) max = r[i].length; });
                return { wch: Math.min(max + 2, 40) };
            });
            ws['!cols'] = colWidths;

            XLSX.utils.book_append_sheet(wb, ws, '{{ $filename }}');
            XLSX.writeFile(wb, '{{ $filename }}.xlsx');
        },
        exportPdf() {
            const { headers, rows } = this.getTableData();
            if (rows.length === 0) return;

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'letter');

            doc.setFontSize(14);
            doc.text('{{ $filename }}', 14, 15);
            doc.setFontSize(8);
            doc.text('Generado: ' + new Date().toLocaleDateString('es-HN'), 14, 21);

            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 25,
                styles: { fontSize: 7, cellPadding: 2 },
                headStyles: { fillColor: [55, 65, 81], fontSize: 7, fontStyle: 'bold' },
                alternateRowStyles: { fillColor: [245, 245, 245] },
            });

            doc.save('{{ $filename }}.pdf');
        }
    }"
    class="flex items-center gap-1"
>
    <button
        type="button"
        x-on:click="copyTable()"
        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
        x-text="copied ? 'Copiado!' : 'Copiar'"
        :class="copied && 'text-green-600 border-green-400'"
    >Copiar</button>

    <button
        type="button"
        x-on:click="exportExcel()"
        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
        Excel
    </button>

    <button
        type="button"
        x-on:click="exportPdf()"
        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
        PDF
    </button>
</div>
