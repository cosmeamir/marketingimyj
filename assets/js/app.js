(function () {
    const timeline = document.getElementById('timeline');
    if (!timeline) return;

    const view = timeline.dataset.view || 'month';
    const posts = JSON.parse(timeline.dataset.posts || '[]');
    const now = new Date();

    let days = 30;
    if (view === 'week') days = 7;
    if (view === 'day') days = 1;

    const grid = document.createElement('div');
    grid.className = 'timeline-grid';
    grid.style.gridTemplateColumns = `repeat(${Math.min(days, 7)}, minmax(120px, 1fr))`;

    for (let i = 0; i < days; i++) {
        const date = new Date(now.getFullYear(), now.getMonth(), i + 1);
        const key = date.toISOString().slice(0, 10);

        const cell = document.createElement('div');
        cell.className = 'timeline-day';
        cell.innerHTML = `<h6>${date.toLocaleDateString('pt-PT')}</h6>`;

        posts
            .filter((p) => p.post_date === key)
            .forEach((p) => {
                const item = document.createElement('div');
                item.className = 'timeline-item';
                item.innerHTML = `<strong>${p.post_time}</strong> ${p.titulo}<br><small>${p.plataforma}</small>`;
                item.title = p.legenda || p.titulo;
                cell.appendChild(item);
            });

        grid.appendChild(cell);
    }

    timeline.appendChild(grid);
})();
