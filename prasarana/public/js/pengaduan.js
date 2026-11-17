document.addEventListener('DOMContentLoaded', function () {

    const input = document.querySelector('#foto');

    const preview = document.querySelector('#imagePreview');


    if (!input || !preview) {
        return;
    }


    input.addEventListener('change', function () {

        preview.innerHTML = '';


        const files = Array.from(input.files);


        files.forEach(function (file) {

            if (!file.type.startsWith('image/')) {
                return;
            }


            const reader = new FileReader();


            reader.onload = function (event) {

                const wrapper = document.createElement('div');

                wrapper.classList.add('image-preview-item');


                const image = document.createElement('img');

                image.src = event.target.result;

                image.alt = 'Preview foto pengaduan';


                wrapper.appendChild(image);

                preview.appendChild(wrapper);

            };


            reader.readAsDataURL(file);

        });

    });

});