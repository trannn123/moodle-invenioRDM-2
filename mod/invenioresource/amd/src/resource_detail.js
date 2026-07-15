define([
    'core/ajax',
    'core/modal_factory'
], function (
    Ajax,
    ModalFactory
) {

    const init = (cmid) => {

        const button = document.querySelector(
            '#view-resource-detail'
        );

        if (!button) {
            return;
        }


        button.addEventListener(
            'click',
            async () => {

                const recordid =
                    button.dataset.recordid;


                const request = Ajax.call([
                    {
                        methodname:
                            'mod_invenioresource_get_resource_detail',

                        args: {
                            recordid: recordid
                        }
                    }
                ]);


                const response =
                    JSON.parse(
                        await request[0]
                    );


                let body = '';


                if (!response.success) {

                    body = `
                        <div class="alert alert-warning">
                            ${response.message}
                        </div>
                    `;

                    if (response.canreplace) {

                        body += `
                            <button 
                                class="btn btn-primary"
                                id="replace-resource">
                                Replace Resource
                            </button>
                        `;

                    }

                } else {

                    body = response.html;

                }


                const modal =
                    await ModalFactory.create({
                        type: ModalFactory.types.DEFAULT,

                        title:
                            'Resource Detail',

                        body:
                        body
                    });


                modal.show();

                const replaceButton =
                    modal.getRoot().find('#replace-resource')[0];


                if (replaceButton) {

                    replaceButton.addEventListener(
                        'click',
                        () => {

                            modal.hide();

                            window.location.href =
                                M.cfg.wwwroot +
                                '/course/modedit.php?update=' +
                                cmid +
                                '&return=1';

                        }
                    );

                }

                require([
                    'mod_invenioresource/metadata_collapse'
                ], function (MetadataCollapse) {

                    MetadataCollapse.init(
                        modal.getRoot()[0]
                    );

                });


                modal.getRoot().on(
                    'hidden.bs.modal',
                    function () {

                        const content =
                            document.querySelector(
                                '#technical-metadata'
                            );

                        if (content) {
                            content.style.display = 'none';
                        }

                    }
                );

            }
        );

    };


    return {
        init: init
    };

});