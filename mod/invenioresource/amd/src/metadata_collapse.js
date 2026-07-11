define([], function () {

    const init = () => {

        const button = document.querySelector(
            '[data-toggle="technical-metadata"]'
        );

        const content = document.querySelector(
            '#technical-metadata'
        );


        if (!button || !content) {
            return;
        }


        button.addEventListener('click', () => {

            if (content.style.display === 'none') {

                content.style.display = 'block';

            } else {

                content.style.display = 'none';

            }

        });

    };


    return {
        init: init
    };

});