define([], function () {

    const init = () => {

        const button = document.querySelector(
            '#view-resource-detail'
        );

        const modal = document.querySelector(
            '#resource-detail-modal'
        );

        const closebutton = document.querySelector(
            '#close-resource-detail'
        );


        if (!button || !modal) {
            return;
        }


        const openModal = () => {

            modal.style.display = 'block';

            modal.classList.add('show');

            modal.setAttribute(
                'aria-hidden',
                'false'
            );

        };


        const closeModal = () => {

            modal.style.display = 'none';

            modal.classList.remove('show');

            modal.setAttribute(
                'aria-hidden',
                'true'
            );

        };


        button.addEventListener(
            'click',
            openModal
        );


        if (closebutton) {

            closebutton.addEventListener(
                'click',
                closeModal
            );

        }


    };


    return {
        init: init
    };

});