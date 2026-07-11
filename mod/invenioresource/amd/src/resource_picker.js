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

            alert('Search listener attached');

            searchButton.addEventListener('click', async () => {

                alert('Search clicked');

                const keyword = searchInput.value.trim();

                if (!keyword) {
                    return;
                }


                const request = Ajax.call([
                    {
                        methodname: 'mod_invenioresource_search',
                        args: {
                            keyword: keyword
                        }
                    }
                ]);


                const response = await request[0];


                const data = JSON.parse(response);

                const records = data.hits?.hits ?? [];

                resultBox.innerHTML = '';

                records.forEach((record) => {

                    const title =
                        record.metadata?.title ?? record.id;

                    const item = document.createElement('div');

                    item.className = 'mb-3';

                    item.innerHTML = `
                        <strong>${title}</strong>
                        <br>
                        <button
                            class="btn btn-secondary select-record"
                            data-id="${record.id}"
                        >
                            Select
                        </button>
                    `;

                    resultBox.appendChild(item);

                    item
                        .querySelector('.select-record')
                        .addEventListener('click', () => {

                            const recordId = record.id;

                            document.querySelector(
                                '[name="recordid"]'
                            ).value = recordId;

                            modal.hide();

                        });

                });

            });

        });

    };


    return {
        init: init
    };

});