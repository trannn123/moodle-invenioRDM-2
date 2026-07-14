define([
    'core/ajax',
    'core/modal_factory'
], function (
    Ajax,
    ModalFactory
) {

    const init = (cmid) => {

        alert('CMID=' + cmid);

        const button = document.querySelector('[name="selectresource"]');

        if (!button) {
            return;
        }

        const clearButton = document.querySelector(
            '[name="clearresource"]'
        );

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
                
                <div class="mb-3">
                    <div class="fw-bold mb-2">
                        Popular keywords
                    </div>
                
                    <div
                        id="popular-keywords"
                        class="d-flex flex-wrap gap-2"
                    ></div>
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

            const modalRoot = modal.getRoot();

            const searchButton =
                modalRoot.find('#invenio-search-btn')[0];

            const searchInput =
                modalRoot.find('#invenio-search-keyword')[0];

            const resultBox =
                modalRoot.find('#invenio-search-result')[0];

            const statusBox =
                modalRoot.find('#invenio-search-status')[0];

            const popularBox =
                modalRoot.find('#popular-keywords')[0];

            const popularRequest = Ajax.call([
                {
                    methodname: 'mod_invenioresource_get_popular_keywords',
                    args: {}
                }
            ]);

            let popularKeywords = [];

            try {

                popularKeywords = await popularRequest[0];

            } catch (error) {

                statusBox.textContent = JSON.stringify(error);

            }

            if (popularBox && popularKeywords.length > 0) {

                popularBox.innerHTML = '';

                popularKeywords.forEach((keyword) => {

                    const button = document.createElement('button');

                    button.className =
                        'btn btn-sm btn-outline-primary rounded-pill me-2 mb-2';

                    button.textContent = keyword;

                    button.addEventListener('click', () => {

                        searchInput.value = keyword;

                    });

                    popularBox.appendChild(button);
                });

            }

            searchButton.addEventListener('click', async () => {

                const keyword = searchInput.value.trim();

                statusBox.textContent = 'Searching...';

                searchButton.disabled = true;

                if (!keyword) {

                    statusBox.textContent =
                        'Please enter keyword.';

                    searchButton.disabled = false;

                    return;
                }

                statusBox.textContent = 'Searching...';

                const request = Ajax.call([
                    {
                        methodname: 'mod_invenioresource_search',
                        args: {
                            keyword: keyword,
                            cmid: cmid
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
                            .addEventListener('click', async () => {

                                const request = Ajax.call([
                                    {
                                        methodname:
                                            'mod_invenioresource_get_resource_detail',
                                        args: {
                                            recordid: record.id
                                        }
                                    }
                                ]);

                                const html = await request[0];

                                const previewModal = await ModalFactory.create({
                                    type: ModalFactory.types.DEFAULT,
                                    title: 'Resource Detail',
                                    body: html
                                });

                                previewModal.show();

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

                                setTimeout(() => {
                                    modal.destroy();
                                }, 300);

                            });

                    });

                } catch (error) {

                    statusBox.textContent =
                        JSON.stringify(error);

                    searchButton.disabled = false;

                }

            });

        });

    };

    return {
        init: init
    };

});