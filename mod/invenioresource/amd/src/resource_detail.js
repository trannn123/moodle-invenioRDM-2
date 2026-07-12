define([
    'core/ajax',
    'core/modal_factory'
], function (
    Ajax,
    ModalFactory
) {

    const init = () => {

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


                const html =
                    await request[0];


                const modal =
                    await ModalFactory.create({
                        type: ModalFactory.types.DEFAULT,

                        title:
                            'Resource Detail',

                        body:
                        html
                    });


                modal.show();

            }
        );

    };


    return {
        init: init
    };

});