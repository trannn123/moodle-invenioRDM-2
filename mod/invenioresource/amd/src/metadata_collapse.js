define([], function () {

    const init = (root) => {

        root = root || document;


        const button = root.querySelector(
            '[data-toggle="technical-metadata"]'
        );


        const content = root.querySelector(
            '#technical-metadata'
        );


        if (!button || !content) {
            return;
        }


        button.addEventListener('click', () => {

            content.classList.toggle('d-none');

        });

    };


    return {
        init: init
    };

});