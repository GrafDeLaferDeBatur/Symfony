const addMask = (phoneNumberInput) => {
    phoneNumberInput.placeholder="+7";
    phoneNumberInput.addEventListener('input', function () {
        let cleanedValue = phoneNumberInput.value.toString().replace(/[^0-9+]/g, '');
        if (!cleanedValue.startsWith('+7')) {
            cleanedValue = '+7' + cleanedValue;
        }
        if (cleanedValue.startsWith('+7+') || phoneNumberInput.value.length === 2) {
            cleanedValue = '';
        }
        phoneNumberInput.value = cleanedValue;
    });
    phoneNumberInput.addEventListener('blur', function () {
        if (phoneNumberInput.value.startsWith('+7+') ||
            (phoneNumberInput.value.startsWith('+7') && phoneNumberInput.value.length <= 2)) {
            phoneNumberInput.value = '';
        }
    });
}
const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    // alert('.' + e.currentTarget.dataset.collectionHolderClass);
    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    item.querySelector('button.btn-danger').addEventListener('click', (e) => {
        e.preventDefault();
        item.remove();
    });

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    addMask(item.querySelector("input[maxlength]"));
};
document
    .querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });

let input = document.querySelector('#product_tags');
input = new Tagify(input);
