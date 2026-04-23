(function () {
    const timeline = document.getElementById('timeline');
    if (!timeline) return;

    const view = timeline.dataset.view || 'month';
    const posts = JSON.parse(timeline.dataset.posts || '[]');
    const ym = timeline.dataset.ym || new Date().toISOString().slice(0, 7);
    const [year, month] = ym.split('-').map(Number);
    const baseDate = new Date(year, (month || 1) - 1, 1);

    const postById = Object.fromEntries(posts.map((p) => [String(p.id), p]));

    const monthDays = new Date(baseDate.getFullYear(), baseDate.getMonth() + 1, 0).getDate();
    const days = view === 'week' ? 7 : (view === 'day' ? 1 : monthDays);

    let columns = 7;
    const width = window.innerWidth;
    if (view === 'day') columns = 1;
    else if (view === 'week') columns = width < 768 ? 1 : (width < 992 ? 2 : 7);
    else columns = width < 576 ? 1 : (width < 768 ? 2 : (width < 992 ? 4 : 7));

    const grid = document.createElement('div');
    grid.className = 'timeline-grid';
    grid.style.gridTemplateColumns = `repeat(${columns}, minmax(0, 1fr))`;

    const startDay = view === 'week' ? 1 : (view === 'day' ? new Date().getDate() : 1);

    for (let i = 0; i < days; i++) {
        const date = new Date(baseDate.getFullYear(), baseDate.getMonth(), startDay + i);
        const key = date.toISOString().slice(0, 10);

        const cell = document.createElement('div');
        cell.className = 'timeline-day';
        cell.innerHTML = `<h6>${date.toLocaleDateString('pt-PT')}</h6>`;

        posts.filter((p) => p.post_date === key).forEach((p) => {
            const item = document.createElement('div');
            item.className = 'timeline-item';

            const thumb = p.creative_url
                ? `<img src="${p.creative_url}" class="timeline-thumb" alt="${p.titulo}" data-full="${p.creative_url}" data-post-id="${p.id}">`
                : '';

            item.innerHTML = `${thumb}<div><strong>${String(p.post_time).slice(0, 5)}</strong> ${p.titulo}<br><small>${p.plataforma}</small></div>`;
            item.title = p.legenda || p.titulo;
            cell.appendChild(item);
        });

        grid.appendChild(cell);
    }

    timeline.innerHTML = '';
    timeline.appendChild(grid);

    timeline.querySelectorAll('.timeline-thumb').forEach((img) => {
        img.addEventListener('click', () => {
            const post = postById[img.dataset.postId] || {};
            openImageModal(img.dataset.full, post);
        });
    });

    function openImageModal(src, post) {
        const existing = document.getElementById('postImageModal');
        if (existing) existing.remove();

        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'postImageModal';
        modal.tabIndex = -1;
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalhes do post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 align-items-start">
                            <div class="col-12 col-md-6 text-center">
                                <img src="${src}" alt="${post.titulo || 'Post'}" class="img-fluid rounded shadow-sm">
                            </div>
                            <div class="col-12 col-md-6">
                                <h4 class="mb-2">${post.titulo || '-'}</h4>
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item px-0"><strong>Plataforma:</strong> ${post.plataforma || '-'}</li>
                                    <li class="list-group-item px-0"><strong>Data:</strong> ${post.post_date || '-'} ${String(post.post_time || '').slice(0, 5)}</li>
                                    <li class="list-group-item px-0"><strong>Tipo:</strong> ${post.tipo_conteudo || '-'}</li>
                                    <li class="list-group-item px-0"><strong>Status:</strong> ${post.status || '-'}</li>
                                    <li class="list-group-item px-0"><strong>CTA:</strong> ${post.cta || '-'}</li>
                                    <li class="list-group-item px-0"><strong>Legenda:</strong> ${post.legenda || '-'}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
})();
