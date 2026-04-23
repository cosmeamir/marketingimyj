(function () {
    const timeline = document.getElementById('timeline');
    if (!timeline) return;

    const view = timeline.dataset.view || 'month';
    const posts = JSON.parse(timeline.dataset.posts || '[]');
    const now = new Date();

    const days = view === 'week' ? 7 : (view === 'day' ? 1 : 30);

    let columns = 7;
    const width = window.innerWidth;
    if (view === 'day') columns = 1;
    else if (view === 'week') columns = width < 768 ? 1 : (width < 992 ? 2 : 7);
    else columns = width < 576 ? 1 : (width < 768 ? 2 : (width < 992 ? 4 : 7));

    const grid = document.createElement('div');
    grid.className = 'timeline-grid';
    grid.style.gridTemplateColumns = `repeat(${columns}, minmax(0, 1fr))`;

    for (let i = 0; i < days; i++) {
        const date = new Date(now.getFullYear(), now.getMonth(), i + 1);
        const key = date.toISOString().slice(0, 10);

        const cell = document.createElement('div');
        cell.className = 'timeline-day';
        cell.innerHTML = `<h6>${date.toLocaleDateString('pt-PT')}</h6>`;

        posts.filter((p) => p.post_date === key).forEach((p) => {
            const item = document.createElement('div');
            item.className = 'timeline-item';
            item.innerHTML = `<strong>${String(p.post_time).slice(0, 5)}</strong> ${p.titulo}<br><small>${p.plataforma}</small>`;
            item.title = p.legenda || p.titulo;
            cell.appendChild(item);
        });

        grid.appendChild(cell);
    }

    timeline.innerHTML = '';
    timeline.appendChild(grid);
})();
