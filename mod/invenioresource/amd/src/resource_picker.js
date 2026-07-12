define([
    'core/ajax',
    'core/modal_factory'
], function (
    Ajax,
    ModalFactory
) {

    const init = () => {

        const button = document.querySelector('[name="selectresource"]');

        if (!button) {
            return;
        }

        const clearButton = document.querySelector(
            '[name="clearresource"]'
        );

        const closeButton =
            document.querySelector(
                '#close-resource-preview'
            );


        if (closeButton) {

            closeButton.addEventListener(
                'click',
                () => {

                    const previewModal =
                        document.querySelector(
                            '#resource-preview-modal'
                        );


                    if (!previewModal) {
                        return;
                    }


                    previewModal.style.display = 'none';

                    previewModal.classList.remove('show');

                    document.body.classList.remove('modal-open');


                    const backdrop =
                        document.querySelector(
                            '#resource-preview-backdrop'
                        );


                    if (backdrop) {
                        backdrop.remove();
                    }

                }
            );

        }

        if (clearButton) {

            clearButton.addEventListener(
                'click',
                (e) => {

                    e.preventDefault();

                    const recordField =
                        document.querySelector(
                            '[name="recordid"]'
                        );

                    if (recordField) {
                        recordField.value = '';
                    }


                    const selectedName =
                        document.querySelector(
                            '#selected-resource-name'
                        );

                    if (selectedName) {
                        selectedName.textContent =
                            'No resource selected';
                    }

                }
            );

        }

        button.addEventListener('click', async (e) => {

            e.preventDefault();

            const modal = await ModalFactory.create({
                type: ModalFactory.types.DEFAULT,
                title: 'Search Invenio Resource',
                body: `
                <div class="mb-3">
                    <input
                        id="invenio-search-keyword"
                        class="form-control"
                        type="text"
                        placeholder="Enter keyword..."
                    >
                </div>
                
                <button
                    id="invenio-search-btn"
                    class="btn btn-primary"
                >
                    Search
                </button>
                
                <hr>
                
                <div id="invenio-search-status" class="mb-3">
                
                </div>
                
                <div id="invenio-search-result">
                
                </div>
                `
            });

            modal.show();

            const searchButton = document.getElementById(
                'invenio-search-btn'
            );

            const searchInput = document.getElementById(
                'invenio-search-keyword'
            );

            const resultBox = document.getElementById(
                'invenio-search-result'
            );

            searchButton.addEventListener('click', async () => {

                const keyword = searchInput.value.trim();

                const statusBox =
                    document.getElementById(
                        'invenio-search-status'
                    );

                statusBox.textContent = 'Searching...';

                searchButton.disabled = true;

                if (!keyword) {
                    statusBox.textContent = 'Please enter keyword.';
                    return;
                }

                statusBox.textContent = 'Searching...';

                const request = Ajax.call([
                    {
                        methodname: 'mod_invenioresource_search',
                        args: {
                            keyword: keyword
                        }
                    }
                ]);

                try {

                    const response = await request[0];

                    statusBox.textContent = '';

                    const data = JSON.parse(response);

                    const records = data.hits?.hits ?? [];

                    resultBox.innerHTML = '';

                    searchButton.disabled = false;

                    if (records.length === 0) {

                        resultBox.textContent =
                            'No resource found.';

                        return;
                    }

                    records.forEach((record) => {

                        const title =
                            record.metadata?.title ?? record.id;

                        const item = document.createElement('div');

                        item.className = 'mb-3';

                        item.innerHTML = `
                            <strong>${title}</strong>
                            <br>
                            <button
                                class="btn btn-info detail-record me-2"
                                data-id="${record.id}"
                            >
                                Detail
                            </button>
                            
                            <button
                                class="btn btn-secondary select-record"
                                data-id="${record.id}"
                            >
                                Select
                            </button>
                        `;

                        resultBox.appendChild(item);

                        item
                            .querySelector('.detail-record')
                            .addEventListener('click', () => {

                                const previewModal =
                                    document.querySelector(
                                        '#resource-preview-modal'
                                    );

                                const content =
                                    document.querySelector(
                                        '#resource-preview-content'
                                    );


                                if (!previewModal || !content) {
                                    return;
                                }


                                const metadata =
                                    record.metadata ?? {};


                                content.innerHTML = `

                                    <h4>
                                        ${metadata.title ?? 'Untitled'}
                                    </h4>
                        
                                    <hr>
                        
                                    <p>
                                        <strong>Description:</strong>
                                        <br>
                                        ${
                                    metadata.description
                                    ?? 'No description'
                                }
                                    </p>
                        
                                    <p>
                                        <strong>Record ID:</strong>
                                        ${record.id}
                                    </p>
                        
                                `;

                                previewModal.style.display = 'block';

                                previewModal.classList.add('show');

                                previewModal.style.zIndex = '2000';

                                document.body.classList.add('modal-open');

                                const dialog =
                                    previewModal.querySelector(
                                        '.modal-dialog'
                                    );

                                if (dialog) {
                                    dialog.style.zIndex = '2001';
                                }

                            });

                        item
                            .querySelector('.select-record')
                            .addEventListener('click', () => {

                                const recordId = record.id;

                                document.querySelector(
                                    '[name="recordid"]'
                                ).value = recordId;


                                const selectedName =
                                    document.querySelector(
                                        '#selected-resource-name'
                                    );

                                if (selectedName) {
                                    selectedName.textContent = title;
                                }


                                modal.hide();

                            });

                    });

                } catch (error) {

                    statusBox.textContent =
                        'Search failed.';

                    searchButton.disabled = false;

                }

            });

        });

    };

    return {
        init: init
    };

});